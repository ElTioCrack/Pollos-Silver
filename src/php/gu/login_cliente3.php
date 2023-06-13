<?php
include('../connection.php');

$response = [];

if (isset($_POST["ci"], $_POST["password"])) {
    $ci = $_POST["ci"];
    $password = $_POST["password"];

    //para prevenir inyecciones SQL
    if (!is_numeric($ci)) die($response);
    $password = mysqli_real_escape_string($connection, $password);

    // Consulta preparada para proteger contra inyecciones SQL
    $query = "SELECT 
                usuarios.ci, usuarios.contrasena,
                tipousuario.id_tipousuario, tipousuario.tipo as tipo_tipousuario,
                clientes.nombres, clientes.apellidos, clientes.telefono, clientes.descripcion_direccion, clientes.fecha_hora 
                FROM usuarios 
                JOIN tipousuario ON usuarios.id_tipousuario = tipousuario.id_tipousuario 
                LEFT JOIN clientes ON clientes.ci = usuarios.ci 
                WHERE usuarios.ci = ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $ci);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result && $row = mysqli_fetch_assoc($result)) {
        $response = $row;
    } else {
        if ($result && mysqli_num_rows($result) === 0) {
            die(json_encode($response));
        } else {
            die("Query failed: " . mysqli_error($connection));
        }
    }

    $response = array(
        "usuario" => array(
            "ci" => $row["ci"],
            "contrasena" => $row["contrasena"],
            "tipousuario" => array(
                "id_tipousuario" => $row["id_tipousuario"],
                "tipo_tipousuario" => $row["tipo_tipousuario"],
            ),
        ),
        "nombres" => $row["nombres"],
        "apellidos" => $row["apellidos"],
        "telefono" => $row["telefono"],
        "descripcion_direccion" => $row["descripcion_direccion"],
        "fecha_hora" => $row["fecha_hora"],
    );

    // Cerrar la conexión y enviar la respuesta JSON
    mysqli_stmt_close($stmt);
}

echo json_encode($response);

mysqli_close($connection);
?>
