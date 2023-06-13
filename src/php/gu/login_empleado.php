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
              WHERE usuarios.ci = ?";

    $stmt = mysqli_prepare($connection, $query);

    if (!$stmt) {
        $response = new ErrorResponse(500, "Error en la preparación de la consulta: " . mysqli_error($connection));
        sendResponse($response);
    }

    mysqli_stmt_bind_param($stmt, "i", $ci);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($result && $row) {
        if (verifyWithArgon2($password, $row["contrasena"])) {
            $fecha_hora_coordenada = new DateTime($row["fecha_hora_coordenada"]);

            $response = [
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
        } else {
            $response = new ErrorResponse(401, "La contraseña no coincide.");
            sendResponse($response);
        }
    } else {
        if (mysqli_num_rows($result) === 0) {
            $response = new ErrorResponse(404, "No se encontró ningún usuario con ese CI.");
        } else {
            $response = new ErrorResponse(500, "Error en la consulta: " . mysqli_error($connection));
        }
    }

    mysqli_stmt_close($stmt);

    if (isset($_POST["system"]) && $_POST["system"] == "Webapp" && $row["estado"] === 1) {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            session_write_close();
        }
        session_start();

        /* -------------------------------- Opcion 1 -------------------------------- */
        // Recuperar en JSON
        $_SESSION["empleado"] = [
            "usuario" => [
                "ci" => $row["ci"],
                "contrasena" => $row["contrasena"],
                "tipousuario" => [
                    "id" => $row["id_tipousuario"],
                    "tipo" => $row["tipo_tipousuario"],
                ],
            ],
            "sucursal" => [
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
            ],
            "nombres" => $row["nombres"],
            "apellidos" => $row["apellidos"],
            "telefono" => $row["telefono"],
            "estado" => $row["estado"],
        ];

        /* -------------------------------- Opcion 2 -------------------------------- */
        // Recuperar response
        $_SESSION["response"] = $response;

        /* -------------------------------- Opcion 3 -------------------------------- */
        // Recuperar cada dato en una variable
        // Usuario
        $_SESSION["ci"] = $row["ci"];
        $_SESSION["contrasena"] = $row["contrasena"];

        // tipousuario
        $_SESSION["id_tipousuario"] = $row["id_tipousuario"];
        $_SESSION["tipo_tipousuario"] = $row["tipo_tipousuario"];

        //  sucursal
        $_SESSION["id_sucursal"] = $row["id_sucursal"];

        // coordenadas
        $_SESSION["id_coordenada"] = $row["id_coordenada"];
        $_SESSION["latitud_coordenada"] = $row["latitud_coordenada"];
        $_SESSION["longitud_coordenada"] = $row["longitud_coordenada"];
        $_SESSION["fecha_hora_coordenada"] = $fecha_hora_coordenada->format('Y-m-d H:i:s');

        // ciudad
        $_SESSION["id_ciudad"] = $row["id_ciudad"];
        $_SESSION["nombre_ciudad"] = $row["nombre_ciudad"];

        $_SESSION["nombre_sucursal"] = $row["nombre_sucursal"];
        $_SESSION["direccion_sucursal"] = $row["direccion_sucursal"];
        $_SESSION["estado_sucursal"] = $row["estado_sucursal"];
        $_SESSION["telefono_sucursal"] = $row["telefono_sucursal"];
        $_SESSION["hora_apertura_sucursal"] = $row["hora_apertura_sucursal"];
        $_SESSION["hora_cierre_sucursal"] = $row["hora_cierre_sucursal"];

        $_SESSION["nombres"] = $row["nombres"];
        $_SESSION["apellidos"] = $row["apellidos"];
        $_SESSION["telefono"] = $row["telefono"];
        $_SESSION["estado"] = $row["estado"];
    }
}

$response = $response ?: new ErrorResponse(401, "No autorizado");
sendResponse($response);

mysqli_close($connection);
