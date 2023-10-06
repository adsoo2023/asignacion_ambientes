<?php
require_once "Conexion.php";

$idambiente = $_POST["id_ambiente"];
$estado = 0;
$enviar_datos = array();

$resul2 = mysqli_query($conexion, "SELECT * FROM asignacion INNER JOIN usuario ON  asignacion.idusuario = usuario.idusuario INNER JOIN rol_usuario ON  usuario.idrol = rol_usuario.idrol WHERE idambiente = '$idambiente'");

if ($resul2) {
    while ($fila = mysqli_fetch_assoc($resul2)) {
        $fechaI = new DateTime($fila["fecha_inicio"]);
        $fechaF = new DateTime($fila["fecha_fin"]);
        $horaFinal = $fechaF->format("H:i:s");
      
        while ($fechaI <= $fechaF) {
            $nuevaFila = $fila; // Copia la fila original
            $nuevaFila["fecha_inicio"] = $fechaI->format("Y-m-d") . "T" . $fechaI->format("H:i:s"); // Establece la fecha de inicio con el formato deseado
            $nuevaFila["fecha_fin"] = $fechaI->format("Y-m-d") . "T" . $horaFinal; // Establece la fecha de fin con el formato deseado
            
            $id_reserva = $fila["idsolicitud"];
            $resul5 = mysqli_query($conexion, "SELECT * FROM reporteactual_ambiente WHERE idreserva = '$id_reserva'");
            
            if (mysqli_num_rows($resul5) > 0) {
                while ($fila5 = mysqli_fetch_assoc($resul5)) {
                    $fechaI_reporte = new DateTime($fila5["fecha_inicio_reporte"]);
                    $fechaF_reporte = new DateTime($fila5["fecha_fin_reporte"]);
                    $horaFinal_reporte = $fechaF_reporte->format("H:i:s");
                    while ($fechaI_reporte <= $fechaF_reporte) {
                        $fecha_actual_bd_times = strtotime($fechaI_reporte->format("Y-m-d"));
                        $fecha_inicio_cada_dia = $fechaI->format("Y-m-d");
                        $fecha_inicio_stro = strtotime($fecha_inicio_cada_dia);
                        if ($fecha_inicio_stro == $fecha_actual_bd_times) {
                            $nuevaFila["estado_ambiente"] = $fila5["estado_reporte"];
                            $nuevaFila["fecha_inicio"] = $fechaI_reporte->format("Y-m-d") . "T" . $fechaI_reporte->format("H:i:s"); 
                            $nuevaFila["fecha_fin"] = $fechaI_reporte->format("Y-m-d") . "T" . $horaFinal_reporte; 
                        }
                        $fechaI_reporte->add(new DateInterval('P1D')); 
                    }
                }
            }
            
            $enviar_datos[] = $nuevaFila; // Agrega la nueva fila al resultado
            $fechaI->add(new DateInterval('P1D')); 
        }
    }
}
echo json_encode($enviar_datos);
?>
