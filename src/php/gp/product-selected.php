<?php
    include('../connection.php');

    $id = $_POST['id'];

    // Preparar consulta con sentencias preparadas
    $query = "SELECT * FROM productos WHERE id_producto = ?";
    $statement = mysqli_prepare($connection, $query);
    if (!$statement) {
        die("Error al preparar la consulta: " . mysqli_error($connection));
    }

    // Asociar parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($statement, "i", $id);
    $result = mysqli_stmt_execute($statement);
    if (!$result) {
        die("Error al ejecutar la consulta: " . mysqli_stmt_error($statement));
    }

    // Obtener el resultado de la consulta
    $row = mysqli_stmt_get_result($statement)->fetch_assoc();
    $json = array(
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

    mysqli_stmt_close($statement);
    mysqli_close($connection);

    $jsonString = json_encode($json);
    echo $jsonString;
?>
