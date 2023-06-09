<?php
include('../connection.php');

$response = [
    "success" => false,
    "message" => "No se han realizado cambios en la información del empleado."
];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die(json_encode($response));
}

$ci = filter_input(INPUT_POST, 'ci', FILTER_VALIDATE_INT);
$nombres = filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_STRING);
$apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
$telefono = filter_input(INPUT_POST, 'telefono', FILTER_VALIDATE_INT);
$id_sucursal = filter_input(INPUT_POST, 'id_sucursal', FILTER_VALIDATE_INT);
$contrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_STRING);

if ($ci === false || $nombres === null || $apellidos === null || $telefono === false || $id_sucursal === false || $contrasena === null) {
    die(json_encode($response));
}

$queryEmpleados = "UPDATE empleados SET nombres = ?, apellidos = ?, telefono = ?, id_sucursal = ? WHERE ci = ?";
$queryUsuarios = "SELECT contrasena FROM usuarios WHERE ci = ?";
$stmtUsuarios = mysqli_prepare($connection, $queryUsuarios);
mysqli_stmt_bind_param($stmtUsuarios, "i", $ci);
mysqli_stmt_execute($stmtUsuarios);
$resultUsuarios = mysqli_stmt_get_result($stmtUsuarios);
$row = mysqli_fetch_assoc($resultUsuarios);
if(!$resultUsuarios) die(json_encode($response));
$hashedPassword = $row['contrasena'];

$isNewPassword = !verifyWithArgon2($contrasena, $hashedPassword);

if ($isNewPassword) {
    $queryUsuarios = "UPDATE usuarios SET contrasena = ? WHERE ci = ?";
    $contrasena = encryptWithArgon2($contrasena);
}

mysqli_begin_transaction($connection);

try {
    $stmtEmpleados = mysqli_prepare($connection, $queryEmpleados);
    $stmtUsuarios = mysqli_prepare($connection, $queryUsuarios);

    mysqli_stmt_bind_param($stmtEmpleados, "ssiii", $nombres, $apellidos, $telefono, $id_sucursal, $ci);
    mysqli_stmt_bind_param($stmtUsuarios, "si", $contrasena, $ci);

    mysqli_stmt_execute($stmtEmpleados);
    $rowsAffectedEmpleados = mysqli_stmt_affected_rows($stmtEmpleados);

    if ($isNewPassword) {
        mysqli_stmt_execute($stmtUsuarios);
        $rowsAffectedUsuarios = mysqli_stmt_affected_rows($stmtUsuarios);
    } else {
        $rowsAffectedUsuarios = 0;
    }

    if ($rowsAffectedEmpleados > 0 || $rowsAffectedUsuarios > 0) {
        mysqli_commit($connection);

        $response = [
            "success" => true,
            "message" => "La información del empleado se ha actualizado correctamente.",
            "rowsAffectedEmpleados" => $rowsAffectedEmpleados,
            "rowsAffectedUsuarios" => $rowsAffectedUsuarios
        ];
    } else {
        mysqli_rollback($connection);
    }
} catch (Exception $e) {
    mysqli_rollback($connection);
    $response["message"] = "Error en la transacción: " . $e->getMessage();
}

mysqli_stmt_close($stmtEmpleados);
mysqli_stmt_close($stmtUsuarios);
mysqli_close($connection);

echo json_encode($response);

function encryptWithArgon2($data)
{
    $time_cost = 8; // Costo de tiempo para Argon2
    $memory_cost = 131072; // Costo de memoria para Argon2
    $threads = 1; // Número de hilos para Argon2

    // Genera el hash de la variable utilizando Argon2
    $hashedData = password_hash($data, PASSWORD_ARGON2I, [
        'time_cost' => $time_cost,
        'memory_cost' => $memory_cost,
        'threads' => $threads
    ]);

    return $hashedData;
}

function verifyWithArgon2($input, $hash)
{
    return password_verify($input, $hash);
}
