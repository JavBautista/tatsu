<template>
    <div class="container">
        <div class="container-fluid">
            <!--Card-->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Nominas
                    <button type="button" @click="abrirModal('nomina','registrar')" class="btn btn-primary float-rigth">
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
                            <input type="text" v-model="buscar" class="form-control" placeholder="Texto a buscar" @keyup.enter="loadNominas(1,buscar,criterio)">
                            <button type="submit" @click="loadNominas(1,buscar,criterio)" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Base</th>
                                    <th>Descuento</th>
                                    <th>Bono</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="nomina in arrayNominas" :key="nomina.id">
                                    <td v-text="nomina.id"></td>
                                    <td>{{nomina.date }}</td>
                                    <td v-text="nomina.name"></td>
                                    <td v-text="nomina.description"></td>
                                    <td>$ {{nomina.base | toCurrency }}</td>
                                    <td>$ {{nomina.discount | toCurrency }}</td>
                                    <td>$ {{nomina.bonus | toCurrency }}</td>
                                    <td>$ {{nomina.total | toCurrency }}</td>

                                    <td>
                                        <button type="button" class="btn btn-outline-info" @click="abrirModal('nomina','ver', nomina)" title="Editar"><i class="bi bi-eye"></i></button>
                                        <button type="button" class="btn btn-outline-info" @click="abrirModal('nomina','actualizar_datos', nomina)" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                        <button type="button" class="btn btn-outline-warning" @click="disable(nomina.id)" title="Eliminar"><i class="bi bi-trash"></i></button>
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
                            <!--tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==1 || tipoAccion==2">
                                <p><em><strong class="text text-danger">* Campos obligatorios</strong></em></p>

                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="date">Fecha</label>
                                    <datepicker v-model="date"></datepicker>
                                </div>

                                <div class="form-group">
                                    <label for="name"><strong class="text text-danger">*</strong>Nombre</label>
                                    <select class="form-select" v-model="select_employee" @change="onChangeEmployee()">
                                      <option selected>Selecciona empleado</option>
                                      <option v-for="employee in arrayEmployees" :key="employee.id" :value="{ id: employee.id, name: employee.name }" v-text="employee.name"></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description"><strong class="text text-danger">*</strong>Descripcion</label>
                                    <input type="text" class="form-control" v-model="description" placeholder="Ingrese descripción" required>
                                </div>
                                <div class="form-group">
                                    <label for="base"><strong class="text text-danger">*</strong>Base</label>
                                    <input type="number" min="0" step="1" class="form-control" v-on:keyup="calcularTotal()" v-model="base" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="discount">Desccuento</label>
                                    <input type="number" min="0" step="1" class="form-control" v-on:keyup="calcularTotal()" v-model="discount" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="bonus">Bono</label>
                                    <input type="number" min="0" step="1" class="form-control" v-on:keyup="calcularTotal()" v-model="bonus" placeholder="0.00">
                                </div>

                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="total" placeholder="0.00" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="observations">Observaciones</label>
                                    <input type="text" class="form-control" v-model="observations" placeholder="">
                                </div>
                            </div>
                            <!--./tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==3">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" v-model="name" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descripcion</label>
                                    <input type="text" class="form-control" v-model="description" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="base">Base</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="base" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Desccuento</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="discount" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="bonus">Bono</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="bonus" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="total" readonly readonly>
                                </div>

                                <div class="form-group">
                                    <label for="observations">Observaciones</label>
                                    <input type="text" class="form-control" v-model="observations" readonly>
                                </div>
                            </div>
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
              select_employee:{},
              arrayNominas:[],
              arrayEmployees:[],
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

              nomina_id:0,
              employee_id:0,
              name:'',
              date:null,
              description:'',
              observations:'',
              base:0,
              discount:0,
              bonus:0,
              total:0,

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
            onChangeEmployee(){
                let me=this;
                me.name= me.select_employee.name;
                me.employee_id= me.select_employee.id;

            },
            loadEmployees(){
                let me=this;
                var url = '/personal/get/all/active';
                axios.get(url).then(function (response){
                    me.arrayEmployees = response.data;
                  })
                  .catch(function (error) {
                    // handle error
                    console.log(error);
                  })
                  .finally(function () {
                    // always executed
                  });
            },
            loadNominas(page,buscar,criterio){
                let me=this;
                var url = '/nomina/get?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response){
                    let respuesta = response.data;
                    me.arrayNominas = respuesta.payroll.data;
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
                me.loadNominas(page,buscar,criterio);
            },
            calcularTotal(){
                let sueldo      = this.base ? parseFloat(this.base):0;
                let descuento   = this.discount ? parseFloat(this.discount):0;
                let bono        = this.bonus ? parseFloat(this.bonus):0;
                this.total =  (sueldo+bono)-descuento;
            },
            validarDatos(){
                this.error=0;
                this.errorMostrarMsj=[];
                let base_0 = this.base ? parseFloat(this.base):0;
                if(!this.date)          this.errorMostrarMsj.push('El valor nombre no puede estar vacio.');
                if(!this.name)          this.errorMostrarMsj.push('El valor nombre no puede estar vacio.');
                if(!this.description)   this.errorMostrarMsj.push('El valor descripcion no puede estar vacio.');
                if(!this.base || base_0 <= 0 ) this.errorMostrarMsj.push('Ingrese monto base correcto.');
                if(this.errorMostrarMsj.length) this.error=1;
                return this.error;
            },
            registrar(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let formatted_date = moment(me.date).format('YYYY-MM-DD')
                axios.post('/nomina/store',{
                  'employee_id':me.employee_id,
                  'name':me.name,
                  'date':formatted_date,
                  'description':me.description,
                  'base':me.base,
                  'discount':me.discount,
                  'bonus':me.bonus,
                  'total':me.total,
                  'observations':me.observations

                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.loadNominas(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarDatos(){
                if(this.validarDatos()){
                    return;
                }
                let me=this;
                let formatted_date = moment(me.date).format('YYYY-MM-DD')
                axios.put('/nomina/update',{
                  'id':me.nomina_id,
                  'name':me.name,
                  'date':formatted_date,
                  'description':me.description,
                  'base':me.base,
                  'discount':me.discount,
                  'bonus':me.bonus,
                  'total':me.total,
                  'observations':me.observations
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.loadNominas(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },
            disable(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                  title: '¿Desea eliminar este registro?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me=this;
                    axios.put('/nomina/disable',{
                        'id': id
                    }).then(function (response){
                        me.loadNominas(me.pagination.current_page,me.buscar,me.criterio);
                        swalWithBootstrapButtons.fire(
                          'Activo',
                          'Registro eliminado correctamente.',
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
                    case "nomina":{
                        switch(accion){
                            case 'registrar':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar';

                                this.select_employee={};
                                this.nomina_id=0;
                                this.employee_id=1;
                                this.name='';
                                this.date=null;
                                this.description='';
                                this.base=0;
                                this.discount=0;
                                this.bonus=0;
                                this.total=0;
                                this.observations='';

                                break;
                            }
                            case 'actualizar_datos':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar';
                                this.nomina_id   = data['id'];
                                this.employee_id = data['employee_id'];
                                this.name = data['name'];
                                this.date = data['date'];
                                this.description = data['description'];
                                this.base = data['base'];
                                this.discount = data['discount'];
                                this.bonus = data['bonus'];
                                this.total = data['total'];
                                this.observations = data['observations'];
                                break;
                            }
                            case 'ver':{
                                this.modal=1;
                                this.tipoAccion =3;
                                this.tituloModal='Ver';
                                this.nomina_id   = data['id'];
                                this.employee_id = data['employee_id'];
                                this.name = data['name'];
                                this.date = data['date'];
                                this.description = data['description'];
                                this.base = data['base'];
                                this.discount = data['discount'];
                                this.bonus = data['bonus'];
                                this.total = data['total'];
                                this.observations = data['observations'];
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
            this.loadNominas(1,'','description');
            this.loadEmployees();
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
