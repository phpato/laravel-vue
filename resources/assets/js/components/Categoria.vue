<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button @click="abrirModal('categoria','registrar')" type="button" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control"
                                    placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                    Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in categorias.data" :key="categoria.id">
                                    <td>
                                        <button @click="abrirModal('categoria','actualizar',categoria)" type="button"
                                            class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;

                                        <template v-if="categoria.condicion == 1">
                                            <button @click="desactivarCategoria( categoria.id)" type="button"
                                                class="btn btn-danger btn-sm">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-if="categoria.condicion == 0">
                                            <button @click="activarCategoria( categoria.id)" type="button"
                                                class="btn btn-info btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                        <div v-if="categoria.condicion == 1">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#">Ant</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            @click="cerrarModal()">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div v-if="errors.length" class="form-group">
                                <div class="alert alert-danger">
                                    <p>
                                        <b>Por favor corriga el(los) siguiente(s) error(es):</b>
                                        <ul>
                                            <li :key="error" v-for="error in errors">{{ error }}</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control"
                                        placeholder="Nombre de categoría">
                                    <span class="help-block">(*) Ingrese el nombre de la categoría</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="descripcion" class="form-control"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-secondary"
                            data-dismiss="modal">Cerrar</button>

                        <div v-if="tipoAccion == 1">
                            <button type="submit" @click="registrarCategoria($event)"
                                class="btn btn-primary">Guardar</button>
                        </div>
                        <div v-else>
                            <button type="submit" @click="actualizarCategoria($event)"
                                class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

    </main>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2'

    export default {

        data() {
            return {
                categoria_id: '',
                nombre: '',
                descripcion: '',
                categorias: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: '',
                errors: '',
                erroCategoria: '',
                mensajeCategorias: []
            }
        },
        methods: {
            ValidarFormulario(e) {
                if (this.nombre && this.descripcion) {
                    return true;
                }

                this.errors = [];

                if (!this.nombre) {
                    this.errors.push('El nombre es obligatorio.');
                }
                if (!this.descripcion) {
                    this.errors.push('Debe ingresar una descripción.');
                }

                e.preventDefault();
            },
            listarCategoria() {
                axios.get('/categoria').then((response) => {
                        //console.log("la response es: ",response.data);
                        this.categorias = response.data.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log("ocurrio un error: ", error);
                    });
            },
            cerrarModal() {
                this.errors = [];
                this.modal = 0;
                this.nombre = '';
                this.descripcion = '';
                this.tituloModal = '';

            },
            registrarCategoria($event) {


                let formularioValido = this.ValidarFormulario($event);
                if (formularioValido) {
                    let me = this;
                    axios.post('/categoria', {
                            nombre: this.nombre.toLowerCase(),
                            descripcion: this.descripcion.toLowerCase()
                        })
                        .then(function (response) {
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Categoria registrada exitosamente!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                me.cerrarModal();
                                me.listarCategoria();
                            })

                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else {
                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Se registraron errores al momento de actualizar',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }

            },
            actualizarCategoria($event) {


                let formularioValido = this.ValidarFormulario($event);
                if (formularioValido) {
                    let me = this;
                    axios.put(`/categoria/${me.categoria_id}`, {
                            categoria_id: me.categoria_id,
                            nombre: this.nombre.toLowerCase(),
                            descripcion: this.descripcion.toLowerCase()
                        })
                        .then(function (response) {
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Categoria registrada exitosamente!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                me.cerrarModal();
                                me.listarCategoria();
                            })

                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }

            },
            desactivarCategoria(id) {

                let me = this;
                Swal.fire({
                    title: '¿Está seguro de actiar la categoria?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: "Cancelar",
                    confirmButtonText: 'Eliminar'
                }).then((result) => {
                    if (result.value) {
                        axios.put(`/categoria/desactivar/${id}`)
                            .then(function (response) {
                                Swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Categoria desactivada exitosamente!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    me.listarCategoria();
                                })

                            })
                            .catch(function (error) {
                                console.log(error);
                            });

                    }
                })
            },
            activarCategoria(id) {

                let me = this;
                Swal.fire({
                    title: '¿Está seguro de activar la categoria?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: "Cancelar",
                    confirmButtonText: 'Eliminar'
                }).then((result) => {
                    if (result.value) {
                        axios.put(`/categoria/activar/${id}`)
                            .then(function (response) {
                                Swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Categoria activada exitosamente!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    me.listarCategoria();
                                })

                            })
                            .catch(function (error) {
                                console.log(error);
                            });

                    }
                })
            },
            //funcion para abrir modal
            abrirModal(modelo, accion, data = []) {
                //console.log("abriendo modal", modelo, accion)
                switch (modelo) {
                    case "categoria": {
                        //console.log("verificando ahora la ccion")
                        switch (accion) {
                            case "registrar": {
                                //console.log("abriendo modal y asignandole el titulo")
                                this.modal = 1;
                                this.tituloModal = 'Registrar Categoría'
                                this.nombre = '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }

                            case "actualizar": {

                                this.modal = 1;
                                this.tituloModal = 'Actualizar Categoría'
                                this.nombre = data["nombre"];
                                this.descripcion = data["descripcion"];
                                this.categoria_id = data["id"]
                                this.tipoAccion = 2;
                                break;

                            }
                            case "eliminar": {

                            }
                        }
                    }

                }

            }
        },
        mounted() {
            this.listarCategoria()
        }
    }

</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        width: 100%;
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

</style>
