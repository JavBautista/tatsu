<template>
    <div class="container">
        <div class="container-fluid">
            <!--Card-->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Personal
                    <button type="button" @click="abrirModal('personal','registrar')" class="btn btn-primary float-rigth">
                        <i class="bi bi-plus-circle"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="description">Descripción</option>
                            </select>
                            <input type="text" v-model="buscar" class="form-control" placeholder="Texto a buscar" @keyup.enter="loadPersonal(1,buscar,criterio)">
                            <button type="submit" @click="loadPersonal(1,buscar,criterio)" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha engreso</th>
                                    <th>Fecha salida</th>
                                    <th>Observaciones</th>
                                    <th>OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="personal in arrayPersonal" :key="personal.id">
                                    <td v-text="personal.name"></td>
                                    <td v-text="personal.address"></td>
                                    <td v-text="personal.movil"></td>
                                    <td v-text="personal.mail"></td>
                                    <td v-text="personal.date_admission"></td>
                                    <td v-text="personal.date_termination"></td>
                                    <td v-text="personal.observations"></td>
                                    <td>
                                        <span v-if="personal.active" class="badge bg-success">Activo</span>
                                        <span v-else class="badge bg-danger">Baja</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info" @click="abrirModal('personal','actualizar_datos', personal)" title="Editar"><i class="bi bi-pencil-square"></i></button>

                                        <button v-if="personal.active" type="button" class="btn btn-outline-warning" @click="editInactive(personal.id)" title="Desactivar"> <i class="bi bi-hand-thumbs-down"></i></button>

                                        <button v-else type="button" class="btn btn-outline-secondary" @click="editActive(personal.id)" title="Activar"> <i class="bi bi-hand-thumbs-up"></i></button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>                    
                        <nav>
                            <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page-1,buscar,criterio)">Ant</a>
                            </li>

                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active':'']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>

                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1,buscar,criterio)">Sig</a>
                            </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!--./Card Productos-->
        </div>
        <!--.container-->
        <!--Modal-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar':modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form v-on:submit.prevent action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div v-show="error" class="form-group row div-error">
                                <div class="container-fluid">
                                    <div class="alert alert-danger text-center">
                                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p><em><strong class="text text-danger">* Campos obligatorios</strong></em></p>
                            <!--tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==1 || tipoAccion==2">
                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="date">Fecha de ingreso</label>
                                    <datepicker v-model="date_admission"></datepicker>
                                </div>

                                <div class="form-group">
                                    <label for="name"><strong class="text text-danger">*</strong>Nombre</label>
                                    <input type="text" class="form-control" v-model="name" placeholder="Ingrese descripción" required>
                                </div>

                                <div class="form-group">
                                    <label for="address">Dirección</label>
                                    <input type="text" class="form-control" v-model="address" placeholder="Ingrese descripción" required>
                                </div>

                                <div class="form-group">
                                    <label for="movil">Teléfono</label>
                                    <input type="text" class="form-control" v-model="movil" placeholder="Ingrese descripción" required>
                                </div>

                                <div class="form-group">
                                    <label for="mail">Email</label>
                                    <input type="text" class="form-control" v-model="mail" placeholder="Ingrese descripción" required>
                                </div>

                                <div class="form-group">
                                    <label for="observations">Observaciones</label>
                                    <input type="text" class="form-control" v-model="observations" placeholder="Ingrese descripción" required>
                                </div>

                            </div>
                            <!--./tipoAccion==1 o 2: Agregar o ACtualizar-->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDatos()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--.Modal-->
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker'
    import { es } from 'vuejs-datepicker/dist/locale'
    import moment from "moment";
    export default {
        components: {
            Datepicker
        },
        data(){
            return {
              
              arrayPersonal:[],
              pagination:{
                  'total':0,
                  'current_page':0,
                  'per_page':0,
                  'last_page':0,
                  'from':0,
                  'to':0
              },
              offset:3,
              criterio:'description',
              buscar:'',

              personal_id:0,

              name:'',
              address:'',
              movil:'',
              mail:'',
              date_admission:'',
              observations:'',

              errors:[],

              modal:0,
              tituloModal:'',
              tipoAccion:0,
              error:0,
              errorMostrarMsj:[],
            }
        },
        computed:{            
           isActived: function(){
            return this.pagination.current_page;
           },
           //Calcula los elementos de la paginacion
           pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if(from <1){
                    from=1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to ){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
           }
        },
        methods:{           
            loadPersonal(page,buscar,criterio){
                let me=this;
                var url = '/personal/get?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response){
                    let respuesta = response.data;
                    me.arrayPersonal = respuesta.employee.data;
                    me.pagination = respuesta.pagination;
                  })
                  .catch(function (error) {
                    // handle error
                    console.log(error);
                  })
                  .finally(function () {
                    // always executed
                  });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.loadPersonal(page,buscar,criterio);
            },
            validarDatos(){
                this.error=0;
                this.errorMostrarMsj=[];
                if(!this.name) this.errorMostrarMsj.push('El valor nombre no puede estar vacio.');
                if(this.errorMostrarMsj.length) this.error=1;
                return this.error;
            },
            registrar(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let formatted_date = moment(me.date_admission).format('YYYY-MM-DD')
                axios.post('/personal/store',{
                  'name': me.name,
                  'address': me.address,
                  'movil': me.movil,
                  'mail': me.mail,
                  'date_admission': formatted_date,
                  'observations': me.observations,

                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.loadPersonal(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },           
            actualizarDatos(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let formatted_date = moment(me.date_admission).format('YYYY-MM-DD')
                axios.put('/personal/update',{
                  'id':me.personal_id,
                  'name':me.name,
                  'address':me.address,
                  'movil':me.movil,
                  'mail':me.mail,
                  'date_admission':formatted_date,
                  'observations':me.observations,
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.loadPersonal(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },

            editActive(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                  title: '¿Desea cambiar a activo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me=this;
                    axios.put('/personal/active',{
                        'id': id
                    }).then(function (response){
                        me.loadPersonal(me.pagination.current_page,me.buscar,me.criterio);
                        swalWithBootstrapButtons.fire(
                          'Activo',
                          'El registro ha sido actualizado con exito.',
                          'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                    });

                  }
                })
            },
            editInactive(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                  title: '¿Desea cambiar a inactivo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {
                    let me=this;
                    axios.put('/personal/inactive',{
                        'id': id
                    }).then(function (response){
                        me.loadPersonal(me.pagination.current_page,me.buscar,me.criterio);
                        swalWithBootstrapButtons.fire(
                          'Inactivo',
                          'El registro ha sido actualizado con exito.',
                          'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                    });
                  }
                })
            },
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "personal":{
                        switch(accion){
                            case 'registrar':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar';
                                this.active=0;
                                this.name='';
                                this.address='';
                                this.movil='';
                                this.mail='';
                                this.date_admission='';
                                this.observations='';
                                break;
                            }
                            case 'actualizar_datos':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar';
                                this.personal_id= data['id'];
                                this.name= data['name'];
                                this.address= data['address'];
                                this.movil= data['movil'];
                                this.mail= data['mail'];
                                this.date_admission= data['date_admission'];
                                this.observations= data['observations'];
                                break;
                            }
                        }
                    }
                }
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            },
        },
        mounted() {
            this.loadPersonal(1,'','description');
        }
    }
</script>


<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
        overflow: scroll;
    }

    .div-error{
        display: flex;
        justify-content: center;
    }

    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
