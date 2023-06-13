<?php
include('../connection.php');

$response = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = isset($_POST["searchestado"]) ? $_POST["searchestado"] : "";
    if (empty($search)){
        die("No se enviaron parámetros de búsqueda");
    }

    $query = "SELECT
                * 
            FROM clientes
            WHERE 
                estado = 0
            ORDER BY apellidos ASC";
    $stmt = mysqli_prepare($connection, $query);
    $searchParam = "%{$search}%";
    mysqli_stmt_bind_param($stmt, "sss", $searchParam, $searchParam, $searchParam);
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
}

echo json_encode($response);
?>
