<?php
include('../connection.php');
$title = '';
$description = '';

//-----DATOS-COORDENADA------
$lat = '';
$lon = '';
//-----DATOS-SUCURSALES------
$nombre = '';
$direccion = '';
$estado = 0;
$telefono = '';
$hora_apertura = '';
$hora_cierre = '';

if (isset($_GET['idsucur']) && isset($_GET['idcoor'])) {
    $idsucur = $_GET['idsucur'];
    $idcoor = $_GET['idcoor'];

    $query = "SELECT * FROM sucursales as S LEFT JOIN coordenadas as C ON (S.id_coordenada = C.id_coordenada) LEFT JOIN ciudad as CI ON (S.id_ciudad=CI.id_ciudad) WHERE id_sucursal=$idsucur";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);

        $nombre = $row['nombre'];
        $direccion = $row['direccion'];

        $estado = $row['estado'];
        $estadoActivo = ($estado == 1) ? 'checked' : '';
        $estadoInactivo = ($estado == 0) ? 'checked' : '';

        $telefono = $row['telefono'];
        $hora_apertura = $row['hora_apertura'];
        $hora_cierre = $row['hora_cierre'];

        $lat = $row['latitud'];
        $lon = $row['longitud'];
    }
}

if (isset($_POST['modificar'])) {
    $idsucur = $_GET['idsucur'];
    $idcoor = $_GET['idcoor'];

    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];

    $estado = isset($_POST['estado']) ? $_POST['estado'] : 0;
    $telefono = $_POST['telefono'];
    $hora_apertura = $_POST['hora_apertura'];
    $hora_cierre = $_POST['hora_cierre'];

    $lat = $_POST['latitud'];
    $lon = $_POST['longitud'];

    $query = "CALL INSUP_sucursales_coordenadas($idsucur, $idcoor, 1, '$nombre', '$direccion', $estado, '$telefono', '$hora_apertura', '$hora_cierre', '$lat', '$lon');";
    mysqli_query($conn, $query);

    $_SESSION['message'] = 'Sucursal modificado';
    $_SESSION['message_type'] = 'warning';
    //header('Location: index.php');
}

?>

<?php include('../HeaderFotter/header2.php'); ?>


<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card card-body">
                <h1 style="background-color: #c55b11; color: white; padding: 3px; margin: 20px; text-align: center;">Modificar sucursal</h1>

                <div class="row">
                    <div class="col-md-4">
                        <form action="edit.php?idsucur=<?php echo $_GET['idsucur']; ?>&idcoor=<?php echo $_GET['idcoor']; ?>" method="POST">

                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="direccion">Dirección:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="estado">Estado:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="estadoActivo" name="estado" value="1" <?php echo ($estado == 1) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="estadoActivo">Activo</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{8}" value="<?php echo $telefono; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="hora_apertura">Hora de Apertura:</label>
                                <input type="time" class="form-control" id="hora_apertura" name="hora_apertura" value="<?php echo $hora_apertura; ?>" required step="1">
                            </div>

                            <div class="form-group">
                                <label for="hora_cierre">Hora de Cierre:</label>
                                <input type="time" class="form-control" id="hora_cierre" name="hora_cierre" value="<?php echo $hora_cierre; ?>" required step="1">
                            </div>

                            <div class="form-group">
                                <label for="latitud">Latitud:</label>
                                <input type="text" class="form-control" id="latitud" name="latitud" value="<?php echo $lat; ?>" required readonly>
                            </div>

                            <div class="form-group">
                                <label for="longitud">Longitud:</label>
                                <input type="text" class="form-control" id="longitud" name="longitud" value="<?php echo $lon; ?>" required readonly>
                            </div>


                            <button type="submit" class="btn btn-primary" name="modificar">Modificar</button>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <div id="map" style="height: 700px;"></div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>