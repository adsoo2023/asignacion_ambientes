<?php
require_once "Conexion.php";

date_default_timezone_set('America/Bogota');
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:s");
$horaI = null;
$horaf = null;
$id_reserva = isset($_POST["id_reserva"]) ? intval($_POST["id_reserva"]) : 0;
$fechaI = isset($_POST["datos"]["form-da-fechai-reser-dis"]) ? $_POST["datos"]["form-da-fechai-reser-dis"] : "";
$fechaf = isset($_POST["datos"]["form-da-fechaf-reser-dis"]) ? $_POST["datos"]["form-da-fechaf-reser-dis"] : "";
$motivo = isset($_POST["datos"]["form-da-motivo-reser-dis"]) ? $_POST["datos"]["form-da-motivo-reser-dis"] : "";
$estado_fecha = null;
$estado_hora = null;
$id_usuario = isset($_POST["id_usu_ingresado"]) ? intval($_POST["id_usu_ingresado"]) : 0;
$concuerdan = false;
$horaF_times = null;
$horaI_times = null;
$fechaI_times = strtotime($fechaI);
$fechaF_times = strtotime($fechaf);

$res = mysqli_query($conexion, "SELECT * FROM asignacion WHERE idsolicitud = '$id_reserva'");

if ($res->num_rows > 0) {
    $fila = mysqli_fetch_assoc($res);
    $fecha_inicio = $fila["fecha_inicio"];
    $fecha_fin = $fila["fecha_fin"];

    $parts_inicio = explode(" ", $fecha_inicio);
    $parts_fin = explode(" ", $fecha_fin);

    $fecha_inici = $parts_inicio[0];
    $hora_inicio = $parts_inicio[1];

    $fecha_fi = $parts_fin[0];
    $hora_fin = $parts_fin[1];

    $fecha_inicio_times = strtotime($fecha_inici);
    $fecha_fin_timestamp = strtotime($fecha_fi);
    $hora_inicio_timestamp = strtotime($hora_inicio);
    $hora_fin_timestamp = strtotime($hora_fin);
    $estado_ambiente = $fila["estado_ambiente"];


    if ($fechaI_times >= $fecha_inicio_times && $fecha_fin_timestamp >= $fechaF_times) {
        if (isset($_POST["datos"]["form-da-jornada-reser-dis"])) {
            $horaI = $hora_inicio;
            $horaf = $hora_fin;
        } else {
            $horaI = isset($_POST["datos"]["form-da-horai-reser-dis"]) ? $_POST["datos"]["form-da-horai-reser-dis"] : "";
            $horaf = isset($_POST["datos"]["form-da-horaf-reser-dis"]) ? $_POST["datos"]["form-da-horaf-reser-dis"] : "";
        }
    } else {
        if (isset($_POST["datos"]["form-da-jornada-reser-dis"])) {
            $horaI = $hora_inicio;
            $horaf = $hora_fin;
        } else {
            $horaI = isset($_POST["datos"]["form-da-horai-reser-dis"]) ? $_POST["datos"]["form-da-horai-reser-dis"] : "";
            $horaf = isset($_POST["datos"]["form-da-horaf-reser-dis"]) ? $_POST["datos"]["form-da-horaf-reser-dis"] : "";
            $horaI_times = strtotime($horaI);
            $horaF_times = strtotime($horaf);
            if ($horaI_times >= $hora_inicio_timestamp && $hora_fin_timestamp >= $horaF_times) {
                $estado_hora = "bien-_hora";
            } else {
                $estado_hora = "fuera_reser";
            }
        }
        $estados = [
            "res" => false,
            "estado_fecha" => "fuera_reser",
            "estado_hora" => $estado_hora
        ];


        echo json_encode($estados);
        exit();
    }
}

$fechaI_times = strtotime($fechaI);
$fechaF_times = strtotime($fechaf);
$fecha_actual_timestamp = strtotime($fecha_actual);
$hora_actual_timestamp = strtotime($hora_actual);

if ($fechaF_times < $fechaI_times) {
    $estado_fecha = "nocon";
    $concuerdan = true;
}

if ($horaF_times < $horaI_times) {
    $estado_hora = "nocon";
    $concuerdan = true;
}

if ($concuerdan) {
    $estados = [
        "estado_fecha" => $estado_fecha,
        "estado_hora" => $estado_hora,
        "res" => false
    ];

    echo json_encode($estados);
    exit();
}


$estado = 3;
$fechainicio_com = $fechaI . " " . $horaI;
$fechafin_com = $fechaf . " " . $horaf;

$sql = "INSERT INTO reporteactual_ambiente (idreserva, fecha_inicio_reporte, fecha_fin_reporte, estado_reporte, idusuario, motivo, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("issiiss", $id_reserva, $fechainicio_com, $fechafin_com, $estado, $id_usuario, $motivo, $motivo);


if ($stmt->execute()) {
    $estados = [
        "res" => true
    ];

    echo json_encode($estados);
}

$stmt->close();
