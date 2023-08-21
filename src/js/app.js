/* --> Inicio: Script para solo aceptar numero en un input */
// Obtener todos los inputs con la clase 'telefono'
const numerosInputs = document.querySelectorAll(".solo-numeros");

// Agregar un evento de escucha al evento 'input' para cada input con la clase 'telefono'
numerosInputs.forEach((telefonoInput) => {
  telefonoInput.addEventListener("input", function (event) {
    // Obtener el valor actual del input
    const valor = event.target.value;

    // Remover cualquier caracter que no sea un número utilizando una expresión regular
    const soloNumeros = valor.replace(/\D/g, "");

    // Actualizar el valor del input con solo números
    event.target.value = soloNumeros;
  });
});
/* Fin: Script para solo aceptar numero en un input */


/* --> Inicio: Script para solo aceptar telefonos en un input ---------------- */
function formatPhoneNumber(input) {
  let phoneNumber = input.value.replace(/\D/g, ""); // Eliminamos caracteres no numéricos

  // Si el número tiene menos de 4 dígitos, no aplicamos formato
  if (phoneNumber.length < 4) {
    input.value = phoneNumber;
  } else {
    // Aplicamos el formato (XXX) XXX XXXX
    let formattedNumber = `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(
      3,
      6
    )} ${phoneNumber.slice(6)}`;
    input.value = formattedNumber.trim();
  }
}

window.onload = function () {
  // Seleccionar todos los elementos con la clase "solo-telefono"
  let phoneInputs = document.querySelectorAll(".solo-telefono");

  // Agregar el evento input a cada input con la clase "solo-telefono"
  phoneInputs.forEach((input) => {
    input.addEventListener("input", function () {
      formatPhoneNumber(this);
    });

    input.addEventListener("keydown", function (event) {
      // Permite borrar el número si el usuario presiona la tecla "Backspace" o "Delete"
      if (event.key === "Backspace" || event.key === "Delete") {
        return;
      }

      // Verifica si el número está siendo editado en medio del campo
      if (
        input.selectionStart !== input.selectionEnd ||
        input.selectionStart < 4
      ) {
        // Si el usuario está editando en medio del campo o antes del primer paréntesis,
        // permitimos la escritura normalmente
        return;
      } else if (input.selectionStart === 3 || input.selectionStart === 7) {
        // Si el usuario edita justo después de cada espacio, adelantamos el cursor en un espacio
        input.setSelectionRange(
          input.selectionStart + 1,
          input.selectionStart + 1
        );
      }
    });
  });
};
/* Fin: Script para solo aceptar telefonos en un input ----------------------- */


/* --> Inicio: Script para solo aceptar nombre(s) en un input ---------------- */
// Obtener todos los elementos con la clase 'custom-input'
const inputElements = document.querySelectorAll(".solo-nombre");

// Función para aplicar las restricciones y cambios al valor del input
function handleInput(event) {
  let inputValue = event.target.value;

  // 1. Solo aceptar letras (eliminar números y símbolos especiales)
  const lettersOnly = inputValue.replace(/[^A-Za-z\s]/g, "");

  // 2. No permitir más de un espacio entre palabras y no permitir espacios al principio
  const singleSpace = lettersOnly.replace(/^\s+|\s+(?=\s)/g, "");

  // 3. Convertir la primera letra de cada palabra en mayúscula y las demás en minúscula
  const titleCase = singleSpace
    .toLowerCase()
    .replace(/\b\w/g, (char) => char.toUpperCase());

  // Actualizar el valor del input con los cambios realizados
  event.target.value = titleCase;
}

// Agregar el evento de escucha a cada elemento con la clase 'custom-input'
inputElements.forEach((inputElement) => {
  inputElement.addEventListener("input", handleInput);
});
/* --> Fin: Script para solo aceptar nombre(s) en un input ---------------- */


/* --> Inicio: Script para botones de Tab ---------------- */
let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

document.addEventListener("DOMContentLoaded", function() {
  iniciarApp();
});

function iniciarApp() {

  mostrarSeccion(); // Muestra y oculta las secciones
  tabs(); // Cambia la sección cuando se presionen los tabs
  botonesPaginador(); // Agrega o quita los botones del paginador
  paginaSiguiente(); // 
  paginaAnterior(); // 
}

function mostrarSeccion(){
  // Ocultar la seccion la seccion que tenga la clase de mostrar
  const seccionAnterior = document.querySelector('.mostrar');
  if (seccionAnterior) {
    seccionAnterior.classList.remove('mostrar');
  }

  // Seleccionar la sección con el paso
  const seccion = document.querySelector(`#paso-${paso}`);
  seccion.classList.add("mostrar");

  // Quita la clase de actual al tab anterior
  const tabAnterior = document.querySelector('.actual');
  if (tabAnterior) {
    tabAnterior.classList.remove('actual');
  }

  // Resalta el tab actual
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  tab.classList.add("actual");
}

function tabs() {
  const botones = document.querySelectorAll(".tabs button");

  botones.forEach( boton => {
    boton.addEventListener("click", function(e) {
      paso = parseInt(e.target.dataset.paso);

      mostrarSeccion();
      botonesPaginador();
    });
  });
}

function botonesPaginador() {
  const paginaAnterior = document.querySelector('#anterior');
  const paginaSiguiente = document.querySelector('#siguiente');

  if (paso === 1) {
    paginaAnterior.classList.add('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  }else if(paso === 3) {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.add('ocultar');
  }else {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  }

  mostrarSeccion();
}

function paginaAnterior(){
  const paginaAnterior = document.querySelector('#anterior');
  paginaAnterior.addEventListener('click', function (){
    
    if (paso <= pasoInicial) return;

    paso --;

    botonesPaginador();
  });
}

function paginaSiguiente(){
  const paginaSiguiente = document.querySelector('#siguiente');
  paginaSiguiente.addEventListener('click', function (){
    
    if (paso >= pasoFinal) return;

    paso ++;

    botonesPaginador();
  });
}
/* --> Fin: Script para botones de Tab ---------------- */
