<?php
include('../connection.php');

$response = [];

if (true) {
    // Verificar si los campos "ci" y "password" existen y no están vacíos
    if (!isset($_POST["ci"], $_POST["password"]) || empty($_POST["ci"]) || empty($_POST["password"])) {
        $response["error"] = "Los campos CI y contraseña son requeridos.";
        die(json_encode($response));
    }

    $ci = $_POST["ci"];
    $password = $_POST["password"];

    // Verificar si $ci es un número válido
    if (!is_numeric($ci)) {
        $response["error"] = "El campo CI debe ser numérico.";
        die(json_encode($response));
    }

    // Prevenir inyecciones SQL en $password
    $password = mysqli_real_escape_string($connection, $password);

    // Consulta preparada para proteger contra inyecciones SQL
    $query = "SELECT usuarios.ci, usuarios.contrasena, 
                tipousuario.id_tipousuario, tipousuario.tipo as tipo_tipousuario
                FROM usuarios 
                JOIN tipousuario ON tipousuario.id_tipousuario = usuarios.id_tipousuario
                WHERE usuarios.ci = ?";

    $stmt = mysqli_prepare($connection, $query);

    if (!$stmt) {
        $response["error"] = "Error en la preparación de la consulta: " . mysqli_error($connection);
        die(json_encode($response));
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
            $response["error"] = "La contraseña no coincide.";
            die(json_encode($response));
        }
    } else {
        if (mysqli_num_rows($result) === 0) {
            $response["error"] = "No se encontró ningún usuario con ese CI.";
            die(json_encode($response));
        } else {
            $response["error"] = "Error en la consulta: " . mysqli_error($connection);
            die(json_encode($response));
        }
    }

    mysqli_stmt_close($stmt);
}

echo json_encode($response);

mysqli_close($connection);

function verifyWithArgon2(string $userInput, string $storedHash): bool
{
    return password_verify($userInput, $storedHash);
}