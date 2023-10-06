<?php
require_once 'Conexion.php';

$consulta = $conexion->query("SELECT * FROM usuario   INNER JOIN estado_usuario ON  usuario.idestado = estado_usuario.idestado JOIN vinculacion ON usuario.idvinculacion = vinculacion.idvinculacion ");
if ($consulta->num_rows > 0) {
    while ($persona = $consulta->fetch_assoc()) {
        if ($persona['idrol'] === '1') {
            $rol = 'Admin';
        } else if ($persona['idrol'] === '2') {
            $rol = 'Aprendiz';
        } else if ($persona['idrol'] === '3') {
            $rol = 'Celador';
        } else if ($persona['idrol'] === '4') {
            $rol = 'Instructor';
        }
        $imagen = $persona['imagen'];
        // Modificar la ruta de la imagen si es necesario
        $modificarRuta = 'imgusuario/'.$imagen;

      

        echo '
        <div class="itemProfileview">
            <div class="profileAbout">
              <img src="'.$modificarRuta.'" alt="" />
              <span>
                <b>' . $persona['nombre_usuario'] . '</b>
                <p>' . $rol . '</p>
              </span>
            </div>

            <div class="profileDocument">
              <b>Tipo vinculación</b>
              <p>' . $persona['nombrevinculacion'] . '</p>
            </div>




            <div class="profileDocument">
              <b>Correo</b>
              <p>' . $persona['correo'] . '</p>
            </div>
            <div class="profileDocument">
              <b>teléfono</b>
              <p>' . $persona['telefono'] . '</p>
            </div>


                <div class="estados">
                    <input type="radio" name="estadoCliente[' . $persona['idusuario'] . ']" value="1" ' . ($persona['estado_usuario'] == 'Activo' ? 'checked' : '') . ' id="activo-' . $persona['idusuario'] . '">
                    <label for="activo-' . $persona['idusuario'] . '">Activo</label>
                </div>
                <div class="estados">
                    <input type="radio" id="inactivo-' . $persona['idusuario'] . '" name="estadoCliente[' . $persona['idusuario'] . ']" value="2" ' . ($persona['estado_usuario'] == 'Desactivado' ? 'checked' : '') . '>
                    <label for="inactivo-' . $persona['idusuario'] . '">Inactivo</label>
                </div>
        
            
          </div>';
    }






} else {
    echo "<p>No hay usuarios</p>";
}
?>