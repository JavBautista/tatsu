<template>
    <div class="container">
        <div class="container-fluid">
            <!--Card-->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Gastos Fijos
                    <button type="button" @click="abrirModal('gasto','registrar')" class="btn btn-primary float-rigth">
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
                            <input type="text" v-model="buscar" class="form-control" placeholder="Texto a buscar" @keyup.enter="loadFixedExpenses(1,buscar,criterio)">
                            <button type="submit" @click="loadFixedExpenses(1,buscar,criterio)" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>DESCRIPCIÓN</th>
                                    <th>MONTO</th>
                                    <th>DÍA DE CORTE</th>
                                    <th>ESTATUS</th>
                                    <th>OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="gasto_fijo in arrayFixedExpenses" :key="gasto_fijo.id">
                                    <td v-text="gasto_fijo.description"></td>
                                    <td v-text="gasto_fijo.amount"></td>
                                    <td v-text="gasto_fijo.cutoff"></td>
                                    <td>
                                        <span v-if="gasto_fijo.active" class="badge bg-success">Activo</span>
                                        <span v-else class="badge bg-danger">Baja</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info" @click="abrirModal('gasto','actualizar_datos', gasto_fijo)" title="Editar"><i class="bi bi-pencil-square"></i></button>

                                        <button v-if="gasto_fijo.active" type="button" class="btn btn-outline-warning" @click="editInactive(gasto_fijo.id)" title="Desactivar"> <i class="bi bi-hand-thumbs-down"></i></button>

                                        <button v-else type="button" class="btn btn-outline-secondary" @click="editActive(gasto_fijo.id)" title="Activar"> <i class="bi bi-hand-thumbs-up"></i></button>
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
                            <!--<div v-else>-->
                                <div class="form-group">
                                    <label for="description"><strong class="text text-danger">*</strong>Descripcion</label>
                                    <input type="text" class="form-control" v-model="description" placeholder="Ingrese descripción" required>
                                </div>

                                <div class="form-group">
                                    <label for="amount"><strong class="text text-danger">*</strong>Monto</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="amount" placeholder="0.00">
                                </div>

                                <div class="form-group">
                                    <label for="cutoff"><strong class="text text-danger">*</strong>Día de corte</label>
                                    <select class="form-select" v-model="cutoff">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                    </select>
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
              
              arrayFixedExpenses:[],
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

              fixed_expense_id:0,
              active:0,
              description:'',
              amount:0,
              cutoff:1,

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
            loadFixedExpenses(page,buscar,criterio){
                let me=this;
                var url = '/gastos-fijos/get?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response){
                    let respuesta = response.data;
                    me.arrayFixedExpenses = respuesta.fixed_expenses.data;
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
                me.loadFixedExpenses(page,buscar,criterio);
            },
            validarDatos(){
                this.error=0;
                this.errorMostrarMsj=[];
                if(!this.description) this.errorMostrarMsj.push('El valor descripcion no puede estar vacio.');
                if(this.errorMostrarMsj.length) this.error=1;
                return this.error;
            },
            registrar(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                axios.post('/gastos-fijos/store',{
                  'description':me.description,
                  'amount':me.amount,
                  'cutoff':me.cutoff
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.loadFixedExpenses(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },           
            actualizarDatos(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                axios.put('/gastos-fijos/update',{
                  'id':me.fixed_expense_id,
                  'description':me.description,
                  'amount':me.amount,
                  'cutoff':me.cutoff
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.loadFixedExpenses(me.pagination.current_page,me.buscar,me.criterio)
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
                    axios.put('/gastos-fijos/active',{
                        'id': id
                    }).then(function (response){
                        me.loadFixedExpenses(me.pagination.current_page,me.buscar,me.criterio);
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
                    axios.put('/gastos-fijos/inactive',{
                        'id': id
                    }).then(function (response){
                        me.loadFixedExpenses(me.pagination.current_page,me.buscar,me.criterio);
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
                    case "gasto":{
                        switch(accion){
                            case 'registrar':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar';
                                this.active=0;
                                this.description='';
                                this.amount=0;
                                this.cutoff=1;
                                break;
                            }
                            case 'actualizar_datos':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar';
                                this.fixed_expense_id     = data['id'];
                                this.description = data['description'];
                                this.amount = data['amount'];
                                this.cutoff = data['cutoff'];
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
            this.loadFixedExpenses(1,'','description');
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
