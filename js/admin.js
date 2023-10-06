var bodyId = $("body").data("nuevas");

if (bodyId) {
  toastr.options = {
    closeButton: false,
    progressBar: true,
    positionClass: 'toast-top-right',
    onclick: function () {
      mostrarContenedor('Solicitudes', document.getElementById("4"));
    }
  };

  toastr.info("Tienes " + bodyId + " solicitudes pendientes. Haz clic para responderlas.");
}


var activeContainer = localStorage.getItem('activeContainer');
var activeBoton = localStorage.getItem('activeBoton');
if (activeContainer && activeBoton) {
  botonId = document.getElementById(activeBoton);
  mostrarContenedor(activeContainer, botonId); // Mostrar el contenedor guardado en el menú
} else {
  mostrarContenedor('Ambientes', document.querySelector(".pri")); // Mostrar el contenedor de Dashboard por defecto
}


function mostrarContenedor(contenedorId, boton) {
  var contenedores = document.getElementsByClassName('pages');
  for (var i = 0; i < contenedores.length; i++) {
    contenedores[i].style.display = 'none'; // Ocultar todos los contenedores
  }

  var botones = document.getElementsByClassName('botones');
  for (var i = 0; i < botones.length; i++) {
    botones[i].classList.remove("active-nav");
    p = botones[i].querySelector("p");
    iElement = botones[i].querySelector("i");
    p.classList.remove("active-nav-p");
    iElement.classList.remove("active-nav-p");
  }

  var p = boton.querySelector("p");
  var iElement = boton.querySelector("i");
  p.classList.add("active-nav-p");
  iElement.classList.add("active-nav-p");
  boton.classList.add("active-nav");

  if (contenedorId == "Ambientes") {
    recibirDatos();
  }

  document.getElementById(contenedorId).style.display = 'flex'; // Mostrar el contenedor seleccionado
  localStorage.setItem('activeContainer', contenedorId); // Guardar el contenedor seleccionado en el menú
  localStorage.setItem('activeBoton', boton.id);
}


function mostrarFormulario() {
  document.getElementById("formulario").style.display = "block";
}


function cerrarFormulario() {
  document.getElementById("formulario").style.display = "none";
}

function mostrarUserCard() {
  var modal = document.getElementById("modal");
  modal.style.display = "block";
}

function ocultarUserCard() {
  var modal = document.getElementById("modal");
  modal.style.display = "none";
}

window.onclick = function (event) {
  var modal = document.getElementById("modal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
}




