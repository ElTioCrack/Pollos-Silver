<?php
include('../connection.php');

if(isset($_POST['cliente_id']) && isset($_POST['nuevo_estado'])) {
    $cliente_id = $_POST['cliente_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    // Verificar si el nuevo estado es válido (1 o 0)
    if($nuevo_estado == 0 || $nuevo_estado == 1) {
        // Actualizar el estado del cliente en la base de datos
        $sql = "UPDATE clientes SET estado = $nuevo_estado WHERE ci = '$cliente_id'";
        $resultado = mysqli_query($connection, $sql);

        if($resultado) {
            echo 'El estado del cliente se ha actualizado correctamente';

        } else {
            echo "Hubo un error al actualizar el estado del cliente: " . mysqli_error($connection);
        }
    } else {
            echo 'El nuevo estado no es valido"';
    }
}
?>
