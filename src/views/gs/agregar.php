<?php
include('../../php/connection.php');

if (isset($_POST['agregar'])) {

    //------DATOS-COORDENADA------
    $lat = $_POST['latitud'];
    $lon = $_POST['longitud'];
    //------DATOS-SUCURSALES------
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $estado = isset($_POST['estado']) ? $_POST['estado'] : 0;
    $telefono = $_POST['telefono'];
    $hora_apertura = $_POST['hora_apertura'];
    $hora_cierre = $_POST['hora_cierre'];
    //------------------------------
    $query1 = "CALL INSUP_sucursales_coordenadas(0, 0, 1, '$nombre', '$direccion', $estado, '$telefono', '$hora_apertura', '$hora_cierre', '$lat', '$lon');";
    $resultado1 = mysqli_query($connection, $query1);

    if ($resultado1) {
    } else {
        die("Fallo el query1");
    }

    mysqli_close($conn);

    $_SESSION['message'] = 'Sucursal agregada';
    $_SESSION['message_type'] = 'warning';

    //header('Location: ../sucursales/index.php');
}
?>

<!-- ADD TASK FORM -->
<div class="container-fluid p-4" style="width: 80%; margin-top: 7%;">
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card card-body">
                <h1 style="background-color: #c55b11; color: white; padding: 3px; margin: 20px; text-align: center;">Registre una nueva sucursal</h1>

                <div class="row">
                    <div class="col-md-4">
                        <form action="agregar.php" method="POST">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="form-group">
                                <label for="direccion">Dirección:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>

                            <div class="form-group">
                                <label for="estado">Estado:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1">
                                    <label class="custom-control-label" for="estado">Activo</label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{8}" required>
                            </div>

                            <div class="form-group">
                                <label for="hora_apertura">Hora de Apertura:</label>
                                <input type="time" class="form-control" id="hora_apertura" name="hora_apertura" required step="1">
                            </div>

                            <div class="form-group">
                                <label for="hora_cierre">Hora de Cierre:</label>
                                <input type="time" class="form-control" id="hora_cierre" name="hora_cierre" required step="1">
                            </div>

                            <div class="form-group">
                                <label for="latitud">Latitud:</label>
                                <input type="text" class="form-control" id="latitud" name="latitud" required readonly>
                            </div>

                            <div class="form-group">
                                <label for="longitud">Longitud:</label>
                                <input type="text" class="form-control" id="longitud" name="longitud" required readonly>
                            </div>

                            <button type="submit" class="btn btn-primary" name="agregar">Agregar</button>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <div id="map" style="height: 700px;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>