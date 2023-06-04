<?php
    include('../connection.php');

    $query = "SELECT id_producto, id_categoria, id_sucursal, nombre, descripcion, imagen_producto, precio_total, precio_unitario, cantidad, estado, fecha_hora FROM productos";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die('Consulta fallida: ' . mysqli_error($connection));
    }
    $json = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $json[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($connection);

    $jsonString = json_encode($json);
    if ($jsonString === false) {
        die('Error al codificar los datos en formato JSON');
    }
    echo $jsonString;
?>
