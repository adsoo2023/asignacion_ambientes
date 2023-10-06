<?php
require_once "Conexion.php";
$datos = array();
$idAmbiente = $_POST["idAmbiente"];
$res = mysqli_query($conexion, "SELECT * FROM inventario_ambiente WHERE idambiente = '$idAmbiente'");
if($res){
    $datos = mysqli_fetch_assoc($res);
}

echo json_encode($datos);
?>