<template>
    <div class="container">
        <form v-on:submit.prevent action="" method="post" enctype="multipart/form-data" class="row row-cols-lg-auto g-3 align-items-center">
            <div class="col-12">
                <label class="visually-hidden" for="fecha">FECHA</label>
                <div class="input-group">
                  <div class="input-group-text">FECHA</div>
                  <datepicker class="form-control" :disabledDates="disabledDates" v-model="fecha"></datepicker>
                </div>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-primary" @click="loadInfoPeriodo()">Filtrar</button>
            </div>
        </form>
        <hr>
        <template v-if="mostrar">
            <h3>Periodo {{fecha_ini }} al {{fecha_fin}}</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Nombre</th>
                            <th>Ingreso</th>
                            <th>Extra</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="personal in arrayPersonal" :key="personal.id">
                            <td>
                                <button v-if="!personal.payroll_id" type="button" class="btn btn-info" @click="abrirModal('personal','create', personal)" title="Registrar"><i class="bi bi-plus-square"></i></button>
                                <button v-else type="button" class="btn btn-secondary" @click="abrirModal('personal','edit', personal)" title="Editar"><i class="bi bi-pencil-square"></i></button>
                            </td>
                            <td v-text="personal.name"></td>
                            <td v-text="personal.base"></td>
                            <td v-text="personal.bonus"></td>
                        </tr>
                    </tbody>
                </table>
        </template>

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
                            <h2 v-text="employee_name"></h2>
                            <!--tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==1 || tipoAccion==2">

                                <div class="form-group">
                                    <label for="movil">$ Base </label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="employee_base" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="movil">$ Extra</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="employee_bonus" placeholder="0.00">
                                </div>

                            </div>
                            <!--./tipoAccion==1 o 2: Agregar o ACtualizar-->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
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
                fecha:new Date(),

                fecha_ini:'',
                fecha_fin:'',
                mostrar:false,
                disabledDates: {
                    to:   new Date(2022, 0, 1), // Disable all dates up to specific date
                    from: new Date(), // Disable all dates after specific date
                },

                employee_id:0,
                employee_name:'',
                employee_payroll_id:0,
                employee_base:0,
                employee_bonus:0,

                errors:[],

                modal:0,
                tituloModal:'',
                tipoAccion:0,
                error:0,
                errorMostrarMsj:[],
            }
        },
        methods:{

            loadInfoPeriodo(){
                let me=this;
                console.log(me.fecha);
                let fecha_formateada = moment(me.fecha).format('YYYY-MM-DD');
                console.log(fecha_formateada);
                var url = '/nomina/caprura-load-info?fecha='+fecha_formateada;
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.fecha_ini= respuesta.fecha_ini;
                    me.fecha_fin= respuesta.fecha_fin;
                    me.arrayPersonal= respuesta.array_personal;
                    me.mostrar=true;
                    console.log(me.arrayPersonal);

                  })
                  .catch(function (error) {
                    // handle error
                    console.log(error);
                  })
                  .finally(function () {
                    // always executed
                  });
            },

            registrar(){
                console.log('registrar');
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let formatted_date_ini = moment(me.fecha_ini).format('DD-MM-YYYY');
                let formatted_date_fin = moment(me.fecha_fin).format('DD-MM-YYYY');
                let formatted_date     = moment(me.fecha_fin).format('YYYY-MM-DD');
                let description = `Del ${formatted_date_ini} al ${formatted_date_fin}`;
                let total = parseFloat(me.employee_base) + parseFloat(me.employee_bonus) ;
                axios.post('/nomina/store',{
                  'employee_id':me.employee_id,
                  'name':me.employee_name,
                  'date':formatted_date,
                  'description':description,
                  'base':me.employee_base,
                  'discount':0,
                  'bonus':me.employee_bonus,
                  'total':total,
                  'observations':''

                }).then(function (response){
                  let insert_id= response.data
                  let index = me.arrayPersonal.findIndex(s => s.employee_id == me.employee_id);
                  me.arrayPersonal[index].payroll_id = insert_id;
                  me.arrayPersonal[index].base =  me.employee_base;
                  me.arrayPersonal[index].bonus = me.employee_bonus;
                  me.cerrarModal();

                }).catch(function (error){
                    console.log(error);
                });

            },
            actualizar(){
                console.log('actualizar');
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let total = parseFloat(me.employee_base) + parseFloat(me.employee_bonus) ;
                axios.put('/nomina/actualizar/montos',{
                  'payroll_id':me.employee_payroll_id,
                  'base':me.employee_base,
                  'discount':0,
                  'bonus':me.employee_bonus,
                  'total':total
                }).then(function (response){
                  console.log(response)
                  let index = me.arrayPersonal.findIndex(s => s.employee_id == me.employee_id);
                  me.arrayPersonal[index].base =  me.employee_base;
                  me.arrayPersonal[index].bonus = me.employee_bonus;
                  me.cerrarModal();

                }).catch(function (error){
                    console.log(error);
                });

            },

            validarDatos(){
                this.error=0;
                this.errorMostrarMsj=[];
                if(!this.employee_base) this.errorMostrarMsj.push('El $ Base no puede estar vacio.');
                if(this.errorMostrarMsj.length) this.error=1;
                return this.error;
            },

            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "personal":{
                        switch(accion){
                            case 'create':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar';
                                this.employee_id=data['employee_id'];
                                this.employee_name= data['name'];
                                this.employee_payroll_id=0;
                                this.employee_base=0;
                                this.employee_bonus=0;
                                break;
                            }
                            case 'edit':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar';
                                this.employee_id=data['employee_id'];
                                this.employee_name= data['name'];
                                this.employee_payroll_id=data['payroll_id'];
                                this.employee_base=data['base'];
                                this.employee_bonus=data['bonus'];
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
            console.log('Captura Component mounted.')
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