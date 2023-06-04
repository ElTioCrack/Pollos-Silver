<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'])) {
        $id_categoria = mysqli_real_escape_string($connection, $_POST['id_categoria']);
        $id_sucursal = mysqli_real_escape_string($connection, $_POST['id_sucursal']);
        $nombre = mysqli_real_escape_string($connection, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($connection, $_POST['descripcion']);
        /*IMAGENES*/
        $imagen_producto = mysqli_real_escape_string($connection, $_POST['imagen_producto']);
        $precio_total = mysqli_real_escape_string($connection, $_POST['precio_total']);
        $precio_unitario = mysqli_real_escape_string($connection, $_POST['precio_unitario']);
        $cantidad = mysqli_real_escape_string($connection, $_POST['cantidad']);
        $estado = mysqli_real_escape_string($connection, $_POST['estado']);
        $fecha_hora = mysqli_real_escape_string($connection, $_POST['fecha_hora']);
        $query = "INSERT INTO productos (id_categoria, id_sucursal, nombre, descripcion, imagen_producto, precio_total, precio_unitario, cantidad, estado, fecha_hora)
        VALUES ('$id_categoria', '$id_sucursal', '$nombre', '$descripcion', '$imagen_producto', '$precio_total', '$precio_unitario', '$cantidad', '$estado', '$fecha_hora')";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Error al insertar producto: " . mysqli_error($connection));
        }
        echo 'Producto agregado XD';
    }
}
?>
