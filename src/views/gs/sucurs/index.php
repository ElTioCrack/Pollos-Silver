<?php

require_once ("./php/component.php");
require_once ("./php/operation.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sucursales</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="../gs/sucurs/style.css">

</head>
<body>

<main>
    <div class="container text-center">
        <h1 class="py-4 bg-dark text-light rounded"><i class="fas fa-swatchbook"></i> Sucursales</h1>

        <div class="d-flex justify-content-center">
            <form action="" method="post" class="w-50">
                <div class="pt-2">
                    <?php inputElement("<i class='fas fa-id-badge'></i>","ID", "id_sucursal",setID()); ?>
                </div>
                <div class="pt-2">
                    <?php inputElement("<i class='fas fa-book'></i>","Coordenada", "id_coordenada",""); ?>
                </div>
                <div class="pt-2">
                    <?php inputElement("<i class='fas fa-book'></i>","Ciudad", "id_ciudad",""); ?>
                </div>
                <div class="row pt-2">
                    <div class="col">
                        <?php inputElement("<i class='fas fa-people-carry'></i>","Nombre", "nombre",""); ?>
                    </div>
                    <div class="col">
                        <?php inputElement("<i class='fas fa-dollar-sign'></i>","direccion", "direccion",""); ?>
                    </div>
                </div>
                      <div class="row pt-2">
                    <div class="col">
                        <?php inputElement("<i class='fas fa-people-carry'></i>","estado", "estado",""); ?>
                    </div>
                    <div class="col">
                        <?php inputElement("<i class='fas fa-dollar-sign'></i>","telefono", "telefono",""); ?>
                    </div>
                </div>
                <div class="pt-2">
                    <?php inputElement("<i class='fas fa-book'></i>","apertura", "hora_apertura",""); ?>
                </div>
                <div class="pt-2">
                    <?php inputElement("<i class='fas fa-book'></i>","cierre", "hora_cierre",""); ?>
                </div>
                
                
                
                <div class="d-flex justify-content-center">
                        <?php buttonElement("btn-create","btn btn-success","<i class='fas fa-plus'></i>","create","data-toggle='tooltip' data-placement='bottom' title='Create'"); ?>
                        <?php buttonElement("btn-read","btn btn-primary","<i class='fas fa-sync'></i>","read","data-toggle='tooltip' data-placement='bottom' title='Read'"); ?>
                        <?php buttonElement("btn-update","btn btn-light border","<i class='fas fa-pen-alt'></i>","update","data-toggle='tooltip' data-placement='bottom' title='Update'"); ?>
                        
                        
        
                </div>
            </form>
        </div>

        <!-- Bootstrap table  -->
        <div class="d-flex table-data">
            <table class="table table-striped table-dark">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Coordenada</th>
                        <th>ciudad</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Estado</th>
                        <th>Telefono</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                   <?php



                       $result = getData();

                       if($result){

                           while ($row = mysqli_fetch_assoc($result)){ ?>

                               <tr>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['id_sucursal']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['id_coordenada']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['id_ciudad']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['nombre']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['direccion']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['estado']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['telefono']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['hora_apertura']; ?></td>
                                   <td data-id="<?php echo $row['id_sucursal']; ?>"><?php echo $row['hora_cierre']; ?></td>
                
                                   <td ><i class="fas fa-edit btnedit" data-id="<?php echo $row['id_sucursal']; ?>"></i></td>
                               </tr>

                   <?php
                           }

                       }
                   


                   ?>
                </tbody>
            </table>
        </div>


    </div>
</main>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="../gs/sucurs/php/main.js"></script>
</body>
</html>