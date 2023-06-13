<?php

require_once ("db.php");
require_once ("component.php");

$con = Createdb();

// create button click
if(isset($_POST['create'])){
    createData();
}

if(isset($_POST['update'])){
    UpdateData();
}

if(isset($_POST['delete'])){
    deleteRecord();
}

if(isset($_POST['deleteall'])){
    deleteAll();

}

function createData(){
    $sucursal = textboxValue("id_sucursal");

   $coordenada = textboxValue("id_coordenada");
    $ciudad = textboxValue("id_ciudad");
    $nombre = textboxValue("nombre");
    $direccion = textboxValue("direccion");
    $estado = textboxValue("estado");
    $telefono = textboxValue("telefono");
    $hora_apertura = textboxValue("hora_apertura");
    $hora_cierre = textboxValue("hora_cierre");

    if( $ciudad && $nombre && $direccion && $estado && $telefono && $hora_apertura && $hora_cierre){

        $sql = "insert into sucursales values ($sucursal,$coordenada,$ciudad, '$nombre', '$direccion', $estado, '$telefono', '$hora_apertura', '$hora_cierre');";

        if(mysqli_query($GLOBALS['con'], $sql)){
            TextNode("success", "Record Successfully Inserted...!");
        }else{
            echo "Error";
        }

    }else{
            TextNode("error", "Provide Data in the Textbox");
    }
}

function textboxValue($value){
    $textbox = mysqli_real_escape_string($GLOBALS['con'], trim($_POST[$value]));
    if(empty($textbox)){
        return false;
    }else{
        return $textbox;
    }
}


// messages
function TextNode($classname, $msg){
    $element = "<h6 class='$classname'>$msg</h6>";
    echo $element;
}


// get data from mysql database
function getData(){
    $sql = "SELECT * FROM sucursales";

    $result = mysqli_query($GLOBALS['con'], $sql);

    if(mysqli_num_rows($result) > 0){
        return $result;
    }
}

// update dat
function UpdateData(){
      $sucursal = textboxValue("id_sucursal");
    $coordenada = textboxValue("id_coordenada");
    $ciudad = textboxValue("id_ciudad");
    $nombre = textboxValue("nombre");
    $direccion = textboxValue("direccion");
    $estado = textboxValue("estado");
    $telefono = textboxValue("telefono");
    $hora_apertura = textboxValue("hora_apertura");
    $hora_cierre = textboxValue("hora_cierre");

   if($coordenada && $ciudad && $nombre && $direccion && $estado && $telefono && $hora_apertura && $hora_cierre){

        $sql = "
                    UPDATE sucursales SET id_coordenada='$coordenada', id_ciudad = '$ciudad', nombre = '$nombre', direccion = '$direccion', estado = '$estado' , telefono = '$telefono', hora_apertura = '$hora_apertura' , hora_cierre = '$hora_cierre'  WHERE id_sucursal='$sucursal';                    
        ";

        if(mysqli_query($GLOBALS['con'], $sql)){
            TextNode("success", "Data Successfully Updated");
        }else{
            TextNode("error", "Enable to Update Data");
        }

    }else{
        TextNode("error", "Select Data Using Edit Icon");
    }


}


function deleteRecord(){
    $bookid = (int)textboxValue("book_id");

    $sql = "DELETE FROM books WHERE id=$bookid";

    if(mysqli_query($GLOBALS['con'], $sql)){
        TextNode("success","Record Deleted Successfully...!");
    }else{
        TextNode("error","Enable to Delete Record...!");
    }

}


function deleteBtn(){
    $result = getData();
    $i = 0;
    if($result){
        while ($row = mysqli_fetch_assoc($result)){
            $i++;
            if($i > 3){
                buttonElement("btn-deleteall", "btn btn-danger" ,"<i class='fas fa-trash'></i> Delete All", "deleteall", "");
                return;
            }
        }
    }
}


function deleteAll(){
    $sql = "DROP TABLE books";

    if(mysqli_query($GLOBALS['con'], $sql)){
        TextNode("success","All Record deleted Successfully...!");
        Createdb();
    }else{
        TextNode("error","Something Went Wrong Record cannot deleted...!");
    }
}


// set id to textbox
function setID(){
    $getid = getData();
    $id = 0;
    if($getid){
        while ($row = mysqli_fetch_assoc($getid)){
            $id = $row['id_sucursal'];
        }
    }
    return ($id + 1);
}








