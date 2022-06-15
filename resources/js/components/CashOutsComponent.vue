<template>
    <div class="container">
        <div class="container-fluid">
            <!--Card Productos-->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cortes
                    <button type="button" @click="abrirModal('product','registrar')" class="btn btn-primary float-rigth">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="name">Nombre</option>
                                <option value="description">Descripción</option>
                            </select>
                            <input type="text" v-model="buscar" class="form-control" placeholder="Texto a buscar" @keyup.enter="cargarCortes(1,buscar,criterio)">
                            <button type="submit" @click="cargarCortes(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>GASTOS</th>
                                    <th>NOTAS</th>
                                    <th>TARJETA</th>
                                    <th>TARJETA PROPINA</th>
                                    <th>NETO</th>
                                    <th>EFECTIVO</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cashout in arrayCortes" :key="cashout.id">
                                    <td v-text="cashout.date"></td>
                                    <td v-text="'$'+cashout.expenses"></td>
                                    <td v-text="'$'+cashout.sales"></td>
                                    <td v-text="'$'+cashout.card_sales"></td>
                                    <td v-text="'$'+cashout.card_tips"></td>
                                    <td> <strong v-text="'$'+cashout.total"></strong></td>
                                    <td> <strong v-text="'$'+cashout.cash"></strong></td>
                                    <td>
                                        <button type="button" class="btn btn-info" @click="abrirModal('product','actualizar_datos', cashout)">Editar</button>                                        
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
                            <div v-show="errorCorte" class="form-group row div-error">
                                <div class="container-fluid">
                                    <div class="alert alert-danger text-center">
                                        <div v-for="error in eerrorMostrarMsjCorte" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p><em><strong class="text text-danger">* Campos obligatorios</strong></em></p>
                            <!--tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==1 || tipoAccion==2">
                            <!--<div v-else>-->
                                
                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="date">Fecha del corte</label>
                                    <datepicker v-model="date"></datepicker>
                                </div>


                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="expenses">Gastos</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="expenses" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="sales"><strong class="text text-danger">*</strong>Ventas</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="sales" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="card_sales">Tarjeta</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="card_sales" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="card_tisp">Propina tarjeta</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="card_tips" placeholder="0.00">
                                </div>

                                <!--<div class="form-group">
                                    <label for="observarion">Comentario</label>
                                    <textarea class="form-control" v-model="observarion"  rows="3"></textarea>
                                </div>
                                -->
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
              
              arrayCortes:[],
              pagination:{
                  'total':0,
                  'current_page':0,
                  'per_page':0,
                  'last_page':0,
                  'from':0,
                  'to':0
              },
              offset:3,
              criterio:'name',
              buscar:'',

              corte_id:0,              
              date:null,
              expenses:0,
              sales:0,
              card_sales:0,
              card_tips:0,
              total:0,
              cash:0,
              observation:'',

              errors:[],

              modal:0,
              tituloModal:'',
              tipoAccion:0,
              errorCorte:0,
              eerrorMostrarMsjCorte:[],
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
            cargarCortes(page,buscar,criterio){
                let me=this;
                var url = '/cortes/get?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.arrayCortes = respuesta.cash_outs.data;
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
                me.cargarCortes(page,buscar,criterio);
            },
             validarDatos(){
                this.errorCorte=0;
                this.eerrorMostrarMsjCorte=[];
                if(!this.date) this.eerrorMostrarMsjCorte.push('El valor date no puede estar vacio.');
                if(!this.expenses) this.eerrorMostrarMsjCorte.push('El valor expenses no puede estar vacio.');
                if(!this.sales) this.eerrorMostrarMsjCorte.push('El valor sales no puede estar vacio.');
                if(this.eerrorMostrarMsjCorte.length) this.errorCorte=1;
                return this.errorCorte;
            },
            registrar(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let f1 = moment(me.date).format('YYYY-MM-DD')
                axios.post('/cortes/store',{
                  'date':f1,
                  'expenses':me.expenses,
                  'sales':me.sales,
                  'card_sales':me.card_sales,
                  'card_tips':me.card_tips,
                  //'observation':me.observation

                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarCortes(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },           
            actualizarDatos(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                axios.put('/cortes/update',{
                  'cash_out_id':me.corte_id,               
                  'date':me.date,
                  'expenses':me.expenses,
                  'sales':me.sales,
                  'card_sales':me.card_sales,
                  'card_tips':me.card_tips,
                  //'observation':me.observation

                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarCortes(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "product":{
                        switch(accion){
                            case 'registrar':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar';

                                this.corte=0;
                                this.date='';
                                this.expenses=0;
                                this.sales =0;
                                this.card_sales =0;
                                this.card_tips=0;
                                this.observation='';


                                break;
                            }
                            case 'actualizar_datos':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar';

                                this.corte_id= data['id'];
                                
                                this.date     = data['date'];
                                this.expenses = data['expenses'];
                                this.sales = data['sales'];
                                this.card_sales = data['card_sales'];
                                this.card_tips  = data['card_tips'];
                                this.observation  = '';
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
            console.log('CashOuts component mounted.')
            this.cargarCortes(1,'','name');
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
