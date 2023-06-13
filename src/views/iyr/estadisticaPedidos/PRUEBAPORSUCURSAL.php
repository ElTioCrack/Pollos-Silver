<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de cantidad de clientes por cada producto</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 400px;"><canvas id="chart" ></canvas></div>
 

    <?php
    // Conexión a la base de datos
    
    $servername = "localhost";
    $username = "id20764505_adminpollos";
    $password = "Turqu3s4_2023";
    $dbname = "id20764505_pollossilver";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }

    // Consulta SQL para obtener la cantidad de empleados por sucursal
    $sql = "SELECT nombre, COUNT(*) AS cantidad_empleados FROM pedidos
INNER JOIN productos ON pedidos.id_producto = productos.id_producto
GROUP BY pedidos.id_producto";

    $result = $conn->query($sql);

    // Variables para almacenar los datos del gráfico
    $sucursales = array();
    $cantidades = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sucursales[] = $row["nombre"];
            $cantidades[] = $row["cantidad_empleados"];
        }
    }

    $conn->close();
    ?>

    <script>
        // Datos del gráfico
        var sucursales = <?php echo json_encode($sucursales); ?>;
        var cantidades = <?php echo json_encode($cantidades); ?>;

        // Crear el gráfico
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: sucursales,
                datasets: [{
                    label: 'Cantidad de preferencia de clientes',
                    data: cantidades,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
