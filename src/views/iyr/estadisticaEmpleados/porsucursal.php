<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Empleados por Sucursal</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    body {
        background-color: #f5ebdc;
    }
</style>
<body>
    <center>
        <h1>Cantidad de empleados por sucursal</h1>
        <div style="width: 40%;"><canvas id="chart"></canvas></div>
    </center>

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

    // Consulta SQL para obtener las sucursales y la cantidad de empleados por sucursal
    $sql = "SELECT sucursales.direccion, COUNT(*) AS cantidad_empleados 
            FROM empleados 
            INNER JOIN sucursales ON empleados.id_sucursal = sucursales.id_sucursal 
            GROUP BY sucursales.id_sucursal";

    $result = $conn->query($sql);

    // Variables para almacenar los datos del gráfico
    $sucursales = array();
    $cantidades = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sucursales[] = $row["direccion"];
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
            type: 'pie', // Cambio de tipo de gráfico a pie
            data: {
                labels: sucursales,
                datasets: [{
                    label: 'Cantidad de Empleados',
                    data: cantidades,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                    ],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
