<?php
//conexion a base de datos
include('php/Conexion.php');

if (isset($_POST['sesion'])) {

    date_default_timezone_set('America/Bogota');
    // Obtener la fecha actual
    $fecha_actual = date("Y-m-d");
    $fecha_actual_times = strtotime($fecha_actual);
    // Consulta SQL para obtener los registros con fecha de finalización menor o igual a la fecha actual
    $sql = "SELECT * FROM usuario";

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        // Iterar sobre los registros y actualizar el estado a "inactivo"
        while ($fila = $resultado->fetch_assoc()) {
            $fechaFinContrato = strtotime($fila["fechafincontrato"]);
            if ($fila["idvinculacion"] != '2') {
                $idd = $fila['idusuario'];
                if ($fechaFinContrato < $fecha_actual_times) {
                    $sql_actualizar = "UPDATE usuario SET idestado = 2 WHERE idusuario = $idd";
                    if ($conexion->query($sql_actualizar) === TRUE) {

                    } else {
                        echo "Error al actualizar el registro: " . $conexion->error;
                    }
                }
            }
            //    $sql_actualizar = "UPDATE usuario SET idestado = 1 WHERE idusuario = $idd";
            //    if ($conexion->query($sql_actualizar) === TRUE) {

            //    } else {
            //        echo "Error al actualizar el registro: " . $conexion->error;
            //    }
        }
    }
    // Consultar los usuarios por fecha de contrato 

    // $consulta = $conexion->query("SELECT * FROM usuario   INNER JOIN estado_usuario ON  usuario.idestado = estado_usuario.idestado");
    // if ($consulta->num_rows > 0) {
    //     while ($persona = $consulta->fetch_assoc()) {}}
    // En caso de caduque actualiza estado a los usuarios 



    //valida si los inputs estan vacios
    if (empty($_POST['id']) && empty($_POST['password'])) {
        echo "<script>
                Swal.fire(
                    {

                        icon:'warning',
                        html: '<p>INGRESE NOMBRE Y CLAVE<p>',
                        backdrop: false,
                        color:'black',
                        toast: true,
                        timer:3000,
                        background: '#ffffff',
                        padding: '1rem',
                        position:'bottom',
                        customClass: {
                            popup: 'my-popup-class',
                            icon: 'icon',
                        },
                        showConfirmButton: false,

                    }
                )
                </script>";

    } else if (!is_numeric($_POST['id'])) {
        echo "<script>
        Swal.fire(
            {
    
                icon:'warning',
                html: '<p>documento tipo texto<p>',
                backdrop: false,
                color:'black',
                toast: true,
                timer:3000,
                background: '#ffffff',
                padding: '1rem',
                position:'bottom',
                customClass: {
                    popup: 'my-popup-class',
                    icon: 'icon',
                },
                showConfirmButton: false,
    
            }
        )
        </script>";

    } else if (empty($_POST['id'])) {
        echo "<script>
                Swal.fire(
                    {

                        icon:'info',
                        html: 'INGRESE USUARIO',
                        backdrop: false,
                        toast: true,
                        timer:3000,
                        background: ' #f1f1f1',
                        padding: '1rem',
                        position:'bottom',
                        customClass: {
                            popup: 'my-popup-class',
                            icon: 'icon',
                        },
                        showConfirmButton: false,

                    }
                )
                </script>";
    } elseif (empty($_POST['password'])) {
        echo "<script>
                Swal.fire(
                    {

                        icon:'info',
                        html: 'INGRESE CLAVE',
                        backdrop: false,
                        toast: true,
                        timer:3000,
                        background: ' #f1f1f1',
                        padding: '1rem',
                        position:'bottom',
                        customClass: {
                            popup: 'my-popup-class',
                            icon: 'icon',
                        },
                        showConfirmButton: false,

                    }
                )
                </script>";


        //valida que los datos fueron enviados
    } else if (isset($_POST['id']) && isset($_POST['password'])) {
        //los datos recibidos los vuelve variables
        $id = $_POST['id'];
        $clave = $_POST['password'];

        $consulta = "SELECT * FROM usuario WHERE documento = '$id' and password_usuario = '$clave' ";
        $result = mysqli_query($conexion, $consulta, );

        if (mysqli_num_rows($result) > 0) {
            // Obtener la información del usuario de la base de datos
            $row = mysqli_fetch_assoc($result);


            if ($row['idestado'] == 1) {

                $_SESSION['nombre'] = $row['nombre_usuario'];
                $_SESSION['apellidos'] = $row['apellido'];
                $_SESSION['telefono'] = $row['telefono'];
                $_SESSION['id'] = $row['idusuario'];
                $_SESSION['rol'] = $row['idrol'];

                $_SESSION['email'] = $row['correo'];
                $_SESSION['img'] = $row['imagen'];
                echo "<script>window.location.href='./admin.php'</script>";
                //    echo "<script>window.location.href='./php/admin_vista.php'</script>";
            } else if ($row['idestado'] == 2) {
                echo "
                <p>Inactivo</p>
                <script>
                Swal.fire(
                    {
    
                        icon:'warning',
                        html: 'USUARIO INACTIVO',
                        backdrop: false,
                        toast: true,
                        timer:3000,
                        background: 'white',
                        padding: '1rem',
                        position:'bottom',
                        customClass: {
                            popup: 'my-popup-class',
                            icon: 'icon',
                        },
                        showConfirmButton: false,
    
                    }
                )
                </script>";
            }
        } else if (mysqli_num_rows($result) == 0) {
            echo "<script>
            Swal.fire(
                {

                    icon:'error',
                    html: 'USUARIO INCORRECTO',
                    backdrop: false,
                    toast: true,
                    timer:3000,
                    background: 'white',
                    padding: '1rem',
                    position:'bottom',
                    customClass: {
                        popup: 'my-popup-class',
                        icon: 'icon',
                    },
                    showConfirmButton: false,

                }
            )
            </script>";

        }


    }

}

?>