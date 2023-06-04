<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = mysqli_real_escape_string($connection, $_POST['search']);
    if (!empty($search)) {
        $query = "SELECT id_producto, id_categoria, id_sucursal, nombre, descripcion, imagen_producto, precio_total, precio_unitario, cantidad, estado, fecha_hora FROM productos WHERE nombre LIKE '$search%'";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die('Error de Consulta' . mysqli_error($connection));
        }
        $json = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $json[]= array(
                'id_producto' => $row['id_producto'],
                'id_categoria' => $row['id_categoria'],
                'id_sucursal' => $row['id_sucursal'],
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'imagen_producto' => $row['imagen_producto'],
                'precio_total' => $row['precio_total'],
                'precio_unitario' => $row['precio_unitario'],
                'cantidad' => $row['cantidad'],
                'estado' => $row['estado'],
                'fecha_hora' => $row['fecha_hora']
            );
        }
        mysqli_free_result($result);
        mysqli_close($connection);

        $jsonString = json_encode($json);
        echo $jsonString;
    }
}
?>
