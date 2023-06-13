<?php
// Obtener los datos del formulario
$id_sucursal = $_POST['id_sucursal'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$id_ciudad = $_POST['id_ciudad'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$estado = $_POST['estado'];
$telefono = $_POST['telefono'];
$hora_apertura = $_POST['hora_apertura'];
$hora_cierre = $_POST['hora_cierre'];

// Conectar a la base de datos
$servername = "localhost";
$username = "id20764505_adminpollos";
$password = "Turqu3s4_2023";
$dbname = "id20764505_pollossilver";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Insertar la coordenada en la tabla "coordenadas"
$sqlCoordenada = "INSERT INTO coordenadas (latitud, longitud, fecha_hora) VALUES ('$latitud', '$longitud', NOW())";
if ($conn->query($sqlCoordenada) === TRUE) {
    // Obtener el ID de la coordenada insertada
    $id_coordenada = $conn->insert_id;

    // Insertar la sucursal en la tabla "sucursales"
    $sqlSucursal = "INSERT INTO sucursales (id_sucursal, id_coordenada, id_ciudad, nombre, direccion, estado, telefono, hora_apertura, hora_cierre) VALUES ('$id_sucursal', '$id_coordenada', '$id_ciudad', '$nombre', '$direccion', '$estado', '$telefono', '$hora_apertura', '$hora_cierre')";
    if ($conn->query($sqlSucursal) === TRUE) {
        echo "Sucursal creada exitosamente";
    } else {
        echo "Error al insertar la sucursal: " . $conn->error;
    }
} else {
    echo "Error al insertar la coordenada: " . $conn->error;
}

$conn->close();
?>
