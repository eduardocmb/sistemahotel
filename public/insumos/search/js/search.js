import { search } from "./export_search.js";
const mysearchp = document.querySelector("#mysearch");
const ul_add_lip = document.querySelector("#showlist");
const myurlp = "/searchinsumos";
const searchproduct = new search(myurlp, mysearchp, ul_add_lip);
searchproduct.InputSearch();
const chk = document.getElementById('precios_impuestos');
const txtnumero = document.getElementById('numero');
const btnguardar = document.getElementById('btnguardar');

let contadorFilas = 0;

function agregarProducto() {
    const codigo = document.getElementById('codigo').value.trim();
    const prodId = document.getElementById('codigo').dataset.idprod.trim();
    const presContiene = document.getElementById('codigo').dataset.prescontiene.trim();
    const producto = document.getElementById('producto').value.trim();
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const precioUnitario = parseFloat(document.getElementById('precio_unitario').value) || 0;
    const presentacion = document.getElementById('presentacion');
    const isv = document.getElementById('isv');
    const impuestoProd = Number(isv.value.replace('%', '')) || 0;
    const totalp = cantidad * precioUnitario;
    const txttotal = document.getElementById('totalP');

    if (codigo && producto && cantidad > 0 && precioUnitario > 0) {
        document.getElementById('error').innerHTML = "";

        contadorFilas++;

        const tabla = document.getElementById('tabla_productos').querySelector('tbody');
        const fila = document.createElement('tr');

        fila.setAttribute('data-index', contadorFilas); // Agrega un índice único a cada fila
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
        </td>
    `;


        tabla.appendChild(fila);

        // Actualiza los totales
        actualizarTotales();

        // Limpia los campos
        document.getElementById('codigo').value = '';
        document.getElementById('producto').value = '';
        document.getElementById('cantidad').value = '';
        document.getElementById('precio_unitario').value = '';
        presentacion.value = '';
        isv.value = '';
        txttotal.value = '';
    } else {
        document.getElementById('error').innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Debe completar todos los campos.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
    }
}



// Agregar evento al botón de agregar producto
document.getElementById('agregar_producto').addEventListener('click', agregarProducto);

// Agregar evento de teclado (Enter) al campo de precio unitario
document.getElementById('precio_unitario').addEventListener('keydown', function (event) {
    if (event.key === "Enter") { // Enter
        agregarProducto();
    }
});

document.getElementById('tabla_productos').addEventListener('click', function (e) {
    if (e.target.classList.contains('eliminar-producto')) {
        e.target.closest('tr').remove();
        actualizarTotales();
    }
});

function actualizarTotales() {
    let subtotal = 0;
    let isv = 0;
    let total = 0;
    const filas = document.getElementById('tabla_productos').querySelectorAll('tbody tr');
    const chkincluyeIsv = document.getElementById('precios_impuestos').checked; // Checkbox para ISV

    filas.forEach(fila => {
        const totalp = parseFloat(fila.children[4].textContent) || 0;
        const isvProducto = parseFloat(fila.children[0].getAttribute('data-isv')) || 0;

        if (isvProducto > 0) {
            if (chkincluyeIsv) {
                isv += totalp - (totalp / (1 + (isvProducto / 100)));
                subtotal += totalp / (1 + (isvProducto / 100));
            } else {
                isv += totalp * (isvProducto / 100);
                subtotal += totalp;
            }
        } else {
            isv += 0;
            subtotal += totalp;
        }
    });

    total = subtotal + isv;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('impuesto').textContent = isv.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

// Validar entrada numérica
function validarNumero(event) {
    const input = event.target;
    const valor = input.value;

    // Permitir valores vacíos
    if (valor === "") {
        return;
    }

    // Si el valor es 0 o negativo, establecerlo en 1
    const valorNumerico = parseFloat(valor);
    if (isNaN(valorNumerico) || valorNumerico <= 0) {
        input.value = 1;
    }
}


const preciotxt = document.getElementById('precio_unitario');
const canttxt = document.getElementById('cantidad')
// Eventos
preciotxt.addEventListener("input", function(event) {
    const input = event.target;
    const valor = input.value;

    if (valor === "") {
        return;
    }

    if (valor === "0") {
        input.value = "0.";
        return;
    }
    const valorNumerico = parseFloat(valor);

    if (isNaN(valorNumerico) || valorNumerico <= 0) {
        input.value = 1;
    }
});

canttxt.addEventListener('input', validarNumero);

function calcularTotal() {
    const txttotal = document.getElementById('totalP');
    const cant = document.getElementById('cantidad').value === "" ? 0 : Number(document.getElementById('cantidad').value);
    const precio = document.getElementById('precio_unitario').value === "" ? 0 : Number(document.getElementById('precio_unitario').value);
    txttotal.value = cant * precio;
}

function AgregarCodigoAutomatico() {
    const key = 'CMIN';
    const table = 'correlativos';
    const prefix = '';

    const url = `/correlativos/get/${key}/${table}/${prefix}`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            txtnumero.value = data.codigo;
        })
        .catch(error => console.error('Error:', error));
    txtnumero.readOnly = true;
}
AgregarCodigoAutomatico();

document.getElementById('cantidad').addEventListener('input', calcularTotal);
document.getElementById('precio_unitario').addEventListener('input', calcularTotal);
chk.addEventListener('change', actualizarTotales);







