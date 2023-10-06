
// Función para cargar los ambientes relacionados
function cargarMunicipios(idDep) {
    fetch(`./php/call_ambientes.php?id_dep=${idDep}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al cargar municipios");
            }
            return response.json();
        })
        .then(data => {
            // Referenciamos la etiqueta select de municipios
            var selectMun = document.getElementById("list-ambientes");

            // Limpiamos el select antes de añadir nuevas opciones
            selectMun.innerHTML = '';

            // Recorremos las opciones y las añadimos al select de municipios
            data.forEach(function (opcion) {
                var option = document.createElement("option");
                option.value = opcion.id;
                option.text = opcion.nombre;
                selectMun.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error:", error);
        });
}



// Agregamos un evento de cambio al select de departamentos
var selectDep = document.getElementById("list-pisos");
selectDep.addEventListener("change", function () {
    var selectedValue = selectDep.value;
    cargarMunicipios(selectedValue);
});
