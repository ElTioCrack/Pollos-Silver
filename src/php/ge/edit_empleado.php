<?php
include('../connection.php');

$response = [];

// Validar y obtener los datos a actualizar para empleados
$ci = filter_input(INPUT_POST, 'ci', FILTER_VALIDATE_INT);
$nombres = filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_STRING);
$apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
$telefono = filter_input(INPUT_POST, 'telefono', FILTER_VALIDATE_INT);
$id_sucursal = filter_input(INPUT_POST, 'id_sucursal', FILTER_VALIDATE_INT);
$estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT);

// Validar y obtener los datos a actualizar para usuarios
$contrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_STRING);

// Verificar si falta algún campo requerido
if ($ci === false || $nombres === null || $apellidos === null || $telefono === false || $id_sucursal === false || $contrasena === null) {
    die(json_encode($response));
}

// Consulta SQL para actualizar los campos de un empleado
$queryEmpleados = "UPDATE empleados SET nombres = ?, apellidos = ?, telefono = ?, id_sucursal = ?, estado = ? WHERE ci = ?";

// Preparar la declaración para empleados
$stmtEmpleados = mysqli_prepare($connection, $queryEmpleados);

// Vincular los parámetros para empleados
mysqli_stmt_bind_param($stmtEmpleados, "ssiiii", $nombres, $apellidos, $telefono, $id_sucursal, $estado, $ci);

// Consulta SQL para actualizar la contraseña de un usuario
$queryUsuarios = "UPDATE usuarios SET contrasena = ? WHERE ci = ?";

// Preparar la declaración para usuarios
$stmtUsuarios = mysqli_prepare($connection, $queryUsuarios);

// Vincular los parámetros para usuarios
mysqli_stmt_bind_param($stmtUsuarios, "si", $contrasena, $ci);

// Iniciar una transacción
mysqli_begin_transaction($connection);

try {
    // Ejecutar la consulta para empleados
    mysqli_stmt_execute($stmtEmpleados);

    // Obtener el número de filas afectadas para empleados
    $rowsAffectedEmpleados = mysqli_stmt_affected_rows($stmtEmpleados);

    // Ejecutar la consulta para usuarios
    mysqli_stmt_execute($stmtUsuarios);

    // Obtener el número de filas afectadas para usuarios
    $rowsAffectedUsuarios = mysqli_stmt_affected_rows($stmtUsuarios);

    // Verificar si se actualizaron filas en ambas consultas
    if ($rowsAffectedEmpleados > 0 || $rowsAffectedUsuarios > 0) {
        // Confirmar la transacción
        mysqli_commit($connection);

        $response = [
            "success" => true,
            "message" => "La información del empleado se ha actualizado correctamente.",
            "rowsAffectedEmpleados" => $rowsAffectedEmpleados,
            "rowsAffectedUsuarios" => $rowsAffectedUsuarios
        ];
    } else {
        // Revertir la transacción
        mysqli_rollback($connection);

        $response = [
            "success" => false,
            "message" => "No se han realizado cambios en la información del empleado."
        ];
    }
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    mysqli_rollback($connection);

    $response = [
        "success" => false,
        "message" => "Error en la transacción: " . $e->getMessage()
    ];
}

// Cerrar las declaraciones
mysqli_stmt_close($stmtEmpleados);
mysqli_stmt_close($stmtUsuarios);

// Cerrar la conexión a la base de datos
mysqli_close($connection);

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
