<?php
// Conexion a base de datos
$conexion = mysqli_connect("localhost", "root", "", "asignacionv1.1.1");


// $conexion = mysqli_connect("localhost", "c1601882_asignar", "keGOtude02", "c1601882_asignar");

if(!$conexion){
  die("algo salio mal". mysqli_connect_error());
}
?>