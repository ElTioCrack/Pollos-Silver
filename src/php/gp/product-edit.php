<?php
    include('../connection.php');

    $id_producto = $_POST['id_producto'];
    $id_categoria = $_POST['id_categoria'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $imagen_producto = $_POST['imagen_producto'];
    $estado = $_POST['estado'];
    $precio_unitario = $_POST['precio_unitario'];

    // Preparar consulta con sentencias preparadas
    $query = "UPDATE productos SET nombre = ?, precio_unitario = ?, descripcion = ?, id_categoria = ?, estado = ?,imagen_producto = ? WHERE id_producto = ?";
    $statement = mysqli_prepare($connection, $query);
    if (!$statement) {
        die("Error al preparar la consulta: " . mysqli_error($connection));
    }

    // Asociar parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($statement, "ssssssi", $nombre, $precio_unitario, $descripcion, $id_categoria, $estado, $imagen_producto, $id_producto);
    $result = mysqli_stmt_execute($statement);
    if (!$result) {
        die("Error al actualizar el producto: " . mysqli_stmt_error($statement));
    }

    mysqli_stmt_close($statement);
    mysqli_close($connection);

    echo 'Producto actualizado';
?>
