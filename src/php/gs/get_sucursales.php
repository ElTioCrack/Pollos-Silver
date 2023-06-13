<?php
include('../connection.php');

$response = [];
$query = "SELECT * FROM sucursales as S LEFT JOIN coordenadas as C ON (S.id_coordenada = C.id_coordenada) LEFT JOIN ciudad as CI ON (S.id_ciudad=CI.id_ciudad)";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fecha_hora = new DateTime($row["hora_apertura"]);
        $fecha_horas = new DateTime($row["hora_cierre"]);

        $customer = [
            "latitud" => $row["latitud"],
            "longitud" => $row["longitud"],
            "nombre" => $row["nombre"],
            "direccion" => $row["direccion"],
            "telefono" => $row["telefono"],
            "hora_apertura" => $fecha_hora->format('Y-m-d H:i:s'),
            "hora_cierre" => $fecha_horas->format('Y-m-d H:i:s'),
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