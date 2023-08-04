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

/* --> Inicio: Script para solo aceptar telefonos en un input */

/* Fin: Script para solo aceptar telefonos en un input */

/* --> Inicio: Script para solo aceptar letras y espacios en un input */
// Obtener todos los inputs con la clase 'nombre'
const nombresInputs = document.querySelectorAll(".solo-letras");

// Agregar un evento de escucha al evento 'input' para cada input con la clase 'nombre'
nombresInputs.forEach((nombreInput) => {
  nombreInput.addEventListener("input", function (event) {
    // Obtener el valor actual del input
    const valor = event.target.value;

    // Remover cualquier caracter que no sea una letra o espacio utilizando una expresión regular
    const soloLetrasYEspacios = valor.replace(/[^A-Za-z\s]/g, "");

    // Actualizar el valor del input con solo letras y espacios
    event.target.value = soloLetrasYEspacios;
  });
});
/* Fin: Script para solo aceptar letras y espacios en un input */