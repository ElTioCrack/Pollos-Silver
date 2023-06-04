<?php
include('../connection.php');

$response = [];

// Obtener el número de CI y el estado de la solicitud
$ci = isset($_POST["ci"]) ? $_POST["ci"] : die(json_encode($response));
$state = isset($_POST["state"]) ? $_POST["state"] : die(json_encode($response));

// Validar que los valores sean numéricos
if (!is_numeric($ci) || !is_numeric($state)) {
    die(json_encode($response));
}

// Consulta SQL para actualizar el estado de un empleado
$query = "UPDATE empleados SET estado = ? WHERE ci = ?";

// Preparar la declaración
$stmt = mysqli_prepare($connection, $query);

// Vincular los parámetros
mysqli_stmt_bind_param($stmt, "ii", $state, $ci);

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    // Obtener el número de filas afectadas
    $rowsAffected = mysqli_stmt_affected_rows($stmt);

    // Verificar si se actualizaron filas
    if ($rowsAffected > 0) {
        $response = [
            "success" => true,
            "message" => "Empleado actualizado correctamente",
            "rowsAffected" => $rowsAffected
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "No se encontró ningún empleado con el número de CI proporcionado"
        ];
    }
} else {
    // Error en la ejecución de la consulta
    $response = [
        "success" => false,
        "message" => "Error en la consulta: " . mysqli_error($connection)
    ];
}

// Cerrar la declaración
mysqli_stmt_close($stmt);

// Cerrar la conexión a la base de datos
mysqli_close($connection);

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
