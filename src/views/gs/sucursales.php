<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes y reportes - Clientes</title>
    <link rel="stylesheet" href="../../style/css/gc/clientes.css">
    <link rel="stylesheet" href="../../style/css/iyr/tabla.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../js/gu/session_data.js"></script>
    <script>
         $("#prueba").click(function () {
                $("#main").load("../gs/CRUD/agregar.php");
            })
        $("#editar").click(function () {
                $("#main").load("../gs/CRUD/edit.php");
            })
    </script>
    <script type="text/javascript">
    
        var tipo_usuario

        window.onload = function () {
            session_data()
                .then(function (response) {
                    // usuario logueado?
                    if (response.length === 0)
                        window.location.href = "../../views/gu/login.html"
                    else
                        init(response)
                })
                .catch(function (error) { console.log(error) });
        };

        function init(response) {
            // Detecciones de tipos de usuarios
            tipo_usuario = response.usuario.tipousuario.tipo
            switch (tipo_usuario) {
                case "Jefe":
                    break;

                case "Empleado":
                    $("#btn-productos").hide();
                    $("#btn-sucursales").hide();
                    $("#btn-informes").hide();
                    break;
            }
        }
    </script>
     <style>
    #container_pollo {
      width: 100%;
      height: 67px;
      position: relative;
      overflow: hidden;
    }
 
        
    #chicken {
      width: 150px;
      height: 100px;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-image: url("../../img/logo_filtrar.png");
      background-size: cover;
      animation: run 10s linear infinite;
    }

    @keyframes run {
      0% {
        left: 0;
      }
      50% {
        left: 750px;
      }
      100% {
        left: 0;
      }
    }
  </style>
</head>

<body>
 <main id="main">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
    <div class="container-AdminCustomers">
        <div class="AC-title">
            <h2>Sucursales</h2>
        </div>
        <div class="AC-title">


<script>
  function validarFormulario() {
    var latitud = document.getElementById('latitud').value;
    var longitud = document.getElementById('longitud').value;
    
    if (latitud === "" || longitud === "") {
      Swal.fire({
        icon: 'error',
        text: 'Por favor, elija una ubicación para la sucursal que desea agregar.',
      });
      return false;
    }
    
    return true;
  }
</script>




<button class="download-button button" id="prueba" >NUEVA SUCURSAL</button>

        </div>
    


        <div class="AC-principalView">
            <div>

            </div>
            <div>
  

                <table id="mi-tabla">
                    <caption>Listado de Clientes</caption>
                   

                   
                    <thead>
                        <tr>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>nombre</th>
                            <th>direccion</th>
                            <th>telefono</th>
                            <th>hora_apertura</th>
                            <th>hora_cierre</th>
                            <th>estado</th>
                            <th>editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td><button id="editar">editar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
     </main>
</body>
<!-- ----------------------------------------------------------------------- -->


<script>
    $(document).ready(function () {
        list_customers();

        $("#searchNombre").on('input', function (event) {
            let search = $(this).val();
            if (search.length > 0) {
                const url_php = "../../php/iyr/search_customers.php";
                const postData = {
                    search: search,
                };
                $.post(url_php, postData, function (response) {
                    response = JSON.parse(response)
                    var list = "";
                    response.forEach(function (customer) {
                        list += create_row_customer(customer);
                    });
                    $("tbody").html(list);
                });
            } else {
                list_customers();
            }
        });

    });






    function get_customers() {
        let url_php = "../../php/gs/get_sucursales.php";
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: url_php,
                method: "post",
                dataType: "json",
                success: function (response) {
                    resolve(response);
                },
                error: function (error) {
                    reject(error);
                }
            });
        });
    }

    function create_row_customer(customer) {
        let customer_state = customer.estado === 1 ? `
            <option value="1" selected>Activo</option>
        ` : `
            <option value="0" selected>Inactivo</option>
        `;
        return `
        <tr>
     
        
            <td>${customer.latitud}</td>
            <td>${customer.longitud}</td>
            <td>${customer.nombre}</td>
            <td>${customer.direccion}</td>
            <td>${customer.telefono}</td>
            <td>${customer.hora_apertura}</td>
            <td>${customer.hora_cierre}</td>
            <td>${customer.estado}</td>
            <td><button>editar</button></td>
        </tr>
    `;
    }

    function list_customers() {
        let list = "";
        get_customers().then(function (response) {
            response.forEach(customer => {
                list += create_row_customer(customer);
            });

            $("tbody").html(list);

         

            if (tipo_usuario == "Empleado") {
                $("tbody select").prop('disabled', true);
                $("tbody button").hide()
            }
        }).catch(function (error) { console.log('%c%s', 'color: #fff000', JSON.stringify(error)); });
    }
</script>







<script>
    function filtrarTabla() {
      const selectElement = document.getElementById('estado-select');
      const estadoSeleccionado = selectElement.value;

      const tabla = document.getElementById('mi-tabla');
      const filas = tabla.getElementsByTagName('tr');

      for (let i = 1; i < filas.length; i++) {
        const fila = filas[i];
        const estado = fila.getElementsByTagName('td')[6].textContent;

        if (estadoSeleccionado === 'todos' || estadoSeleccionado === estado.toLowerCase()) {
          fila.style.display = '';
        } else {
          fila.style.display = 'none';
        }
      }
    }
  </script>

  

  
 
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
   <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDmR2jKUoDdMY_giAYCkDGi_dkW-WYaBTs&amp;v=3.exp&amp;sensor=true&amp;libraries=places'></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
</html>