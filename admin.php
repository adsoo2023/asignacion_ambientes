<?php

session_start();
#Validacion de rol
include('php/Conexion.php');
$rol = $_SESSION['rol'];


if (!$_SESSION['rol']) {
  echo "<script>alert('No has iniciado sesión');
  window.location.href = './index.php';</script>";

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/admin.css" />
  <link rel="stylesheet" href="css/slider.css" />
  <link rel="stylesheet" href="css/ventana.css">
  <script src="https://kit.fontawesome.com/0015840e45.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- alertas toast -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <?php

  if ($rol == 2 or $rol == 3 or $rol == 4) {
    echo " <style> 
    .cambiar-num-inve{
      display:none;
    }

    .acciones-inve-da{
      display:none;
    }
    </style>";
  }
  ?>


  </link>

  <title>Panel administrador</title>
</head>

<body>
  <div class="" id="carga">
    <div class="custom-loader"></div>
  </div>

  <div id="menuResponsive">
    <h2><img src="img/sena.png" alt="" class="sena"> Asignación ambiente</h2>
    <div id="iconoMenu">
      <div id="desplegarMenu" class="">
        <i class="fa-solid fa-bars"></i>
      </div>

      <div id="cerrarMenu" class="">
        <i class="fa-solid fa-x"></i>
      </div>

    </div>
  </div>


  <!-- Ventana editar perfil -->
      <!-- EL PROPIO PROGRAMACONSUEÑOINADOR -->
      <?php
    if (isset($_SESSION['id'])) {
      $id_usuario = $_SESSION['id'];

      $sql = "SELECT * FROM usuario WHERE idusuario = ?";
      $stmt = $conexion->prepare($sql);
      $stmt->bind_param("i", $id_usuario);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nombre_usuario = $row["nombre_usuario"];
        $apellido = $row["apellido"];
        $documento = $row["documento"];
        $password_usuario = $row["password_usuario"];
        $correo = $row["correo"];
        $telefono = $row["telefono"];
        $imagen = $row["imagen"];
      }
    }
    ?>

    <div id="modal" class="modal">
      <div class="content-form">

        <form class="formularo-edit" action="php/actualizar_ints_ap.php" method="POST" enctype="multipart/form-data">
          <span class="closeProfile" onclick="ocultarUserCard()">&times;</span>
          <h1 class="card-title">Editar Perfil</h1>
          <div class="campos-data">
            <div class="label-input">
              <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
              <label for="nombre">Nombre:</label>
              <input type="text" name="nombre" id="nombre" class="dato" value="<?php echo $nombre_usuario; ?>">
            </div>
            <div class="label-input">
              <label for="apellido">Apellido:</label>
              <input type="text" name="apellido" id="apellido" class="dato" value="<?php echo $apellido; ?>">
            </div>

          </div>
          <div class="campos-data">
            <div class="label-input">
              <label for="documento">Documento:</label>
              <input type="text" name="documento" id="documento" class="dato" value="<?php echo $documento; ?>">
            </div>
            <div class="label-input">
              <label for="password">Contraseña:</label>
              <input type="password" name="password" id="password" class="dato"
                value="<?php echo $password_usuario; ?>">
            </div>

          </div>
          <div class="campos-data">
            <div class="label-input">
              <label for="correo">Correo:</label>

              <input type="text" name="correo" id="correo" class="dato" value="<?php echo $correo; ?>">
            </div>
            <div class="label-input"><label for="telefono">Teléfono:</label>
              <input type="text" name="telefono" id="telefono" class="dato" value="<?php echo $telefono; ?>">
            </div>
          </div>
          <div class="imgaa">
            <label for="imagen">Imagen Actual:</label>
            <img src="imgusuario/<?php echo $imagen; ?>" alt="Imagen Actual" class="imga"
              ondblclick="mostrarImagenAmpliada(this)" onclick="cerrarImagen(this)">
          </div>
          <div class="imgaa">
            <label for="new-imagen" class="btn-img">Subir nueva imagen</label>
            <input type="file" name="imagen" id="new-imagen" class="filenew">
          </div>
          <button type="submit" class="btn-cargar-novedad">Actualizar</button>
        </form>
      </div>
    </div>


  <aside id="aside">
    <section class="logo">


      <img src="imgusuario/<?php echo $_SESSION['img']; ?>" alt="" />

      <span class="name_rol">

        <?php

        $sconsult = mysqli_query($conexion, "SELECT * FROM rol_usuario WHERE idrol = $rol");
        $row = mysqli_fetch_assoc($sconsult);
        echo '<p>';
        echo $_SESSION['nombre'];
        echo '</p>';
        echo '<p>';
        echo $row['nombre_rol'];
        echo '</p>';
        ?>
      </span>

      <article class="BtnEditarPerfil" onclick="mostrarUserCard()" title='Editar perfil'>
      <i class="fa-solid fa-pen-to-square"></i>
      </article>
      <?php
      if ($rol == 1) {
        echo "<article class='BtnCargueAsignacion' onclick='mostrarCargueAsignacion()' title='Cargar asignaciones'>
        <i class='fa-solid fa-upload'></i>
      </article>
      
         <button class='Btninforme' id='abrirModalInforme' title='Generar informe'>
         <i class='fa-solid fa-file-export'></i>
        </button> ";

      }


      ?>
    </section>



    <section id="modalCargueAsignaciones" class="ventana">
      <section class="product">
        <div class="cerrarbutton">
          
          <a href="descargarPlantillaAsignacion.pdf" download="php/descargarPlantillaAsignacion.php"><button
              class="btnexcel">Descargar guia importe de asignaciones</button>
          </a>
          <h6 class="titleExcel">Cargar Asignaciones con excel</h6>
          <span class="cerrar" onclick="cerrarCargueAsignacion()">&times;</span>
        </div>

        <div id="cargarDatos">
          <div class="formsExcel">
            <a href="php/descargarPlantillaAsignacion.php"><button name="asignacion" class="btnexcel">Descarga tu
                plantilla</button>
            </a>
          </div>
          <div class="formsExcel">
            <form action="" method="post" enctype="multipart/form-data" class="formsExcel" id="cargarExcelAsignacion">
              <input type="file" name="csvfile" id="datosAsignacion">
              <button type="submit" name="guardarDatosAsignacion" class="btnexcelI">Importar datos</button>
            </form>
          </div>
        </div>
      </section>
    </section>

    <!-- Anuncios sena mi papa -->
    <div id="anuncios">

      <?php
      if ($rol == 1) {
        echo '
  <button title="Gestionar noticias" id="EditarCarrusel" onclick="mostrarContenedor(\'carruselNoticias\', this)">
    <p></p>
    <i class="fa-solid fa-ellipsis"></i>
  </button>
  ';
      }
      ?>

      <div class="swiper mySwiper">
        <div class="swiper-wrapper">

        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
        <div class="autoplay-progress">
          <svg viewBox="0 0 48 48">
            <circle cx="24" cy="24" r="20"></circle>
          </svg>
          <span></span>
        </div>
      </div>

      <p id="textoAnuncio"></p>

    </div>


    <nav id="contenedor-botones">
      <!-- DE AQUI HACIA ABAJO MEJIA -->
      <?php
      if ($rol == 2) {
        ?>
        <button onclick="window.location.href='crearSolicitud.php' " class="botones">
          <i class="fa-solid fa-plus"></i>
          <p>Crear solicitud</p>
        </button>
        <?php
        echo "
      <button class='botones' id='2' onclick='mostrarContenedor(\"crearNovedad\", this)'>
        <i class='fas fa-clipboard-list'></i>
        <p>Novedades</p>
      </button>
    ";

        // ME PUSIERON ESTO A ULTIMA HORA MALDITOS DESGRACIADOS (CON TODO RESPETO)
        echo "
    <button class='botones' id='2' onclick='mostrarContenedor(\"verSolicitudes\", this)'>
      <i class='fas fa-clipboard-list'></i>
      <p>Solicitudes</p>
    </button>

  ";
      }
      ?>

      <!-- DE AQUI HACIA ARRIBA MEJIA  -->
      <button class="botones pri" id="1" onclick="mostrarContenedor('Ambientes',this)">
        <i class="fas fa-building pri"></i>
        <p class="pri">Ambientes</p>
      </button>
      <?php
      if ($rol == 3) {
        echo '<button class="botones pri btn-open" onclick="mostrarFormularioN()">
        <i class="fas fa-building pri"></i>
        <p class="pri  ">Generar Novedad</p>
      </button>';
      }
      ?>
      <?php
      if ($rol == 1) {
        echo "
      <button class='botones' id='2' onclick='mostrarContenedor(\"crearAmbiente\", this)'>
        <i class='fas fa-plus-circle'></i>
        <p>Crear Ambiente</p>

      </button>
      
   
     
      <button class='botones' id='3' onclick='mostrarContenedor(\"crearUsuario\", this)'>
        <i class='fas fa-plus-circle'></i>
        <p>Crear Usuario</p>
      </button>
    ";
      }
      ?>


      <?php
      if ($rol == 1) {
        ?>
      <button class='botones' id='4' onclick='mostrarContenedor("Solicitudes",this)'>
        <?php require_once 'php/num_solicutudes_nueva.php'; ?>
        <i class='fas fa-clipboard-list'></i>
        <p>Solicitudes</p>
      </button>
     
     <button class='botones' id='5' onclick='mostrarContenedor("Usuarios",this)'>
        <i class='fas fa-users'></i>
      
        <p>Usuarios</p>
      </button>
        <?php
      }
      ?>

      <button class="botones" id="6" onclick="cerrarSesion()">
        <i class="fas fa-sign-out-alt"></i>
        <p>Cerrar sesión</p>
      </button>
    </nav>
  </aside>

  <main id="main">
    <section class="pages" id="Inicio">
      <h1>Inicio</h1>
    </section>

    <section class="pages" id="Solicitudes">
      <!-- <section class="filtros"></section> -->
      <section class="soli">
        <section class="filtros-total">
          <div>
            <h3>Solicitudes por fecha</h3>
            <input type="date" id="fecha">
          </div>
          <div>
            <h3>Jornadas</h3>
            <select id="jornadas">
              <option value="#">Selecciona una opcion</option>
              <?php
              $jorna = mysqli_query($conexion, "SELECT * FROM  jornadas");
              if ($jorna) {
                while ($g = mysqli_fetch_assoc($jorna)) {
                  echo '<option value="' . $g["id_jornada"] . '">' . $g["jornada"] . '</option>';
                }
              }
              ?>
            </select>
          </div>
          <div>
            <h3>Pisos</h3>
            <select id="pisos">
              <option value="#">Selecciona un piso</option>
              <option value="1">Piso 1</option>
              <option value="2">Piso 2</option>
              <option value="3">Piso 3</option>
            </select>
          </div>
          <input type="hidden" id="valorNulo" value="1">
          <button id="limpiarFiltros">Limpiar Filtros</button>
        </section>
        <section class="scroll3">
          <div class="d" id="cases">
            <!-- <section id="button" class="buxo">Solicitudes sin responder</section> -->
            <?php
            require_once "php/solicitudes-Ambientes.php";
            ?>
          </div>
        </section>
        <section class="cetaba-cares" id="cetaba-cares"></section>

      </section>
      <div id="overlay" class="overlay">
        <div class="spinner">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <p>Enviando correo...</p>
      </div>
    </section>

    <section class="pages" id="Ambientes">
      <div id="contenedorPisos">
        <section class="pisos pi3">
          <span>
            <h3>Piso numero 3</h3>
            <i class="num_ambie"></i>
            <div class='consema'>
              <p class='semaforo dis dis3' title="Disponibles"></p>
            </div>
          </span>
          <button class="verAmbientes" data-piso="3">Ver ambientes</button>
          <img src="img/piso3.jpeg" alt="">
        </section>

        <section class="pisos pi2">
          <span>
            <h3>Piso numero 2</h3>
            <i class="num_ambie"></i>
            <div class='consema'>
              <p class='semaforo dis dis2' title="Disponibles"></p>
            </div>
          </span>
          <button class="verAmbientes" data-piso="2">Ver ambientes</button>
          <img src="img/piso2.jpeg" alt="">
        </section>

        <section class="pisos pi1">
          <span>
            <h3>Piso numero 1</h3>
            <i class="num_ambie"></i>
            <div class='consema'>
              <p class='semaforo dis dis1' title="Disponibles"></p>
            </div>
          </span>
          <button class="verAmbientes" data-piso="1">Ver ambientes</button>
          <img src="img/piso1.jpeg" alt="">
        </section>


      </div>
      <!--Se crean las tarjetas de ambientes dinamicamente con peticion fetch-->
      <div id="contenedorAMBIENTE">

      </div>

    </section>
 <!-- CUALQUIER ERROR DE AQUI HACIA ABAJO -->

 <?php

if ($rol == 2) {

  ?>

  <section class="pages" id="crearNovedad">

    <?php
    date_default_timezone_set('America/Bogota');

    if (isset($_SESSION['id'])) {
      $id_usuario = $_SESSION['id'];
  

      $sql = "SELECT ambiente.img, ambiente.nombre_ambiente, ambiente.piso_ambiente, asignacion.fecha_inicio, asignacion.idsolicitud, asignacion.fecha_fin 
    FROM ambiente 
    INNER JOIN asignacion ON ambiente.idambiente = asignacion.idambiente 
    WHERE asignacion.idusuario = $id_usuario";

      $result = $conexion->query($sql);
      $hora_actual = date("H:i:s"); 
      $fecha_actual = date("Y-m-d H:i:s");
      $reservasEncontradas = false;

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $fecha_inicio = $row["fecha_inicio"];
          $fecha_fin = $row["fecha_fin"];
  
          list($fecha, $hora_inicio) = explode(' ', $fecha_inicio);
          list($fecha, $hora_fin) = explode(' ', $fecha_fin);
  
          if ($hora_actual >= $hora_inicio && $hora_actual <= $hora_fin) {
              if ($fecha_actual >= $fecha_inicio && $fecha_actual <= $fecha_fin) {
            $img_ambiente = $row["img"];
            $nombre_ambiente = $row["nombre_ambiente"];
            $piso_ambiente = $row["piso_ambiente"];
            $fecha_inicio = $row["fecha_inicio"];
            $fecha_fin = $row["fecha_fin"];
            $id_asignacion = $row["idsolicitud"];

            echo '<div class="card">';
            echo '<img src="imgambientes/' . $img_ambiente . '" alt="Ambiente">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $nombre_ambiente . '</h5>';
            echo '<p class="card-text">Piso: ' . $piso_ambiente . '</p>';
            echo '<p class="card-text">Fecha Inicio: ' . $fecha_inicio . '</p>';
            echo '<p class="card-text">Fecha Fin: ' . $fecha_fin . '</p>';
            echo '</div>';
            echo '<div class="bt-space">';
            echo '<button class="mostrar-formulario" onclick="mostrarFormulario()">Cargar Novedad</button>';
            echo '</div>';
            echo '</div>';
            $reservasEncontradas = true;
          }
        }
      }}

      if (!$reservasEncontradas) {
        echo "No tienes reservas para el día de hoy.";
      } else {

      }
      ?>

      <div class="form-novedades" id="formulario" style="display: none;">
        <span class="cerrar-da" onclick="cerrarFormulario()">
          <svg class="salir" aria-label="Cerrar" color="#1c1e21" fill="#1c1e21" height="14" role="img"
            viewBox="0 0 24 24" width="14">
            <polyline fill="none" points="20.643 3.357 12 12 3.353 20.647" stroke="currentColor" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="3"></polyline>
            <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
              x1="20.649" x2="3.354" y1="20.649" y2="3.354"></line>
          </svg>
        </span>
        <form action="php/procesar_novedad.php" method="post">
          <input type="hidden" name="id_asignacion" value="<?php echo $id_asignacion; ?>">
          <input type="hidden" name="fecha_actual_reporte" value="<?php echo date('Y-m-d H:i:s'); ?>">

          <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">

          <label for="estado_reporte">Estado de la Novedad:</label>
          <textarea name="estado_reporte" id="estado_reporte" class="descripcion" required rows="6"
            maxlength="500"></textarea>

          <button type="submit" name="cargar_novedad" class="btn-cargar-novedad">Cargar Novedad</button>
        </form>
      </div>


    </section>
<section class="pages" id="verSolicitudes">
<div class="cont_amb">
<?php
$idusuario = $_SESSION['id'];
$sql = "SELECT asignacion.*, ambiente.nombre_ambiente, ambiente.numero_ambiente, ambiente.img FROM asignacion
        INNER JOIN ambiente ON asignacion.idambiente = ambiente.idambiente
        WHERE asignacion.idusuario = $idusuario";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="cardverAmbiente">';
        echo "<img src='imgambientes/" . $row["img"] . "' alt='Imagen del Ambiente'>";
        echo "<h3>Ficha: " . $row["numero_ficha"]. "</h3>";
        echo "<h3>Formación: " . $row["formacion"]. "</h3>";
        echo "<h3>Motivo: " . $row["motivo"]. "</h3>";
        echo "<h3>Nombre: " . $row["nombre_ambiente"]. "</h3>";
        echo "<h3>Número: " . $row["numero_ambiente"]. "</h3>";
        // echo "<a class='btn-eliminar' href='php/eliminar_solicitud.php?id=" . $row['idsolicitud'] . "'>Eliminar</a>";
        echo "<a class='btn-editar' href='php/editar_solicitud.php?id=" . $row['idsolicitud'] . "'>Editar solicitud</a>";
        echo '</div>';
    }
} else {
    echo "0 resultados";
}

// Cerrar la conexión
$conexion->close();
?>
</div>
</section>





    <?php

    }
}
?>




<!-- CUALQUIER ERROR SALE DE AQUI V: HACIA ARRIBA -->


    <?php

    if ($rol == 1) {
      require_once 'informeAmbiente/generarInforme.html';
      echo '
    
      <section class="pages" id="crearAmbiente">
  <article id="containeruser">
    <h2 class="titleuser">Registro de Ambiente</h2>
    <form action="php/crearAmbiente.php" method="POST" id="registrarAmbiente" class="form">

      <section class="form-group">
        <p>
          <label for="nombreAmbiente">Nombre de Ambiente:</label>
          <input type="text" name="nombreAmbiente" id="nombreAmbiente" class="inputs">
        </p>
        <p>
          <label for="pisoAmbiente">Piso de Ambiente:</label>
          <select name="pisoAmbiente" id="pisoAmbiente" class="select">
            <option value="1">Piso 1</option>
            <option value="2">Piso 2</option>
            <option value="3">Piso 3</option>
          </select>
        </p>
        <p>
          <label for="numeroAmbiente">Número de Ambiente:</label>
          <input type="text" name="numeroAmbiente" id="numeroAmbiente" class="inputs">
        </p>
      </section>
      <section class="form-group">
        <p>
          <label for="sillaAmbiente">Cantidad de Sillas:</label>
          <input type="number" name="sillaAmbiente" id="sillaAmbiente" class="inputs" value="0">
        </p>
        <p>
          <label for="mesaAmbiente">Cantidad de Mesas:</label>
          <input type="number" name="mesaAmbiente" id="mesaAmbiente" class="inputs" value="0">
        </p>
        <p>
          <label for="tvAmbiente">Cantidad de TVs:</label>
          <input type="number" name="tvAmbiente" id="tvAmbiente" class="inputs" value="0">
        </p>
      </section>
      <section class="form-group">
        <p>
          <label for="aire_acondicionado">Cantidad de Aires Acondicionados:</label>
          <input type="number" name="aire_acondicionado" id="aire_acondicionado" class="inputs" value="0">
        </p>
        <p>
          <label for="computadorAmbiente">Cantidad de Computadoras:</label>
          <input type="number" name="computadorAmbiente" id="computadorAmbiente" class="inputs" value="0">
        </p>
        <p>
          
      </section>
      <section class="form-group">
        <p>
          <label for="imagenAmbiente">Imagen frontal del ambiente:</label>
          <input type="file" name="imagen" id="imagenAmbiente" class="imginput" accept="image/*">
        </p>
        <p>
          <label for="imagenAngular">Imagen perspectiva por dentro:</label>
          <input type="file" name="imagenA" id="imagenAngular" class="imginput" accept="image/*">
        </p>
      </section>
      <section class="form-group-img">
        <div id="imagen">
          <img id="iconaddA" src="img/front.png" class="info_profile_icon">
          <img id="imagen_cargadaaddA" src="#" alt="imagen" class="info_profile_img" style="display: none;">
        </div>
        <div id="imagen">
          <img id="iconaddAngular" src="img/angular.png" class="info_profile_icon">
          <img id="imagenaddAngular" src="#" alt="imagenA" class="info_profile_img" style="display: none;">
        </div>
      </section>
      <input type="submit" name="crearAmbiente" value="Registrar Ambiente" class="btn-form">
    </form>
  </article>
  <div id="cargarDatos">
    <div class="formsExcel">
      <h6 class="titleExcel">Cargar Ambientes con excel</h6>
      <a href="plantillaAmbientes.csv" download="plantillaAmbientes.csv"><button class="btnexcel">Descarga tu
          plantilla</button></a>
    </div>
    <div class="formsExcel">
      <form action="" method="post" enctype="multipart/form-data" class="formsExcel" id="cargarExcelAmbientes">
        <input type="file" name="csvfile" id="datosAmbientes">
        <button type="submit" name="guardarDatosAmbientes" class="btnexcelI">Importar datos</button>
      </form>
    </div>
  </div>
</section>

<section class="pages" id="crearUsuario">
  <article id="containeruser">
    <h2 class="titleuser">Registro de Usuario</h2>
    <form action="php/crearUsuario.php" method="POST" id="registrarUsuario" enctype="multipart/form-data">
      <section class="form-group">
        <p>
          <label for="nombreUsuario">Nombre:</label>
          <input type="text" name="nombreUsuario" id="nombreUsuario" class="inputs">
        </p>
        <p>
          <label for="apellidoUsuario">Apellido:</label>
          <input type="text" name="apellidoUsuario" id="apellidoUsuario" class="inputs">
        </p>
        <p>
          <label for="documento">Numero Documento:</label>
          <input type="text" name="documento" id="documento"  class="inputs">
        </p>
      </section>
      <section class="form-group">
        <p>
          <label for="password">Contraseña:</label>
          <input type="password" name="password" id="password" minlength="7" class="inputs">
        </p>
        <p>
          <label for="correo">Correo:</label>
          <input type="email" name="correo" id="correo" class="inputs">
        </p>
        <p>
          <label for="telefono">Teléfono:</label>
          <input type="tel" name="telefono" id="telefono" maxlength="10" minlength="10" class="inputs">
        </p>
      </section>
      <section class="form-group">
        <p>
          <label for="rol">Rol:</label>
          <select name="rol" id="rol" class="select">
            <option value="1">Admin</option>
            <option value="3">Celador</option>
            <option value="2">Aprendiz</option>
            <option value="4">Instructor</option>
          </select>
        </p>
        <p>
          <label for="TV">Tipo de vinculacion:</label>
            <select name="TV" id="TV" class="select">
            <option value="0">Selecciona tipo de contrato</option>
            <option value="1">Planta</option>
            <option value="2">Temporal</option>
            <option value="3">Contratista</option>
            <option value="4">Contracto Aprendizaje</option>
          </select>
    
        </p>

        <p>
          <label for="imagenUsuario">Imagen:</label>
          <input type="file" name="imagen" id="imagenUsuario" class="imginput" accept="image/*">
        </p>
      </section>
      <section class="form-group" id="containerInputFecha">
      </section>

      <section class="form-group-img" id="container-img">
        <div id="imagen">
          <img id="iconadd" src="img/usericon.png" class="info_profile_icon">
          <img id="imagen_cargadaadd" src="#" alt="imagen" class="info_profile_img" style="display: none;">
        </div>
      </section>

      <input type="submit" name="crearUsuario" value="Registrar Usuario" class="btn-form">
    </form>

  </article>
  <div id="cargarDatos">
    <div class="formsExcel">
    
          <a href="descargarPlantillaUsuarios.pdf" download="descargarPlantillaUsuarios.php"><button
              class="btnexcel">Descargar guia de importe de usuarios</button>
          </a>
      <h6 class="titleExcel">Cargar usuarios con excel</h6>
      <a href="php/descargarPlantillaUsuarios.php"><button class="btnexcel">Descarga tu
          plantilla</button></a>
    </div>
    <div class="formsExcel">
      <form action="" method="post" enctype="multipart/form-data" class="formsExcel" id="cargarExcelUsuario">
        <input type="file" name="csvfile" id="datosUsuarios">
        <button type="submit" name="guardarDatosUsuario" class="btnexcelI">Importar datos</button>
      </form>
    </div>
  </div>

</section>


    <section class="pages" id="Usuarios">
      <form method="post" id="estadoForm">
        <div class="itemProfileview itemProfileviewHeader">
          <div class="profileAbout">
            <span>
              <b>Usuarios</b>
            </span>
          </div>

          <div class="profileDocument">
            <p>Tipo vinculación</p>
          </div>
          <div class="profileDocument">
            <p>Correo</p>
          </div>
          <div class="profileDocument">
            <p>Teléfono</p>
          </div>

          <div class="estados">
            <p>Estado <i class="fa-solid fa-check"></i></p>
          </div>
          <div class="estados">
            <p>Estado <i class="fa-solid fa-xmark"></i></p>
          </div>
        </div>

        </div>
        ';
        
        require_once "php/listarUsuarios.php";
    
     
        

     

      echo '      <button type="button" id="submitBtn" class="logoSpan">Actualizar Estado</button>
      </form>
    </section>
    ';
    }

    ?>

    <form method="post" enctype="multipart/form-data" class="pages" id="carruselNoticias">

      <header id="headerCarrusel">
        <h3>Lista de imagenes del carrusel de noticias</h3>
        <div>
          <label for="T100">Digite alguna información <br> importante Máximo 100 caracteres</label>
          <textarea name="textoNoticia" id="T100" cols="30" rows="10"></textarea>
          <input type="hidden" name="textoActual" id="T1000">
        </div>
      </header>

      <!--Cards dinamicas  -->
      <main id="mainCarrusel">
      </main>

      <!-- Input file de imagenes -->
      <label for="inputs" class="c-button c-button--gooey"> Subir imagenes
        <div class="c-button__blobs">
          <div></div>
          <div></div>
          <div></div>
        </div>
      </label>
      <input type="file" name="imagenes[]" id="inputs" accept="image/*" multiple>


      <div class="controlsCarrusel">
        <button type="submit" class="bbutton"> Guardar</button>
      </div>

    </form>


    <section class="novedad_celador" id="novedad" onclick="cerrarFormularioN()">

    </section>

    <div id="form_novedad">


      <form action="" id="forma_novedad" class="formulario_novedad">
        <input type="hidden" id="" name="idusuario" value='<?php echo $_SESSION['id'] ?>'>
        <img src="./img/close.svg" class="img_close" onclick="cerrarFormularioN()" alt="">
        <h2>GENERAR NOVEDAD</h2>
        <label for="">Piso</label>
        <select name="idpiso" id="list-pisos">
          <option value="0">Selecciona</option>
          <option value="1">Piso 1</option>
          <option value="2">Piso 2</option>
          <option value="3">Piso 3</option>
        </select>

        <label>Ambientes</label>
        <select name="idambiente" id="list-ambientes">
          <option value="0">Selecciona</option>
        </select>

        <label for="">Descripción de novedad</label>
        <textarea name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
        <input type="submit" class="my-button2" value="Enviar">
      </form>
    </div>
  </main>

  <script src="js/info_ambie_pisos.js"></script>
  <script src="js/admin.js"></script>
  <script src="js/ampliarImagen.js"></script>
  <script src="js/consultaAmbientes.js"></script>
  <script src="js/estadoUsuario.js"></script>
  <script src="js/crearUsuario.js"></script>
  <script src="js/crearAmbiente.js"></script>
  <script src="js/cargarExcelUsuarios.js"></script>
  <script src="js/cargarExcelAmbientes.js"></script>
  <script src="js/cargarExcelAsignacion.js"></script>
  <script src="js/mostrarimg.js"></script>
  <script src="archivos_calendario/index.global.js"></script>
  <script src="archivos_calendario/index.global.min.js"></script>
  <script src="archivos_calendario/es.global.js"></script>
  <script src="js/detalles_ambie.js"></script>
  <script src="js/slider.js"></script>
  <script src="js/cerrar.js"></script>
  <script src="js/CargarSlider.js"></script>
  <script src="js/menuResponsive.js"></script>
  <script src="js/modal1.js"></script>
  <script src="js/ventana.js"></script>
  <script src="js/novedad.js"></script>
  <script src="js/informeAsignacion.js"></script>
  <script src="js/EstadoAmbi.js"></script>
  <script src="js/validarVinculacion.js"></script>
  <script src="js/abrirDescrip.js"></script>
</body>

</html>