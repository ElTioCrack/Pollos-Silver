<?php
// Conectar a la base de datos
$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los registros de la tabla "sucursales" con INNER JOIN en "coordenadas"
$sql = "SELECT s.id_sucursal, s.nombre, s.direccion, c.latitud, c.longitud, s.estado
        FROM sucursales AS s 
        INNER JOIN coordenadas AS c ON s.id_coordenada = c.id_coordenada";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Generar el listado HTML
    echo "<table>";
    echo "<tr><th>ID Sucursal</th><th>Nombre</th><th>Dirección</th><th>Latitud</th><th>Longitud</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr";
        if ($row["estado"] == 1) {
            echo " style='background-color: rgb(168, 255, 134);'";
        } elseif ($row["estado"] == 0) {
            echo " style='background-color: rgb(253, 159, 159);'";
        }
        echo ">";
        echo "<td>" . $row["id_sucursal"] . "</td>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["direccion"] . "</td>";
        echo "<td>" . $row["latitud"] . "</td>";
        echo "<td>" . $row["longitud"] . "</td>";
        echo "<td><button class='btnEditarSucursal' data-id='" . $row["id_sucursal"] . "'><img src='https://cdn-icons-png.flaticon.com/512/126/126794.png' alt='Editar' width='15px'></button></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron sucursales.";
}

$conn->close();
