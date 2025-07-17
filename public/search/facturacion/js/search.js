import { search } from "./export_search.js";
const mysearchp = document.querySelector("#mysearch");
const ul_add_lip = document.querySelector("#showlist");
const myurlp = "/myurlventa";
const searchproduct = new search(myurlp, mysearchp, ul_add_lip);
searchproduct.InputSearch();
function totalizarFactura() {
    const tabla = document.getElementById('tabla_productos').querySelector('tbody');
    const filas = tabla.querySelectorAll('tr');

    let importeExento = 0;
    let importeExonerado = 0;
    let importeGrav15 = 0;
    let importeGrav18 = 0;
    let descuentoTotal = 0;

    filas.forEach(fila => {
        const cantidad = parseFloat(fila.querySelector('input[name^="cantidad"]').value) || 0;
        const precioUnitario = parseFloat(fila.querySelector('input[name^="precio_unitario"]').value) || 0;
        const descuento = parseFloat(fila.querySelector('input[name^="descuento"]').value) || 0;
        const isv = parseFloat(fila.querySelector('td[data-isv]').dataset.isv) || 0;

        const total = (cantidad * precioUnitario) - descuento;
        fila.querySelector('.total').textContent = total.toFixed(2);

        if (isv === 0) {
            importeExento += total;
        } else if (isv === 15) {
            importeGrav15 += total;
        } else if (isv === 18) {
            importeGrav18 += total;
        } else if (isv === -1) {
            importeExonerado += total;
        }

        descuentoTotal += descuento;
    });

    const isv15 = importeGrav15 - (importeGrav15 / 1.15);
    const isv18 = importeGrav18 - (importeGrav18 / 1.18);
    const totalFactura = importeExento + importeExonerado + importeGrav15 + importeGrav18;

    document.getElementById('LblImpExen').textContent = importeExento.toFixed(2);
    document.getElementById('importeExento').value = importeExento.toFixed(2);
    document.getElementById('LblImpExon').textContent = importeExonerado.toFixed(2);
    document.getElementById('importeExonerado').value = importeExonerado.toFixed(2);
    document.getElementById('LblImpGrav15').textContent = (importeGrav15 / 1.15).toFixed(2);
    document.getElementById('importeGrav15').value = (importeGrav15 / 1.15).toFixed(2);
    document.getElementById('LblImpGrav18').textContent = importeGrav18.toFixed(2);
    document.getElementById('importeGrav18').value = importeGrav18.toFixed(2);
    document.getElementById('LblIsv15').textContent = isv15.toFixed(2);
    document.getElementById('isv15').value = isv15.toFixed(2);
    document.getElementById('LblIsv18').textContent = isv18.toFixed(2);
    document.getElementById('isv18').value = isv18.toFixed(2);
    document.getElementById('LblDescto').textContent = descuentoTotal.toFixed(2);
    document.getElementById('descuento').value = descuentoTotal.toFixed(2);
    document.getElementById('LblTotal').textContent = totalFactura.toFixed(2);
    document.getElementById('total').value = totalFactura.toFixed(2);
}

document.getElementById('agregar_producto').addEventListener('click', agregarProducto);
document.getElementById('precio_unitario').addEventListener('keydown', function (event) {
    if (event.key === "Enter") { // Enter
        agregarProducto();
    }
});

document.getElementById('tabla_productos').addEventListener('click', function (e) {
    if (e.target.classList.contains('eliminar-producto')) {
        e.target.closest('tr').remove();
        totalizarFactura();
    }
});

function calcularTotal() {
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const precioUnitario = parseFloat(document.getElementById('precio_unitario').value) || 0;
    const total = cantidad * precioUnitario;
    document.getElementById('totalP').value = total.toFixed(2);
}

function validarNumero(event) {
    const input = event.target;
    const valor = input.value;

    if (valor === "") {
        return;
    }

    const valorNumerico = parseFloat(valor);
    if (isNaN(valorNumerico) || valorNumerico <= 0) {
        input.value = 1;
    }
}
document.getElementById('cantidad').addEventListener('input', () => {
    calcularTotal();
});
document.getElementById('precio_unitario').addEventListener('input', () => {
    calcularTotal();
});

const preciotxt = document.getElementById('precio_unitario');
const canttxt = document.getElementById('cantidad')

preciotxt.addEventListener("input", validarNumero);
canttxt.addEventListener('input', validarNumero);


document.getElementById('cantidad').addEventListener('input', calcularTotal);
document.getElementById('precio_unitario').addEventListener('input', calcularTotal);

const editarModal = document.getElementById('editarModal');
const formEditar = document.getElementById('formEditar');

document.addEventListener('click', function (event) {
    if (event.target.closest('.editar-producto')) {
        const botonEditar = event.target.closest('.editar-producto');
        const index = botonEditar.getAttribute('data-index');

        const fila = document.querySelector(`tr[data-index="${index}"]`);

        document.getElementById('modalProd').readOnly = true;
        document.getElementById('modalTotal').readOnly = true;
        document.getElementById('modalPrecio').readOnly = true;
        const producto = fila.querySelector(`input[name="producto[${index}]"]`).value;
        const codigo = fila.querySelector(`input[name="codigo[${index}]"]`).value;
        const cantidad = fila.querySelector(`input[name="cantidad[${index}]"]`).value;
        const precio = fila.querySelector(`input[name="precio_unitario[${index}]"]`).value;
        const descuento = fila.querySelector(`input[name="descuento_prod[${index}]"]`).value || 0;
        const total = fila.querySelector(`input[name="total[${index}]"]`).value;

        document.getElementById('modalProd').setAttribute('data-code', codigo);
        document.getElementById('modalProd').value = producto;
        document.getElementById('modalCantidad').value = cantidad;
        document.getElementById('modalPrecio').value = precio;
        document.getElementById('modalDescuento').value = descuento;
        document.getElementById('modalTotal').value = total;

        formEditar.setAttribute('data-index', index);

        $(editarModal).modal('show');
    }
});

formEditar.addEventListener('input', function () {
    const cantidad = parseFloat(document.getElementById('modalCantidad').value) || 0;
    const precio = parseFloat(document.getElementById('modalPrecio').value) || 0;
    const descuento = parseFloat(document.getElementById('modalDescuento').value) || 0;

    const total = (cantidad * precio) - descuento;
    document.getElementById('modalTotal').value = total.toFixed(2);
});
document.getElementById('guardarCambios').addEventListener('click', function () {
    const errorDivModal = document.getElementById('errorModal');
    errorDivModal.innerHTML = '';
    const codigo = document.getElementById('modalProd').dataset.code;
    const cantidad = parseFloat(document.getElementById('modalCantidad').value.trim()) || 0;
    const precio = parseFloat(document.getElementById('modalPrecio').value.trim()) || 0;
    const descuento = parseFloat(document.getElementById('modalDescuento').value.trim()) || 0;
    const total = parseFloat(document.getElementById('modalTotal').value.trim()) || 0;

    if (precio <= 0) {
        errorDivModal.innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Ingrese un precio válido mayor a 0.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
        return;
    }

    if (cantidad <= 0) {
        errorDivModal.innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Ingrese una cantidad válida mayor a 0.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
        return;
    }

    async function verificarPrecioProducto(code) {
        try {
            const responsePrecio = await fetch(`/verificar-precios-productos/${code}`);
            if (!responsePrecio.ok) {
                throw new Error('Error en la verificación de precios.');
            }
            const data = await responsePrecio.json();
            const costo = parseInt(data.costo); // Asegúrate de que 'costo' existe en el JSON devuelto
            return costo;
        } catch (error) {
            console.error('Error:', error.message);
            throw error; // Puedes manejar el error según sea necesario
        }
    }
    let costo = verificarPrecioProducto(codigo)

    if (precio < costo) {
        errorDivModal.innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>El precio no puede ser menor al precio de costo (${costo.toFixed(2)}).</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
        return;
    }

    const index = formEditar.getAttribute('data-index');
    const fila = document.querySelector(`tr[data-index="${index}"]`);

    fila.querySelector(`#cant\\[${index}\\]`).textContent = cantidad;
    fila.querySelector(`input[name="cantidad[${index}]"]`).value = cantidad;

    fila.querySelector(`#descuento\\[${index}\\]`).textContent = descuento;
    fila.querySelector(`input[name="descuento_prod[${index}]"]`).value = descuento;

    fila.querySelector(`#total\\[${index}\\]`).textContent = total;
    fila.querySelector(`input[name="total[${index}]"]`).value = total;

    totalizarFactura();
    $(editarModal).modal('hide');
});

document.getElementById('modalPrecio').addEventListener('input', validarNumero);
document.getElementById('modalCantidad').addEventListener('input', validarNumero);

function configurarValidacion(cantidadInput, precioInput, codigoInput, agregarBtn, errorDiv) {
    function mostrarError(mensaje) {
        errorDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>${mensaje}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        agregarBtn.disabled = true;
    }

    function limpiarError() {
        errorDiv.innerHTML = '';
        agregarBtn.disabled = false;
    }

    async function manejarValidacion(tipo, valor) {
        const codigo = codigoInput.value;

        if (valor <= 0 || !codigo) {
            mostrarError(`Ingrese un ${tipo === 'cantidad' ? 'cantidad válida' : 'precio válido'}.`);
            return;
        }

        try {
            const endpoint = tipo === 'cantidad'
                ? `/verificar-existencias-productos/${codigo}`
                : `/verificar-precios-productos/${codigo}`;

            const response = await fetch(endpoint);

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const data = await response.json();
            const limite = parseInt(data);

            if ((tipo === 'cantidad' && valor > limite) || (tipo === 'precio' && valor < limite)) {
                const mensaje = tipo === 'cantidad'
                    ? `La cantidad ingresada excede las existencias disponibles (${limite}).`
                    : `El precio ingresado no puede ser menor que el precio de compra (${limite}).`;
                mostrarError(mensaje);
            } else {
                limpiarError();
            }
        } catch (error) {
            console.error(`Error al verificar ${tipo}:`, error);
            mostrarError('No se pudo verificar los datos. Intente nuevamente.');
        }
    }

    cantidadInput.addEventListener('input', async function () {
        this.value = this.value.replace(/\D+/g, '');
        const cantidad = parseInt(this.value) || 0;
        try {
            const responseUnidades = await fetch(`/verificar-unidades-productos/${document.getElementById('codigoprod').value}`);
            if (!responseUnidades.ok) {
                throw new Error('Error en la verificación de unidades.');
            }
            const data = await responseUnidades.json();
            const _cantsalida = parseInt(data.salida);

            agregarBtn.disabled = true; // Asegúrate de que esta variable esté definida
            manejarValidacion('cantidad', cantidad * _cantsalida); // Asegúrate de que esta función esté implementada
        } catch (error) {
            console.error('Error:', error.message);
        }
    });


    precioInput.addEventListener('input', function () {
        const precio = parseInt(this.value) || 0;
        agregarBtn.disabled = true;
        manejarValidacion('precio', precio);
    });
}

configurarValidacion(
    document.getElementById('cantidad'),
    document.getElementById('precio_unitario'),
    document.getElementById('codigoprod'),
    document.getElementById('agregar_producto'),
    document.getElementById('error')
);

let contadorFilas = 0;
async function agregarProducto() {
    let NoagregarProdsAFila = true;

    const codigo = document.getElementById('codigoprod').value.trim();
    const prodId = document.getElementById('codigoprod').dataset.idprod?.trim() || '';
    const presContiene = document.getElementById('codigoprod').dataset.prescontiene?.trim() || '';
    const producto = document.getElementById('producto').value.trim();
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const precioUnitario = parseFloat(document.getElementById('precio_unitario').value) || 0;
    const impuestoProd = Number(document.getElementById('isv').value.replace('%', '')) || 0;
    const totalp = cantidad * precioUnitario;
    const errorDiv = document.getElementById('error');
    const tabla = document.getElementById('tabla_productos').querySelector('tbody');
    const filas = tabla.querySelectorAll('tr');

    if (!codigo || !producto || cantidad <= 0 || precioUnitario <= 0) {
        errorDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Debe completar todos los campos con valores válidos.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
        return;
    }

    try {
        let productoEncontrado = false;

        if (NoagregarProdsAFila) {
            for (const fila of filas) {
                const codigoFila = fila.querySelector('td input[name^="codigo"]').value;

                if (codigoFila === codigo) {
                    const spanCantidad = fila.querySelector('td span[id^="cant"]');
                    const inputCantidad = fila.querySelector('td input[name^="cantidad"]');
                    const spanTotal = fila.querySelector('td span[id^="total"]');
                    const inputTotal = fila.querySelector('td input[name^="total"]');

                    const cantidadActual = parseFloat(spanCantidad.textContent);
                    const nuevaCantidad = cantidadActual + cantidad;

                    try {
                        const cant = await obtenerExistencias(codigo);

                        if (nuevaCantidad > cant) {
                            errorDiv.innerHTML = `
                                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                                    <strong>La cantidad ingresada excede las existencias disponibles (${cant}).</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`;
                            document.getElementById('cantidad').focus();
                            return;
                        }

                        const nuevoTotal = nuevaCantidad * precioUnitario;
                        spanCantidad.textContent = nuevaCantidad;
                        inputCantidad.value = nuevaCantidad;
                        spanTotal.textContent = nuevoTotal.toFixed(2);
                        inputTotal.value = nuevoTotal.toFixed(2);

                        productoEncontrado = true;
                        break;
                    } catch (error) {
                        console.error('Error:', error.message);
                    }
                }
            }
        }

        async function obtenerExistencias(codigo) {
            const responseCant = await fetch(`/verificar-existencias-productos/${codigo}`);
            console.log(`/verificar-existencias-productos/${codigo}`);

            if (!responseCant.ok) {
                throw new Error('Error en la verificación de existencias.');
            }

            const data = await responseCant.json();
            return parseInt(data);
        }
        if (!productoEncontrado) {
            contadorFilas++;
            const fila = document.createElement('tr');
            fila.setAttribute('data-index', contadorFilas);
            fila.innerHTML = `
                <td data-isv="${impuestoProd}">
                    <input type="hidden" name="codigo[${contadorFilas}]" value="${codigo}">
                    <input type="hidden" name="isv[${contadorFilas}]" value="${impuestoProd}">
                    <input type="hidden" name="prodid[${contadorFilas}]" value="${prodId}">
                    <input type="hidden" name="presContiene[${contadorFilas}]" value="${presContiene}">
                    ${codigo}
                </td>
                <td>
                    <input type="hidden" name="producto[${contadorFilas}]" value="${producto}">
                    ${producto}
                </td>
                <td>
                    <span id="cant[${contadorFilas}]">${cantidad}</span>
                    <input type="hidden" name="cantidad[${contadorFilas}]" value="${cantidad}">
                </td>
                <td>
                    <span id="precio[${contadorFilas}]">${precioUnitario.toFixed(2)}</span>
                    <input type="hidden" name="precio_unitario[${contadorFilas}]" value="${precioUnitario.toFixed(2)}">
                </td>
                <td>
                    <span id="descuento[${contadorFilas}]">0</span>
                    <input type="hidden" name="descuento_prod[${contadorFilas}]" value="0">
                </td>
                <td>
                    <span id="total[${contadorFilas}]" class="total">${totalp.toFixed(2)}</span>
                    <input type="hidden" name="total[${contadorFilas}]" value="${totalp.toFixed(2)}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-producto">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-warning text-light btn-sm editar-producto"
                        data-nombre="${producto}"
                        data-index="${contadorFilas}"
                        data-cantidad="${cantidad}"
                        data-precio="${precioUnitario.toFixed(2)}"
                        data-total="${totalp.toFixed(2)}">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                </td>`;
            tabla.appendChild(fila);
        }

        // Limpiar campos
        document.getElementById('codigoprod').value = '';
        document.getElementById('producto').value = '';
        document.getElementById('cantidad').value = '';
        document.getElementById('precio_unitario').value = '';
        document.getElementById('mysearch').focus();
        presentacion.value = '';
        isv.value = '';
        txttotal.value = '';
        totalizarFactura();

    } catch (error) {
        console.error('Error al agregar producto:', error);
        errorDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>No se pudo agregar el producto. Intente nuevamente.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
    }
}

document.getElementById('modalCantidad').addEventListener('input', function () {
    let _cantDisponible = 0;
    const errorDivModal = document.getElementById('errorModal');
    const codigo = document.getElementById('modalProd').dataset.code.trim();
    const cantidad = parseFloat(document.getElementById('modalCantidad').value) || 0;
    const guardarBtn = document.getElementById('guardarCambios');

    // Deshabilitar el botón mientras se verifica la cantidad
    guardarBtn.disabled = true;

    function verificarExistenciasProducto(code) {
        return fetch(`/verificar-existencias-productos/${code}`)
            .then(responseCant => {
                if (!responseCant.ok) {
                    throw new Error('Error en la verificación de existencias.');
                }
                return responseCant.json();
            })
            .then(data => {
                const cant = parseInt(data);
                return cant;
            })
            .catch(error => {
                console.error('Error:', error.message);
                throw error;
            });
    }

    verificarExistenciasProducto(codigo).then(cantidadDispo => {
        _cantDisponible = cantidadDispo;

        if (cantidad > _cantDisponible) {
            errorDivModal.innerHTML = `
                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                    <strong>La cantidad ingresada es mayor que la disponible (${_cantDisponible}).</strong>
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
            guardarBtn.disabled = true; // Deshabilitar el botón si la cantidad es mayor
        } else {
            errorDivModal.innerHTML = '';
            guardarBtn.disabled = false; // Habilitar el botón si la cantidad es válida
        }
    });
});


document.getElementById('editarModal').addEventListener('input', function () {
    let precio = parseFloat(document.getElementById('modalPrecio').value.trim()) || 0;
    let cantidad = parseFloat(document.getElementById('modalCantidad').value.trim()) || 0;
    let descuento = parseFloat(document.getElementById('modalDescuento').value.trim()) || 0;

    let total = (cantidad * precio) - descuento;

    document.getElementById('modalTotal').value = total.toFixed(2);
});

document.addEventListener("DOMContentLoaded", function () {
    const modalCantidad = document.getElementById("modalCantidad");
    const modalPrecio = document.getElementById("modalPrecio");
    const modalDescuento = document.getElementById("modalDescuento");
    const modalDescuentoPorcentaje = document.getElementById("modalDescuentoPorcentaje");
    const modalTotal = document.getElementById("modalTotal");

    function actualizarDescuento() {
        const cantidad = parseFloat(modalCantidad.value) || 1;
        const precio = parseFloat(modalPrecio.value) || 0;
        const subtotal = cantidad * precio;
        const descuento = parseFloat(modalDescuento.value) || 0;

        if (descuento < 0) {
            modalDescuento.value = ""; // Limpia si el descuento es negativo
            return;
        }

        if (descuento > subtotal) {
            modalDescuento.value = subtotal; // Limita el descuento al subtotal
        }

        const porcentaje = subtotal === 0 ? 0 : ((descuento / subtotal) * 100).toFixed(2);
        modalDescuentoPorcentaje.value = descuento === 0 ? "" : Math.min(porcentaje, 100); // Máximo 100%
        actualizarTotal();
    }

    function actualizarPorcentaje() {
        const cantidad = parseFloat(modalCantidad.value) || 1;
        const precio = parseFloat(modalPrecio.value) || 0;
        const subtotal = cantidad * precio;
        const porcentaje = parseFloat(modalDescuentoPorcentaje.value) || 0;

        if (porcentaje < 0) {
            modalDescuentoPorcentaje.value = ""; // Limpia si el porcentaje es negativo
            return;
        }

        if (porcentaje > 100) {
            modalDescuentoPorcentaje.value = 100; // Máximo 100%
        }

        const descuento = subtotal === 0 ? 0 : ((porcentaje / 100) * subtotal).toFixed(2);
        modalDescuento.value = porcentaje === 0 ? "" : descuento;
        actualizarTotal();
        actualizarDescuento();
    }

    function actualizarTotal() {
        const cantidad = parseFloat(modalCantidad.value) || 1;
        const precio = parseFloat(modalPrecio.value) || 0;
        const subtotal = cantidad * precio;
        const descuento = parseFloat(modalDescuento.value) || 0;

        const total = (subtotal - descuento).toFixed(2);
        modalTotal.value = total >= 0 ? total : 0;
    }

    modalCantidad.addEventListener("input", () => {
        actualizarDescuento();
        actualizarTotal();
    });

    modalPrecio.addEventListener("input", () => {
        actualizarDescuento();
        actualizarTotal();
    });

    modalDescuento.addEventListener("input", () => {
        actualizarDescuento();
    });

    modalDescuentoPorcentaje.addEventListener("input", () => {
        actualizarPorcentaje();
    });
});
