<?php
include('../connection.php');

$response = [];
$query = "SELECT * FROM clientes ORDER BY apellidos ASC";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fecha_hora = new DateTime($row["fecha_hora"]);

        $customer = [
            "ci" => $row["ci"],
            "nombres" => $row["nombres"],
            "apellidos" => $row["apellidos"],
            "telefono" => $row["telefono"],
            "descripcion_direccion" => $row["descripcion_direccion"],
            "fecha_hora" => $fecha_hora->format('Y-m-d H:i:s'),
            "estado" => $row["estado"],
        ];

        $response[] = $customer;
    }
} else {
    if ($result && mysqli_num_rows($result) === 0) {
        die(json_encode($response));
    } else {
        die("Query failed: " . mysqli_error($connection));
    }
}

mysqli_stmt_close($stmt);
mysqli_close($connection);

echo json_encode($response);
?>