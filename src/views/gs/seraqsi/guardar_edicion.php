<?php
// Obtener los datos del formulario de edición
$idSucursal = $_POST["id_sucursal"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$estado = $_POST["estado"];
$telefono = $_POST["telefono"];
$horaApertura = $_POST["hora_apertura"];
$horaCierre= $_POST["hora_cierre"];
// Obtén los demás campos del formulario de edición según sea necesario

// Conectar a la base de datos
$servername = "localhost";
$username = "id20764505_adminpollos";
$password = "Turqu3s4_2023";
$dbname = "id20764505_pollossilver";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Actualizar los datos de la sucursal en la base de datos
$sql = "UPDATE sucursales SET nombre = '$nombre', direccion = '$direccion', estado= '$estado', telefono= '$telefono', hora_apertura= '$horaApertura', hora_cierre= '$horaCierre' WHERE id_sucursal = $idSucursal";
if ($conn->query($sql) === TRUE) {
   echo "<script>alert('Sucursal actualizada exitosamente');</script>";
} else {
    echo "<script>alert('Error al actualizar la sucursal: " . $conn->error . "');</script>";
}

$conn->close();
?>
