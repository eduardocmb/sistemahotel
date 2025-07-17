export class search {
    constructor(myurlp, mysearchp, ul_add_lip) {
        this.url = myurlp;
        this.mysearch = mysearchp;
        this.ul_add_li = ul_add_lip;
        this.idli = "mylist";
        this.pcantidad = document.getElementById("cantidad");
        this.pisv = document.getElementById('isv');
        this.ppresentacion = document.getElementById('presentacion');
        this.pprecio = document.getElementById('precio_unitario');
        this.pcodigo = document.getElementById('codigoprod');
        this.pproducto = document.getElementById('producto');
    }
    InputSearch() {
        this.mysearch.addEventListener("input", (e) => {
            e.preventDefault();
            try {
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let minimo_letras = 1;
                let valor = this.mysearch.value;

                if (valor.length >= minimo_letras) {
                    let datasearch = new FormData();
                    datasearch.append("valor", valor);
                    fetch(this.url, {
                        headers: { "X-CSRF-TOKEN": token },
                        method: "POST",
                        body: datasearch,
                    }).then((data) => data.json())
                        .then((data) => {
                            console.log(data);
                            this.Showlist(data, valor);
                        }).catch(function (error) {
                            console.error(error);
                        });
                } else {
                    this.ul_add_li.style.display = "none";
                }

            } catch (error) {
                console.log(error);
            }
        });

        this.mysearch.addEventListener("keydown", (e) => {
            const selected = this.ul_add_li.querySelector(".selected");
            const allItems = Array.from(this.ul_add_li.querySelectorAll("li"));

            if (e.key === "ArrowDown") {
                e.preventDefault();
                if (selected) {
                    const next = selected.nextElementSibling || allItems[0];
                    selected.classList.remove("selected");
                    next.classList.add("selected");
                } else if (allItems.length > 0) {
                    allItems[0].classList.add("selected");
                }
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                if (selected) {
                    const prev = selected.previousElementSibling || allItems[allItems.length - 1];
                    selected.classList.remove("selected");
                    prev.classList.add("selected");
                } else if (allItems.length > 0) {
                    allItems[allItems.length - 1].classList.add("selected");
                }
            } else if (e.key === "Enter") {
                e.preventDefault();
                if (selected) {
                    selected.click();
                }
            }
        });
    }

    Showlist(data, valor) {
        this.ul_add_li.style.display = "block";
        if (data.estado == 1) {
            if (data.result != "") {
                let arrayp = data.result;
                this.ul_add_li.innerHTML = "";
                let n = 0;
                this.Show_list_each_data(arrayp, valor, n);
                let adclasli = document.getElementById('1' + this.idli);
                adclasli.classList.add("selected");
            } else {
                this.ul_add_li.innerHTML = "";
                this.ul_add_li.innerHTML += `
                    <p style="color:red;"><br>No se encontró un producto con ese nombre o código.</p>
                `;
            }
        }
    }

    Show_list_each_data(arrayp, valor, n) {
        for (let item of arrayp) {
            n++;

            let nombre = item.nombre;

            let li = document.createElement("li");
            li.id = `${n + this.idli}`;
            li.value = item.nombre;
            li.classList.add("list-group-item");

            li.innerHTML = `
                <div class="d-flex flex-row">
                    <div class="p-2">
                       <span>${item.codigo} - </span> <strong data-presentacionporpum="${item.contiene}" data-idprod="${item.id}" data-prod="${item.nombre}" data-isv="${item.isv}" data-presentacion="${item.unidad_nombre == null ? 'NA':item.unidad_nombre}/${item.contiene == null ? 'NA':item.contiene}" data-precio="${item.precio_venta}" data-codigo = "${item.codigo}"> ${nombre.substr(0, valor.length)}</strong>${nombre.substr(valor.length)}
                        <p class="card-text">P. venta: ${item.precio_venta} Lps.</p>
                    </div>
                </div>
            `;

            li.addEventListener("click", () => {
                const strongElement = li.querySelector("strong");
                fetch(`verificar-existencias-productos/${strongElement.dataset.codigo}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('error').innerHTML = '';
                        if (parseInt(data) <= 0) {
                            document.getElementById('error').innerHTML = `
                                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                                    <strong>No hay unidades de ${strongElement.dataset.prod} en el inventario.</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            return;
                        } else {
                            const presContiene = strongElement.dataset.presentacionporpum;
                            const prodId = strongElement.dataset.idprod;
                            const precio = strongElement.dataset.precio;
                            const codigo = strongElement.dataset.codigo;
                            const isv = strongElement.dataset.isv;
                            const presentacion = strongElement.dataset.presentacion;
                            const producto = strongElement.dataset.prod;

                            this.pcodigo.value = codigo;
                            this.pcodigo.setAttribute('data-idprod', prodId);
                            this.pcodigo.setAttribute('data-prescontiene', presContiene);
                            this.pcantidad.value = 1;
                            this.pisv.value = String(isv) + "%";
                            this.ppresentacion.value = presentacion;
                            this.pproducto.value = producto;
                            this.pprecio.value = precio;
                            this.pprecio.focus();
                            calcularTotal();
                        }
                    })

                this.ul_add_li.style.display = "none";

                this.mysearch.value = "";
            });

            this.ul_add_li.appendChild(li);
        }
    }
}

function calcularTotal() {
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const precioUnitario = parseFloat(document.getElementById('precio_unitario').value) || 0;
    const total = cantidad * precioUnitario;
    document.getElementById('totalP').value = total.toFixed(2);
}
