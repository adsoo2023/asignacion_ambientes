<?php
include 'Conexion.php';

$response = array();
$registro_exitoso = true; // Variable para rastrear si todos los datos son válidos

if (isset($_POST['guardarDatosUsuario'])) {
    if ($_FILES["csvfile"]["error"] == UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES["csvfile"]["name"], PATHINFO_EXTENSION);

        if (strtolower($file_extension) === 'csv') {
            $file = $_FILES["csvfile"]["tmp_name"];
            $handle = fopen($file, "r");

            if ($handle !== FALSE) {
                $csvData = array(); // Inicializa la variable fuera del bucle
                fgetcsv($handle, 1000, ";");
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $nombre = $data[0];
                    $apellido = $data[1];
                    $documento = (int) $data[2];
                    $contraseña = $data[3];
                    $correo = $data[4];
                    $telefono = (int) $data[5];
                    $rol_nombre = $data[6];
                    $fecha_inicio_contrato = str_replace('/', '-', $data[7]);
                    $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio_contrato));
                    $fecha_fin_contrato = str_replace('/', '-', $data[8]);
                    $fecha_fin = date('Y-m-d', strtotime($fecha_fin_contrato));
                    $vinculacion = $data[9];

                    $estado = 1; // Estado predeterminado
                    $imagen = 'img/default.jpg'; // Imagen predeterminada
                    $imagen = basename($imagen);

                    // Validaciones (puedes agregar más según tus necesidades)
                    if ($nombre === '' || $apellido === '' || $documento === '' || $contraseña === '' || $correo === '' || $telefono === '' || $rol_nombre === '' || $fecha_inicio === '' || $fecha_fin === '' || $vinculacion === '') {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'Existen campos vacíos, completa todos los campos';
                        break; // Salir del bucle si hay un error
                    } elseif (count($data) != 10) {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'Número incorrecto de campos, no corresponde a la plantilla';
                        break; // Salir del bucle si hay un error
                    } elseif (in_array($data, $csvData)) {
                        $response['error'] = 'Datos ambiguos o repetidos fila: ' . implode(', ', $data);
                        $valid = false;
                        break;
                    } elseif (preg_match('/[0-9]+/', $nombre) || preg_match('/[0-9]+/', $apellido)) {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'El campo (nombre/apellido) no debe contener números';
                        break; // Salir del bucle si hay un error
                    } elseif (!preg_match('/^\d{6,10}$/', $documento)) {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'El número de documento debe ser numérico y tener entre 6 y 10 dígitos';
                        break; // Salir del bucle si hay un error
                    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $contraseña)) {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'El campo (contraseña) no debe contener caracteres especiales';
                        break; // Salir del bucle si hay un error
                    } elseif (!preg_match('/^\d{10}$/', $telefono) && !is_numeric($telefono)) {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'El campo (teléfono) debe contener 10 dígitos numéricos y no debe contener letras';
                        break; // Salir del bucle si hay un error
                    } elseif (!preg_match('/^[a-zA-Z]+$/', $rol_nombre)) {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'El campo (rol) no debe contener números o caracteres especiales';
                        break; // Salir del bucle si hay un error
                    } elseif ($fecha_inicio === '1970-01-01') {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'Fecha de inicio incorrecta en fila ' . implode(', ', $data);
                        break; // Salir del bucle si hay un error
                    } elseif ($fecha_fin === '1970-01-01') {
                        $registro_exitoso = false; // Cambiamos el estado si hay un error
                        $response['error'] = 'Fecha de fin incorrecta en fila ' . implode(', ', $data);
                        break; // Salir del bucle si hay un error
                    } elseif ($vinculacion == 'contratista' || $vinculacion == 'temporal' || $vinculacion == 'aprendizaje') {
                        if ($fecha_inicio !== date('Y-m-d', strtotime($fecha_inicio_contrato))) {
                            $registro_exitoso = false; // Cambiamos el estado si hay un error
                            $response['error'] = 'La fecha de inicio debe estar en formato Y-m-d para las vinculaciones Contratista, Temporal y Aprendizaje en fila: ' . implode(', ', $data);
                            break; // Salir del bucle si hay un error
                        } elseif ($fecha_fin !== date('Y-m-d', strtotime($fecha_fin_contrato))) {
                            $registro_exitoso = false; // Cambiamos el estado si hay un error
                            $response['error'] = 'La fecha de fin debe estar en formato Y-m-d para las vinculaciones Contratista, Temporal y Aprendizaje en fila: ' . implode(', ', $data);
                            break; // Salir del bucle si hay un error
                        }
                    } elseif ($vinculacion == 'planta') {
                        if ($fecha_inicio !== date('Y-m-d', strtotime($fecha_inicio_contrato))) {
                            $registro_exitoso = false; // Cambiamos el estado si hay un error
                            $response['error'] = 'La fecha de inicio debe estar en formato Y-m-d para la vinculación Planta en fila: ' . implode(', ', $data);
                            break; // Salir del bucle si hay un error
                        }
                    }

        

                    // Procesar fechas
                    $fecha_inicio = date('Y-m-d', strtotime(str_replace('/', '-', $fecha_inicio_contrato)));
                    $fecha_fin = date('Y-m-d', strtotime(str_replace('/', '-', $fecha_fin_contrato)));

                    // Consultar el ID del rol
                    $sql_get_rol_id = "SELECT idrol FROM rol_usuario WHERE nombre_rol = ?";
                    $stmt_get_rol_id = $conexion->prepare($sql_get_rol_id);
                    $stmt_get_rol_id->bind_param("s", $rol_nombre);
                    $stmt_get_rol_id->execute();
                    $result_rol_id = $stmt_get_rol_id->get_result();

                    if ($result_rol_id->num_rows > 0) {
                        $row_rol_id = $result_rol_id->fetch_assoc();
                        $rol = $row_rol_id['idrol'];
                    } else {
                        $response['error'] = 'Rol no encontrado: ' . $rol_nombre;
                        $registro_exitoso = false;
                        break;
                    }

                    // Consultar el ID de la vinculación laboral
                    $sql_get_vinculacion_id = "SELECT idvinculacion FROM vinculacion WHERE nombrevinculacion = ?";
                    $stmt_get_vinculacion_id = $conexion->prepare($sql_get_vinculacion_id);
                    $stmt_get_vinculacion_id->bind_param("s", $vinculacion);
                    $stmt_get_vinculacion_id->execute();
                    $result_vinculacion_id = $stmt_get_vinculacion_id->get_result();

                    if ($result_vinculacion_id->num_rows > 0) {
                        $row_vinculacion_id = $result_vinculacion_id->fetch_assoc();
                        $vinculacionid = $row_vinculacion_id['idvinculacion'];
                    } else {
                        $response['error'] = 'Vinculación laboral no encontrada: ' . $vinculacion;
                        $registro_exitoso = false;
                        break;
                    }

                    // Validar duplicados por documento y correo
                    $sql_check_duplicate = "SELECT COUNT(*) AS count FROM usuario WHERE documento = ? AND correo = ?";
                    $stmt_check_duplicate = $conexion->prepare($sql_check_duplicate);
                    $stmt_check_duplicate->bind_param("is", $documento, $correo);
                    $stmt_check_duplicate->execute();
                    $result_duplicate = $stmt_check_duplicate->get_result();
                    $row_duplicate = $result_duplicate->fetch_assoc();

                    if ($row_duplicate['count'] > 0) {
                        $response['error'] = 'Ya existen registros con el mismo correo y documento';
                        $registro_exitoso = false;
                        break;
                    }

                    // Agregar datos a $csvData
                    $csvData[] = $data;

                    // Insertar el usuario en la base de datos
                    $sql_insert_usuario = "INSERT INTO usuario (nombre_usuario, apellido, documento, password_usuario, correo, telefono, idrol,idestado,imagen, fechainiciocontrato, fechafincontrato, idvinculacion) VALUES (?, ?, ?, ?, ?, ?,?,?, ?, ?, ?, ?)";
                    $stmt_insert_usuario = $conexion->prepare($sql_insert_usuario);
                    $stmt_insert_usuario->bind_param("ssisssiissss", $nombre, $apellido, $documento, $contraseña, $correo, $telefono, $rol, $estado, $imagen, $fecha_inicio, $fecha_fin, $vinculacionid);

                    if ($stmt_insert_usuario->execute() !== TRUE) {
                        $response['error'] = 'Error al insertar el registro: ' . $stmt_insert_usuario->error;
                        $registro_exitoso = false;
                        break;
                    }
                }

                fclose($handle);

                if ($registro_exitoso) {
                    $response['success'] = 'Importación exitosa';
                }
            } else {
                $response['error'] = 'Error al abrir el archivo CSV.';
            }
        } else {
            $response['error'] = 'El archivo debe tener extensión CSV.';
        }
    } else {
        $response['error'] = 'Por favor, seleccione el archivo excel de "usuarios" antes de importar.';
    }
} else {
    $response['error'] = 'Acceso no autorizado.';
}

// Devolver la respuesta JSON con los resultados
header('Content-Type: application/json');
echo json_encode($response);
$conexion->close();
?>