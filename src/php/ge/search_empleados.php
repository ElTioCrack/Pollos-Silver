<?php
include('../connection.php');

$response = [];

if (isset($_POST["state"]) && isset($_POST["search"])) { // Verificar si tanto "state" como "search" están definidos en el POST
    $state = $_POST["state"];
    $search = $_POST["search"];
    $searchTerm = "%{$search}%";

    $query = "SELECT 
                  usuarios.ci, usuarios.contrasena, 
                  tipousuario.id_tipousuario, tipousuario.tipo as tipo_tipousuario,
                  sucursales.id_sucursal,
                  coordenadas.id_coordenada, coordenadas.latitud as latitud_coordenada, coordenadas.longitud as longitud_coordenada, coordenadas.fecha_hora as fecha_hora_coordenada,
                  ciudad.id_ciudad, ciudad.ciudad as nombre_ciudad,
                  sucursales.nombre as nombre_sucursal, sucursales.direccion as direccion_sucursal, sucursales.estado as estado_sucursal, sucursales.telefono as telefono_sucursal, sucursales.hora_apertura as hora_apertura_sucursal, sucursales.hora_cierre as hora_cierre_sucursal,
                  empleados.nombres, empleados.apellidos, empleados.telefono, empleados.estado
              FROM usuarios 
              JOIN tipousuario ON usuarios.id_tipousuario = tipousuario.id_tipousuario
              LEFT JOIN empleados ON empleados.ci = usuarios.ci 
              LEFT JOIN sucursales on sucursales.id_sucursal = empleados.id_sucursal
              LEFT JOIN coordenadas ON coordenadas.id_coordenada = sucursales.id_coordenada
              LEFT JOIN ciudad on ciudad.id_ciudad = sucursales.id_ciudad
              WHERE empleados.estado = ? AND (usuarios.ci LIKE ? OR empleados.nombres LIKE ? OR empleados.apellidos LIKE ?)";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "isss", $state, $searchTerm, $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fecha_hora_coordenada = new DateTime($row["fecha_hora_coordenada"]);

            $response[] = [
                "usuario" => [
                    "ci" => $row["ci"],
                    "contrasena" => $row["contrasena"],
                    "tipousuario" => [
                        "id_tipousuario" => $row["id_tipousuario"],
                        "tipo_tipousuario" => $row["tipo_tipousuario"],
                    ],
                ],
                "sucursal" => [
                    "id_sucursal" => $row["id_sucursal"],
                    "coordenadas" => [
                        "id_coordenada" => $row["id_coordenada"],
                        "latitud_coordenada" => $row["latitud_coordenada"],
                        "longitud_coordenada" => $row["longitud_coordenada"],
                        "fecha_hora_coordenada" => $fecha_hora_coordenada->format('Y-m-d H:i:s'),
                    ],
                    "ciudad" => [
                        "id_ciudad" => $row["id_ciudad"],
                        "nombre_ciudad" => $row["nombre_ciudad"],
                    ],
                    "nombre_sucursal" => $row["nombre_sucursal"],
                    "direccion_sucursal" => $row["direccion_sucursal"],
                    "estado_sucursal" => $row["estado_sucursal"],
                    "telefono_sucursal" => $row["telefono_sucursal"],
                    "hora_apertura_sucursal" => $row["hora_apertura_sucursal"],
                    "hora_cierre_sucursal" => $row["hora_cierre_sucursal"],
                ],
                "nombres" => $row["nombres"],
                "apellidos" => $row["apellidos"],
                "telefono" => $row["telefono"],
                "estado" => $row["estado"],
            ];
        }

        mysqli_free_result($result);
    } else {
        die("Query failed: " . mysqli_error($connection));
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($connection);

echo json_encode($response);
?>
