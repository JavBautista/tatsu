<template>
    <div class="container">
        
        <div class="container-fluid">
            <!--Card Productos-->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Insumos
                    <button type="button" @click="abrirModal('product','registrar')" class="btn btn-primary float-rigth">
                        <i class="bi bi-plus-circle"></i>&nbsp;Nuevo
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
                                <input type="text" v-model="buscar" class="form-control" placeholder="Texto a buscar" @keyup.enter="cargarProductos(1,buscar,criterio)">
                                <button type="submit" @click="cargarProductos(1,buscar,criterio)" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button @click="exportar()" class="btn btn-outline-success">Exportar</button>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <!--<th>Proveedor</th>
                                    <th>Descripción</th>
                                    -->
                                    <th>Unidad</th>
                                    <th>Costo Unitario</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in arrayProducts" :key="product.id">

                                    <td v-text="product.name"></td>
                                    <!--<td v-text="product.provider"></td>
                                    <td v-text="product.description"></td>
                                    -->
                                    <td v-text="product.unit"></td>
                                    <td class="text-bg-primary" v-text="'$'+product.cost"></td>
                                    <td v-text="product.qty"></td>
                                    <td v-text="'$'+(product.cost*product.qty)"></td>
                                    <td>
                                        <span v-if="product.active" class="badge bg-success">Activo</span>
                                        <span v-else class="badge bg-danger">Baja</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info" @click="abrirModal('product','actualizar_datos', product)"><i class="bi bi-pencil-square"></i></button>
                                        <button v-if="product.active" type="button" class="btn btn-info" @click="actualizarAInactivo(product.id)"> <i class="bi bi-hand-thumbs-down"></i></button>                                
                                        <button v-else type="button" class="btn btn-info" @click="actualizarAActivo(product.id)"> <i class="bi bi-hand-thumbs-up"></i></button>
                                        
                                        <button type="button" class="btn btn-secondary" @click="abrirModal('product','agregar_iventario', product)"><i class="bi bi-journal-plus"></i> </button>
                                        <button type="button" class="btn btn-dark" @click="abrirModal('product','ver_iventario', product)"> <i class="bi bi-journals"></i> </button>
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
                            <div v-show="errorProducto" class="form-group row div-error">
                                <div class="container-fluid">
                                    <div class="alert alert-danger text-center">
                                        <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <div v-if="tipoAccion==1 || tipoAccion==2">
                                <p><em><strong class="text text-danger">* Campos obligatorios</strong></em></p>
                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="name">Name</label>
                                    <input type="text" class="form-control" v-model="name"  placeholder="Enter Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="provider">Proveedor</label>
                                    <input type="text" class="form-control" v-model="provider"  placeholder="Nombre proveedor">
                                </div>

                                <div class="form-group">
                                   <label for="description">Description</label>
                                    <textarea class="form-control" v-model="description"  rows="3"></textarea>
                                </div>
                                

                                <div class="form-group">
                                    <label for="unit">Unidad</label>
                                    <input type="text" class="form-control" v-model="unit" placeholder="Enter unidad">
                                </div>
                                <div v-if="tipoAccion==1" class="form-group">
                                    <label for="cost">Costo Unitario</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="cost" placeholder="Enter Cost">
                                </div>

                                <div v-if="tipoAccion==1" class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="qty" placeholder="Enter qty">
                                </div>
                            </div>
                            <!--./tipoAccion==1 o 2: Agregar o ACtualizar-->
                            <!--tipoAccion==3 Agregar inventario-->
                            <div v-else-if="tipoAccion==3">
                                <p><em><strong class="text text-danger">* Campos obligatorios</strong></em></p>
                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="date">Fecha</label>
                                    <datepicker v-model="inventory.created_at"></datepicker>
                                </div>
                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="cost">Costo Unitario</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="inventory.cost" placeholder="Enter Cost">
                                </div>
                                <div class="form-group">
                                    <strong class="text text-danger">*</strong><label for="qty">Qty</label>
                                    <input type="number" min="0" step="1" class="form-control" v-model="inventory.qty" placeholder="Enter unidad">
                                </div>
                            </div>
                            <!--./tipoAccion==3 Agregar inventario-->
                        </form>
                        <!--tipoAccion==4-->
                        <div v-if="tipoAccion==4">
                            <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Costo Unidad</th>
                                    <th>Qty</th>
                                    <th>Sutotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inv in arrayInventories" :key="inv.id">
                                    <td v-text="inv.created_at"></td>
                                    <td v-text="'$'+inv.cost"></td>                                   
                                    <td v-text="inv.qty"></td>
                                    <td v-text="'$'+(inv.cost * inv.qty)"></td>                                   
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <!--./tipoAccion==4 -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDatosProducto()">Actualizar</button>
                        <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="agregarInventarioProducto()">Actualizar</button>
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
              
              arrayProducts:[],
              arrayInventories:[],
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

              product_id:0,              
              name:'',
              provider:'',
              description:'',
              unit:'',              
              qty:0,              
              cost:0,              
              active:1,

              inventory:{
                product_id:0,
                qty:0,
                cost:0,
                created_at:''
              },

              errors:[],

              modal:0,
              tituloModal:'',
              tipoAccion:0,
              errorProducto:0,
              errorMostrarMsjProducto:[],
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
            exportar(){
                let me=this;
                let export_url= '/insumos/exportar';
                window.location.href= export_url;

            },       
            loadInvetoryProduct(product_id){
                let me=this;
                var url = '/insumos/inventory/get/'+product_id;
                axios.get(url).then(function (response){
                    me.arrayInventories=[];
                    me.arrayInventories  = response.data;
                    me.modal=1;
                  })
                  .catch(function (error) {
                    // handle error
                    console.log(error);
                  })
                  .finally(function () {
                    // always executed
                  });
            },
            cargarProductos(page,buscar,criterio){
                let me=this;
                var url = '/insumos/get?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.arrayProducts = respuesta.products.data;
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
                me.cargarProductos(page,buscar,criterio);
            },
             validarDatosProduct(){
                this.errorProducto=0;
                this.errorMostrarMsjProducto=[];
                if(!this.name) this.errorMostrarMsjProducto.push('El valor nombre no puede estar vacio.');
                if(!this.qty) this.errorMostrarMsjProducto.push('El valor qty no puede estar vacio.');
                if(!this.cost) this.errorMostrarMsjProducto.push('El valor costo no puede estar vacio.');
                if(this.errorMostrarMsjProducto.length) this.errorProducto=1;
                return this.errorProducto;
            },
            registrarProducto(){
                if(this.validarDatosProduct()){
                    return;
                }
                let me=this;
                console.log(me.unit)
                axios.post('/insumos/store',{
                  'barcode':me.barcode,
                  'name':me.name,
                  'provider':me.provider,
                  'description':me.description,
                  'qty': me.qty,
                  'unit': me.unit,
                  'cost': me.cost
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarProductos(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },  
             validarDatosIventario(){
                this.errorProducto=0;
                this.errorMostrarMsjProducto=[];
                if(!this.inventory.qty) this.errorMostrarMsjProducto.push('El valor qty no puede estar vacio.');
                if(!this.inventory.cost) this.errorMostrarMsjProducto.push('El valor costo no puede estar vacio.');
                if(!this.inventory.created_at) this.errorMostrarMsjProducto.push('El valor fecha no puede estar vacio.');
                if(this.errorMostrarMsjProducto.length) this.errorProducto=1;
                return this.errorProducto;
            },         
            agregarInventarioProducto(){
                if(this.validarDatosIventario()){
                    return;
                }                
                let me=this;
                let inv= this.inventory;
                let created_at_format = moment(inv.created_at).format('YYYY-MM-DD');
                axios.post('/insumos/inventory/store',{
                  'product_id':inv.product_id,
                  'qty':inv.qty,
                  'cost':inv.cost,
                  'created_at':created_at_format

                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarProductos(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },           
            actualizarDatosProducto(){
                if(this.validarDatosProduct()){
                    return;
                }
                let me=this;
                axios.put('/insumos/update',{
                  'product_id':me.product_id,                  
                  'provider':me.provider,
                  'name':me.name,
                  'description':me.description,                  
                  'unit':me.unit,                  
                }).then(function (response){
                  //console.log(response)
                  me.cerrarModal();
                  me.cargarProductos(me.pagination.current_page,me.buscar,me.criterio)
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarAActivo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                  title: '¿Desea cambiar a producto activo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me=this;
                    axios.put('/insumos/activar',{
                        'id': id
                    }).then(function (response){
                        me.cargarProductos(me.pagination.current_page,me.buscar,me.criterio);
                        swalWithBootstrapButtons.fire(
                          'Producto Activo!',
                          'El producto ha sido actualizado con exito.',
                          'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                    });

                  } /*else if (
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    swalWithBootstrapButtons.fire(
                      'Cancelled',
                      'Your imaginary file is safe :)',
                      'error'
                    )
                  }*/
                })
            },
            actualizarAInactivo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                  title: '¿Desea cambiar a producto inactivo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me=this;
                    axios.put('/insumos/desactivar',{
                        'id': id
                    }).then(function (response){
                        me.cargarProductos(me.pagination.current_page,me.buscar,me.criterio);
                        swalWithBootstrapButtons.fire(
                          'Producto Inactivo!',
                          'El producto ha sido actualizado con exito.',
                          'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                    });

                  } /*else if (
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    swalWithBootstrapButtons.fire(
                      'Cancelled',
                      'Your imaginary file is safe :)',
                      'error'
                    )
                  }*/
                })
            },
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "product":{
                        switch(accion){
                            case 'registrar':{
                                this.modal=1;
                                this.tipoAccion =1;
                                this.tituloModal='Agregar Producto';

                                this.product_id=0;
                                this.name='';
                                this.description ='';
                                this.provider ='';
                                this.unit=1;
                                this.cost=1;
                                this.qty=1;

                                break;
                            }
                            case 'actualizar_datos':{
                                this.modal=1;
                                this.tipoAccion =2;
                                this.tituloModal='Actualizar Producto';

                                this.product_id= data['id'];
                                this.name = data['name'];
                                this.description = data['description'];
                                this.provider = data['provider'];
                                this.unit  = data['unit'];                                
                                this.qty  = data['qty'];                                
                                this.cost = data['cost'];                                
                                break;
                            }
                            case 'agregar_iventario':{
                                this.modal=1;
                                this.tipoAccion =3;
                                this.tituloModal='Agregar Inventario';

                                this.inventory.product_id = data['id'];                             
                                this.inventory.qty  = 0;                             
                                this.inventory.cost = 0;                             
                                this.inventory.created_at = '';                             
                                break;
                            }
                            case 'ver_iventario':{
                                //ets elo haremos cuando se carguen lo registros
                                //this.modal=1;
                                this.tipoAccion =4;
                                this.tituloModal='Ver Inventario';

                                this.inventory.product_id = data['id'];
                                this.loadInvetoryProduct(this.inventory.product_id);                     
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
            console.log('Insumos component mounted.')
            this.cargarProductos(1,'','name');
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
