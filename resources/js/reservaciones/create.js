const selectclienteid = document.getElementById('cliente_id');
const txtdni = document.getElementById('cliente_dni');
const txttelefono = document.getElementById('cliente_telefono');
const txtemail = document.getElementById('cliente_email');
const dtpfechaEntrada = document.getElementById('fecha_entrada');
const dtpfechaSalida = document.getElementById('fecha_salida');
const txttotal = document.getElementById('total');
const selectHabitacion = document.getElementById('habitacion');

function CalcularTotal() {
    const fechaSalida = dtpfechaSalida.value;
    const fechaEntrada = dtpfechaEntrada.value;

    if (!fechaSalida || !fechaEntrada) {
        console.error('Por favor, selecciona ambas fechas.');
        return;
    }

    const fecha1 = new Date(fechaSalida);
    const fecha2 = new Date(fechaEntrada);

    if (fecha1 <= fecha2) {
        console.error('La fecha de salida debe ser posterior a la fecha de entrada.');
        return;
    }

    const diferenciaMilisegundos = fecha1 - fecha2;
    const diasDiferencia = Math.ceil(diferenciaMilisegundos / (1000 * 60 * 60 * 24));

    const habitacionSeleccionada = selectHabitacion.options[selectHabitacion.selectedIndex];
    const precioPorDia = parseFloat(habitacionSeleccionada.dataset.precio);

    if (isNaN(precioPorDia)) {
        console.error('El precio por día no es válido o no ha sido seleccionado.');
        return;
    }

    const total = precioPorDia * diasDiferencia;

    txttotal.value = total.toFixed(2);
}

dtpfechaEntrada.addEventListener('change', CalcularTotal);
dtpfechaSalida.addEventListener('change', CalcularTotal);
selectHabitacion.addEventListener('change', CalcularTotal);


selectclienteid.addEventListener('change', function () {
    const opcionSeleccionada = this.options[this.selectedIndex];

    const dni = opcionSeleccionada.getAttribute('data-dni');
    const telefono = opcionSeleccionada.getAttribute('data-telefono');
    const email = opcionSeleccionada.getAttribute('data-email');

    txtdni.value = dni || '';
    txttelefono.value = telefono || '';
    txtemail.value = email || '';
});


