<?php
require_once "Conexion.php";
$datos = array();
$res = mysqli_query($conexion, "SELECT * FROM usuario INNER JOIN rol_usuario ON  usuario.idrol = rol_usuario.idrol");
if($res){
    while($fila = mysqli_fetch_assoc($res)){
        if($fila["idestado"] == 1){
            $datos[] = $fila;
        }
    }
}
echo json_encode($datos);
?>