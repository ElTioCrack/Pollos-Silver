<?php
include('../connection.php');
include('./response.php');
include('../argon2/encryption.php');

$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si los campos "ci" y "password" existen y no están vacíos
    if (!isset($_POST["ci"], $_POST["password"]) || empty($_POST["ci"]) || empty($_POST["password"])) {
        $response = new ErrorResponse(400, "Los campos CI y contraseña son requeridos.");
        sendResponse($response);
    }

    $ci = $_POST["ci"];
    $password = $_POST["password"];

    // Verificar si $ci es un número válido
    if (!is_numeric($ci)) {
        $response = new ErrorResponse(400, "El campo CI debe ser numérico.");
        sendResponse($response);
    }

    // Prevenir inyecciones SQL en $password
    $password = mysqli_real_escape_string($connection, $password);

    // Consulta preparada para proteger contra inyecciones SQL
    $query = "SELECT 
                usuarios.ci, usuarios.contrasena, 
                tipousuario.id_tipousuario, tipousuario.tipo as tipo_tipousuario
              FROM usuarios 
              JOIN tipousuario ON tipousuario.id_tipousuario = usuarios.id_tipousuario
              WHERE usuarios.ci = ?";

    $stmt = mysqli_prepare($connection, $query);

    if (!$stmt) {
        $response = new ErrorResponse(500, "Error en la preparación de la consulta: " . mysqli_error($connection));
        sendResponse($response);
    }

    mysqli_stmt_bind_param($stmt, "i", $ci);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        if (verifyWithArgon2($password, $row["contrasena"])) {
            $response = [
                "ci" => $row["ci"],
                "contrasena" => $row["contrasena"],
                "tipousuario" => [
                    "id_tipousuario" => $row["id_tipousuario"],
                    "tipo_tipousuario" => $row["tipo_tipousuario"],
                ],
            ];
        } else {
            $response = new ErrorResponse(401, "La contraseña no coincide.");
            sendResponse($response);
        }
    } else {
        if (mysqli_num_rows($result) === 0) {
            $response = new ErrorResponse(404, "No se encontró ningún usuario con ese CI.");
            sendResponse($response);
        } else {
            $response = new ErrorResponse(500, "Error en la consulta: " . mysqli_error($connection));
            sendResponse($response);
        }
    }

    mysqli_stmt_close($stmt);
}

$response = $response ?: new ErrorResponse(401, "No autorizado");
sendResponse($response);

mysqli_close($connection);
