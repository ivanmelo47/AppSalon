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

const cita = {
  id: '',
  nombre: '',
  fecha: '',
  hora: '',
  servicios: []
}

document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
});

function iniciarApp() {

  mostrarSeccion(); // Muestra y oculta las secciones
  tabs(); // Cambia la sección cuando se presionen los tabs
  botonesPaginador(); // Agrega o quita los botones del paginador
  paginaSiguiente(); // 
  paginaAnterior(); //

  consultarAPI(); // Consulta la API en BackEnd de PHP

  idCliente(); // Agrega el id de cliente al objeto de cita
  nombreCliente(); // Añade el nombre del cliente al objeto de cita
  seleccionarFecha(); // Añade la fecha de la cita en el objeto de cita
  seleccionarHora(); // Añade la hora de la cita en el objeto de cita

  mostrarResumen(); // Muestra resumen de la cita

}

function mostrarSeccion() {
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

  botones.forEach(boton => {
    boton.addEventListener("click", function (e) {
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
  } else if (paso === 3) {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.add('ocultar');
    mostrarResumen();
  } else {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  }

  mostrarSeccion();
}

function paginaAnterior() {
  const paginaAnterior = document.querySelector('#anterior');
  paginaAnterior.addEventListener('click', function () {

    if (paso <= pasoInicial) return;

    paso--;

    botonesPaginador();
  });
}

function paginaSiguiente() {
  const paginaSiguiente = document.querySelector('#siguiente');
  paginaSiguiente.addEventListener('click', function () {

    if (paso >= pasoFinal) return;

    paso++;

    botonesPaginador();
  });
}
/* --> Fin: Script para botones de Tab ---------------- */


/* --> Inicio: Consumiendo la API desde PHP ---------------- */
async function consultarAPI() {

  try {
    const url = `${location.origin}/api/servicios`;
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);

  } catch (error) {
    console.log(error);
  }
}


function mostrarServicios(servicios) {
  servicios.forEach(servicio => {
    const { id, nombre, precio } = servicio;

    const nombreServicio = document.createElement('P');
    nombreServicio.classList.add('nombre-servicio');
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.classList.add('precio-servicio');
    precioServicio.textContent = `$${precio}`;

    const servicioDiv = document.createElement('DIV');
    servicioDiv.classList.add('servicio');
    servicioDiv.dataset.idServicio = id;
    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    }

    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    document.querySelector('#servicios').appendChild(servicioDiv);
  });
}
/* --> Fin: Consumiendo la API desde PHP ---------------- */

/* --> Inicio: Seleccionando servicio ---------------- */
function seleccionarServicio(servicio) {
  const { id } = servicio;
  const { servicios } = cita;

  // Ideentifcar el elemento al cual se le da click
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  // Comprobar si el servicio ya fue agregado
  if (servicios.some(agregado => agregado.id === id)) {
    // Elimarlo
    cita.servicios = servicios.filter(agregado => agregado.id !== id);
    divServicio.classList.remove('seleccionado');
  } else {
    // Agregarlo
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add('seleccionado');
  }

  console.log(cita);

}
/* --> Fin: Seleccionando servicio  ---------------- */

/*  */
function idCliente() {
  const id = document.querySelector('#idUsuario').value;
  cita.id = id;
}

function nombreCliente() {
  const nombre = document.querySelector('#nombre').value;
  cita.nombre = nombre;
}

function seleccionarFecha() {
  const inputFecha = document.querySelector('#fecha');
  inputFecha.addEventListener('input', function (e) {

    const dia = new Date(e.target.value).getUTCDay();

    if ([6, 0].includes(dia)) {
      e.target.value = '';
      mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
    } else {
      cita.fecha = e.target.value;
    }

  });
}

function seleccionarHora() {
  const inputHora = document.querySelector('#hora');
  inputHora.addEventListener('input', function (e) {


    const horaCita = e.target.value;
    const hora = horaCita.split(":")[0];
    if (hora < 10 || hora > 18) {
      e.target.value = '';
      mostrarAlerta('Hora No Válida', 'error', '.formulario');
    } else {
      cita.hora = e.target.value;

      // console.log(cita);
    }
  })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

  // Previene que se generen más de 1 alerta
  const alertaPrevia = document.querySelector('.alerta');
  if (alertaPrevia) {
    alertaPrevia.remove();
  }

  // Creacion del elemento alerta
  const alerta = document.createElement('DIV');
  alerta.textContent = mensaje;
  alerta.classList.add('alerta');
  alerta.classList.add(tipo);

  // Agrega la alerta al html
  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  if (desaparece) {
    // Eliminar la alerta
    setTimeout(() => {
      alerta.remove();
    }, 3000);
  }
}
/*  */

function mostrarResumen() {
  const resumen = document.querySelector('.contenido-resumen');

  // Limpiar el contenido de Resumen
  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }

  if (Object.values(cita).includes('') || cita.servicios.length === 0) {
    mostrarAlerta('Faltan datos de Servicio, Fecha u Hora', 'error', '.contenido-resumen', false);

    return;
  }

  // Formatear el div de resumen
  const { nombre, fecha, hora, servicios } = cita;

  // Heading para servicios en Resumen
  const headingServicios = document.createElement('H3');
  headingServicios.textContent = 'Resumen de Servicios';
  resumen.appendChild(headingServicios);

  // Iterando y mostrando los servicios
  servicios.forEach(servicio => {
    const { id, precio, nombre } = servicio;

    const contenedorServicio = document.createElement('DIV');
    contenedorServicio.classList.add('contenedor-servicio');

    const textoServicio = document.createElement('P');
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);

    resumen.appendChild(contenedorServicio);
  });

  // Heading para servicios en Resumen
  const headingCita = document.createElement('H3');
  headingCita.textContent = 'Resumen de Cita';
  resumen.appendChild(headingCita);

  const nombreCliente = document.createElement('P');
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  // Inicio: Formatear la fecha en español
  const fechaObj = new Date(fecha);
  const mes = fechaObj.getMonth();
  const dia = fechaObj.getDate() + 2;
  const year = fechaObj.getFullYear();

  const fechaUTC = new Date(Date.UTC(year, mes, dia));

  const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
  const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);
  // Fin: Formatear la fecha en español


  const fechaCita = document.createElement('P');
  fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

  const horaCita = document.createElement('P');
  horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

  // Boton para crear una cita
  const botonReservar = document.createElement('BUTTON');
  botonReservar.classList.add('boton');
  botonReservar.textContent = 'Reservar Cita';
  botonReservar.onclick = reservarCita;

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
  resumen.appendChild(botonReservar);
}

async function reservarCita() {

  const { nombre, fecha, hora, servicios, id } = cita;

  const idServicios = servicios.map(servicio => servicio.id);
  console.log(idServicios);

  //return;

  const datos = new FormData();
  datos.append('fecha', fecha);
  datos.append('hora', hora);
  datos.append('usuarioId', id);
  datos.append('servicios', idServicios);

  //console.log([...datos]);
  //return;

  try {
    // Peticion hacia la API
    const url = `${location.origin}/api/citas`;

    const respuesta = await fetch(url, {
      method: 'POST',
      body: datos
    });

    const resultado = await respuesta.json();

    //console.log(resultado);

    if (resultado.resultado) {
      Swal.fire({
        icon: 'success',
        title: 'Cita Creada!',
        text: 'Tu Cita fue creada correctamente!',
        button: 'OK',
        timer: 3000, // Duración en milisegundos (3 segundos)
        customClass: {
          popup: 'custom-alert-popup',         // Clase para el popup de la alerta
          content: 'custom-alert-content'      // Clase para el contenido de la alerta
        }
      }).then(() => {
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      });
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Hubo un error al guardar la cita!',
      button: 'OK',
      customClass: {
        popup: 'custom-alert-popup',         // Clase para el popup de la alerta
        content: 'custom-alert-content'      // Clase para el contenido de la alerta
      }
    });
  }



  // console.log([...datos]);
}
