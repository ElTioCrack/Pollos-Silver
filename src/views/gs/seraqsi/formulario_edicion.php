<?php
// Obtener el ID de la sucursal enviado por GET
$idSucursal = $_GET["id_sucursal"];

// Aquí puedes realizar consultas adicionales para obtener los datos de la sucursal en base al ID

// Ejemplo: Obtener los datos de la sucursal de la base de datos
$servername = "localhost";
$username = "id20764505_adminpollos";
$password = "Turqu3s4_2023";
$dbname = "id20764505_pollossilver";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM sucursales WHERE id_sucursal = $idSucursal";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row["nombre"];
    $direccion = $row["direccion"];
    $estado = $row["estado"];
    $telefono = $row["telefono"];
    $horaApertura = $row["hora_apertura"];
    $horaCierre= $row["hora_cierre"];
    // Obtener los demás campos de la sucursal según sea necesario
} else {
    echo "No se encontró la sucursal.";
    exit;
}

$conn->close();
?>
<script>
 $("#btnCancelarEdicion").click(function() {
                $("#modalEditarSucursal").hide();
            });
</script>

<div class="modal-content" id="mod">
<!-- Formulario de edición -->
<form action="../gs/seraqsi/guardar_edicion.php" method="POST" style="max-width: 400px; margin: 0 auto;">
  <input type="hidden" name="id_sucursal" value="<?php echo $idSucursal; ?>">

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="nombre" style="margin-bottom: 5px;">Nombre:</label>
    <input type="text" name="nombre" value="<?php echo $nombre; ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="direccion" style="margin-bottom: 5px;">Dirección:</label>
    <input type="text" name="direccion" value="<?php echo $direccion; ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="estado" style="margin-bottom: 5px;">Estado:</label>
   <select name="estado" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    <option value="1" <?php if ($estado == 1) echo "selected"; ?>>Activo</option>
    <option value="0" <?php if ($estado == 0) echo "selected"; ?>>Inactivo</option>
</select>
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="telefono" style="margin-bottom: 5px;">Teléfono:</label>
    <input type="number" name="telefono" value="<?php echo $telefono; ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="hora_apertura" style="margin-bottom: 5px;">Hora Apertura:</label>
    <input type="time" name="hora_apertura" value="<?php echo $horaApertura; ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="hora_cierre" style="margin-bottom: 5px;">Hora Cierre:</label>
    <input type="time" name="hora_cierre" value="<?php echo $horaCierre; ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
  </div>

  <!-- Agrega los demás campos del formulario de edición -->

  <div style="display: flex; justify-content: space-between; margin-top: 20px;">
    <button type="submit" style="padding: 8px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Guardar</button>
    <button type="button" id="btnCancelarEdicion" style="padding: 8px 20px; background-color: #ccc; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Cancelar</button>
  </div>
</form>

</div>