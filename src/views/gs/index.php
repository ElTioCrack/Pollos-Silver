<?php include("../../php/connection.php"); ?>

<style>
  .table-container {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    padding: 8px;
    border: 1px solid #ddd;
  }

  th {
    background-color: #c55b11;
    color: white;
    text-align: left;
  }

  @media screen and (max-width: 900px) {
    table {
      margin-left: 10%;
      display: block;
      overflow-x: auto;
      white-space: nowrap;
    }

    th,
    td {
      min-width: 150px;
    }
  }
</style>
<main class="container-fluid p-4">
  <div class="row">
    <div class="col-md-12">
      <!-- MESSAGES -->
      <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
          <?= $_SESSION['message'] ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php session_unset();
      } ?>
    </div>
  </div>
  <center>
    <div class="row" style="width: 90%; margin-top: 6%;">
      <div class="col-md-12">
        <h1 style="background-color: #c55b11; color: white; padding: 3px; margin: 20px; text-align: center;">Lista de sucursales</h1>
        <a href="../gs/agregar.php" Target="_blank" class="btn btn-primary" style="margin-bottom: 10px;">
          <i class="fas fa-plus"></i> Agregar
        </a>
        <table class="table table-bordered table-container">
          <thead>
            <tr>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Ciudad</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Nombre</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Descripcion</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Telefono</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Apertura</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Cierre</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Latitud</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Longitud</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col">Estado</th>
              <th style="text-align: center; font-size: 15px; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT * FROM sucursales as S LEFT JOIN coordenadas as C ON (S.id_coordenada = C.id_coordenada) LEFT JOIN ciudad as CI ON (S.id_ciudad=CI.id_ciudad) ";
            $result_tasks = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($result_tasks)) { ?>
              <tr class="background-color: rgba(255, 0, 0, 0.5);">
                <td><?php echo $row['ciudad']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['direccion']; ?></td>
                <td><?php echo $row['telefono']; ?></td>
                <td><?php echo $row['hora_apertura']; ?></td>
                <td><?php echo $row['hora_cierre']; ?></td>
                <td><?php echo $row['latitud']; ?></td>
                <td><?php echo $row['longitud']; ?></td>
                <td><?php echo $row['estado']; ?></td>
                <td>
                  <a href="edit.php?idsucur=<?php echo $row['id_sucursal'] ?>&idcoor=<?php echo $row['id_coordenada'] ?>" class="btn btn-secondary" Target="_blank">
                    <i class="fas fa-marker"></i>
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </center>
</main>