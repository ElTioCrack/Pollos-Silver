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
            FROM empleados
            WHERE 
                nombres LIKE ? or 
                apellidos LIKE ? or 
                ci LIKE ?
            ORDER BY apellidos ASC";
    $stmt = mysqli_prepare($connection, $query);
    $searchParam = "%{$search}%";
    mysqli_stmt_bind_param($stmt, "sss", $searchParam, $searchParam, $searchParam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
           // $fecha_hora = new DateTime($row["fecha_hora"]);

            $customer = [
                "ci" => $row["ci"],
                "id_sucursal" => $row["id_sucursal"],
                "nombres" => $row["nombres"],
                "apellidos" => $row["apellidos"],
                "telefono" => $row["telefono"],
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
