<?php
include('../connection.php');

$response = [];

// Verificar si se han enviado los parámetros por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los parámetros de POST
    $ci = $_POST["ci"];
    $contrasena = $_POST["contrasena"];
    $id_sucursal = $_POST["id_sucursal"];
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $telefono = $_POST["telefono"];
    $estado = $_POST["estado"];

    // Encriptar la contraseña utilizando Argon2
    $time_cost = 8; // Costo de tiempo para Argon2
    $memory_cost = 131072; // Costo de memoria para Argon2
    $threads = 1; // Número de hilos para Argon2
    $contrasena_encriptada = encryptWithArgon2($contrasena, $time_cost, $memory_cost, $threads);

    // Llamar al procedimiento almacenado utilizando una consulta preparada
    $sql = "CALL CreateUserAndEmployee(?, ?, ?, ?, ?, ?, ?, @creado, @mensaje)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isisssi", $ci, $contrasena_encriptada, $id_sucursal, $nombres, $apellidos, $telefono, $estado);
    $stmt->execute();

    // Obtener los valores de las variables creadas en el procedimiento almacenado
    $result = $connection->query("SELECT @creado AS creado, @mensaje AS mensaje");
    $row = $result->fetch_assoc();

    $response = [
        "creado" => $row['creado'],
        "mensaje" => $row['mensaje']
    ];
}

// Cerrar conexión
$stmt->close();
$connection->close();

// Enviar la respuesta como JSON
echo json_encode($response);

function encryptWithArgon2($data, $time_cost, $memory_cost, $threads)
{
    // Genera el hash de la variable utilizando Argon2
    $hashedData = password_hash($data, PASSWORD_ARGON2I, [
        'time_cost' => $time_cost,
        'memory_cost' => $memory_cost,
        'threads' => $threads
    ]);

    return $hashedData;
}