<?php
include('../connection.php');

$response = [];

$query = "SELECT 
            sucursales.id_sucursal as id_sucursal,

                coordenadas.id_coordenada as id_coordenada, 
                coordenadas.latitud as latitud_coordenada,
                coordenadas.longitud as longitud_coordenada,
                coordenadas.fecha_hora as fecha_hora_coordenada,

                ciudad.id_ciudad as id_ciudad,
                ciudad.ciudad as nombre_ciudad,

            sucursales.nombre as nombre_sucursal,
            sucursales.direccion as direccion_sucursal,
            sucursales.estado as estado_sucursal,
            sucursales.telefono as telefono_sucursal,
            sucursales.hora_apertura as hora_apertura_sucursal,
            sucursales.hora_cierre as hora_cierre_sucursal
          FROM sucursales
          LEFT JOIN coordenadas ON coordenadas.id_coordenada = sucursales.id_coordenada
          LEFT JOIN ciudad on ciudad.id_ciudad = sucursales.id_ciudad";

$state = isset($_POST["state"]) ? $_POST["state"] : -1;
if (!is_numeric($state)) die(json_encode($response));

if ($state !== -1) {
    $query .= " WHERE sucursales.estado = ?";
}

$query .= " ORDER BY sucursales.id_sucursal ASC;";

$stmt = mysqli_prepare($connection, $query);
if ($state !== -1 ) {
    mysqli_stmt_bind_param($stmt, "i", $state);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fecha_hora_coordenada = new DateTime($row["fecha_hora_coordenada"]);

        $response[] = [
            "id" => $row["id_sucursal"],
            "coordenadas" => [
                "id" => $row["id_coordenada"],
                "latitud" => $row["latitud_coordenada"],
                "longitud" => $row["longitud_coordenada"],
                "fecha_hora" => $fecha_hora_coordenada->format('Y-m-d H:i:s'),
            ],
            "ciudad" => [
                "id" => $row["id_ciudad"],
                "nombre" => $row["nombre_ciudad"],
            ],
            "nombre" => $row["nombre_sucursal"],
            "direccion" => $row["direccion_sucursal"],
            "estado" => $row["estado_sucursal"],
            "telefono" => $row["telefono_sucursal"],
            "hora_apertura" => $row["hora_apertura_sucursal"],
            "hora_cierre" => $row["hora_cierre_sucursal"],
        ];
    }
} else {
    if ($result && mysqli_num_rows($result) === 0) {
        die(json_encode($response));
    } else {
        die("Query failed: " . mysqli_error($connection));
    }
}

mysqli_stmt_close($stmt);

echo json_encode($response);

mysqli_close($connection);
?>
