<?php
    if($_SERVER['REQUEST_METHOD']=="GET"){
        include('../connection.php');
        $query="CALL LIST_sucursales();";
        $resultado = $connection->query($query);
        if($connection->affected_rows>0){
            $json= "[";
            while($row=$resultado->fetch_assoc()){
                $json=$json.json_encode($row);
                $json=$json.",";
            }
            $json=substr(trim($json),0,-1);
            $json=$json."]";
        }
        echo $json;
        $resultado->close();
        $connection->close();

    }
    exit();
?>