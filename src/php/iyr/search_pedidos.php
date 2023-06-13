<?php
include('../connection.php');

$response = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    if (empty($search)){
        die("No se enviaron parámetros de búsqueda");
    }

    $query = "SELECT
                * 
            FROM pedidos
            WHERE 
            ci LIKE ?
            ORDER BY ci ASC";
    $stmt = mysqli_prepare($connection, $query);
    $searchParam = "%{$search}%";
    mysqli_stmt_bind_param($stmt, "sss", $searchParam, $searchParam, $searchParam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fecha_hora = new DateTime($row["fecha_hora"]);

            $customer = [
                "id_pedido" => $row["id_pedido"],
                "ci" => $row["ci"],
                "id_producto" => $row["id_producto"],
                "id_sucursal" => $row["id_sucursal"],
                "id_tipopedido" => $row["id_tipopedido"],
                "cantidad" => $row["cantidad"],
                "precio" => $row["precio"],
                "estado_pedido" => $row["estado_pedido"],
                "fecha_hora" => $row["fecha_hora"]

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
