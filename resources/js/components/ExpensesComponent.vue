<template>
    <div class="container">
        <div class="container-fluid">
            <!--Card Productos-->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cortes
                    <button type="button" @click="abrirModal('expense','registrar')" class="btn btn-primary float-rigth">
                        <i class="bi bi-plus-circle"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="description">Descripción</option>
                                <option value="cost">Costo</option>
                                <option value="date">Fecha</option>
                                <option value="person">Persona</option>
                            </select>
                            <input type="text" v-model="buscar" class="form-control" placeholder="Texto a buscar" @keyup.enter="cargarGastos(1,buscar,criterio)">
                            <button type="submit" @click="cargarGastos(1,buscar,criterio)" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Concept</th>
                                    <th>Caja</th>
                                    <th>Persona</th>
                                    <th>Evidencia</th>
                                    <th>Costo</th>
                                    <th>Facturado</th>
                                    <th># Ref.Fac.</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="expense in arrayExpenses" :key="expense.id">
                                    <td v-text="expense.date"></td>
                                    <td v-text="expense.description"></td>
                                    <td v-text="expense.till"></td>
                                    <td v-text="expense.person"></td>
                                    <td v-text="expense.evidence"></td>
                                    <td v-text="'$'+expense.cost"></td>
                                    <td>
                                        <span v-if="expense.billing" class="badge bg-info">Facturado</span>
                                        <span v-else class="badge bg-secondary">Sin Facturar</span>
                                    </td>
                                    <td v-tect="expense.billing_reference"></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" @click="abrirModal('expense','actualizar_datos', expense)"> <i class="bi bi-pencil-square"></i> </button>

                                        <button type="button" class="btn btn-sm btn-warning" @click="abrirModal('expense','actualizar_datos_facturacion', expense)" title="Act. Factura"><i class="bi bi-receipt-cutoff"></i></button>
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
                            <div v-show="errorExpense" class="form-group row div-error">
                                <div class="container-fluid">
                                    <div class="alert alert-danger text-center">
                                        <div v-for="error in eerrorMostrarMsjExpense" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p><em><strong class="text text-danger">* Campos obligatorios</strong></em></p>
                            <!--tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==1 || tipoAccion==2">
                            <!--<div v-else>-->
                                
                                <div v-if="tipoAccion==1"  class="form-group">
                                    <label for="date"><strong class="text text-danger">*</strong>Fecha del gasto</label>
                                    <datepicker v-model="date"></datepicker>
                                </div>

                                <div class="form-group">
                                    <label for="description"><strong class="text text-danger">*</strong>Concepto</label>
                                    <input  type="text" class="form-control"
                                            v-model="description"
                                            placeholder="Ingrese concepto de gasto"

                                            required>
                                </div>

                                <div class="form-group">
                                    <label for="cost"><strong class="text text-danger">*</strong>Costo</label>
                                    <input type="number" min="0" step="1" max="99999999.99" class="form-control" v-model="cost" placeholder="0.00">
                                </div>

                                <div class="form-group">
                                    <label for="person">Persona</label>
                                    <input type="text" class="form-control" v-model="person"  placeholder="Persona que realizó el gasto.">
                                </div>
                                <div class="form-group">
                                    <label for="evidence">Evidencia</label>
                                    <input type="text" class="form-control" v-model="evidence"  placeholder="Ingrese evidencia">
                                </div>

                                <div class="form-group form-check">
                                    <input class="form-check-input" type="checkbox" value="" v-model="till">
                                    <label class="form-check-label" for="till">
                                        ¿Gasto de caja?
                                    </label>
                                </div>
                            </div>
                            <!--./tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==3">
                                <div class="form-group form-check">
                                    <input class="form-check-input" type="checkbox" value="" v-model="billing">
                                    <label class="form-check-label" for="billing">
                                        Facturado
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="billing_reference">Referencia de factura</label>
                                    <input type="text" class="form-control" v-model="billing_reference"  placeholder="Ingrese folio, número o referencia de la factura. ">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDatos()">Actualizar</button>
                        <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="actualizarDatosFacturacion()">Actualizar</button>
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
              
              arrayExpenses:[],
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

              expense_id:0,              
              description:'',
              cost:0,
              till:0,
              billing:0,
              billing_reference:'',
              person:'',
              evidence:'',
              date:'',

              errors:[],

              modal:0,
              tituloModal:'',
              tipoAccion:0,
              errorExpense:0,
              eerrorMostrarMsjExpense:[],
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
            cargarGastos(page,buscar,criterio){
                let me=this;
                var url = '/gastos/get?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.arrayExpenses = respuesta.expenses.data;
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
                me.cargarGastos(page,buscar,criterio);
            },
            validarDatos(){
                this.errorExpense=0;
                this.eerrorMostrarMsjExpense=[];
                if(!this.date) this.eerrorMostrarMsjExpense.push('El valor fecha no puede estar vacio.');
                if(!this.description) this.eerrorMostrarMsjExpense.push('El valor descripcion no puede estar vacio.');
                if(!this.cost) this.eerrorMostrarMsjExpense.push('El valor costo no puede estar vacio.');
                if(this.eerrorMostrarMsjExpense.length) this.errorExpense=1;
                return this.errorExpense;
            },
            registrar(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let f1 = moment(me.date).format('YYYY-MM-DD');
                axios.post('/gastos/store',{
                  'description':me.description,
                  'cost':me.cost,
                  'till':me.till,
                  'billing':me.billing,
                  'billing_reference':me.billing_reference,
                  'person':me.person,
                  'evidence':me.evidence,
                  'date':f1

                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarGastos(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },           
            actualizarDatos(){

                let me=this;
                axios.put('/gastos/update-info',{
                  'id':me.expense_id,
                  'description':me.description,
                  'cost':me.cost,
                  'till':me.till,
                  'person':me.person,
                  'evidence':me.evidence
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarGastos(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarDatosFacturacion(){

                let me=this;
                axios.put('/gastos/update-factura',{
                  'id':me.expense_id,
                  'billing':me.billing,
                  'billing_reference':me.billing_reference
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarGastos(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "expense":{
                        switch(accion){
                            case 'registrar':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar';

                                this.description='';
                                this.cost=0;
                                this.till=0;
                                this.billing=0;
                                this.billing_reference='';
                                this.person ='';
                                this.evidence ='';
                                this.date='';

                                break;
                            }
                            case 'actualizar_datos':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar';

                                this.expense_id= data['id'];
                                
                                this.description = data['description'];
                                this.cost = data['cost'];
                                this.till = data['till'];
                                this.billing = data['billing'];
                                this.billing_reference = data['billing_reference'];
                                this.person = data['person'];
                                this.evidence  = data['evidence'];
                                this.date  = data['date'];
                                console.log(data['date']);
                                break;
                            }
                            case 'actualizar_datos_facturacion':{
                                this.modal=1;
                                this.tipoAccion =3;
                                this.tituloModal='Actualizar datos facturación';

                                this.expense_id= data['id'];

                                this.description = data['description'];
                                this.cost = data['cost'];
                                this.till = data['till'];
                                this.billing = data['billing'];
                                this.billing_reference = data['billing_reference'];
                                this.person = data['person'];
                                this.evidence  = data['evidence'];
                                this.date  = data['date'];
                                console.log(data['date']);
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
            console.log('Expense component mounted.')
            this.cargarGastos(1,'','description');
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
