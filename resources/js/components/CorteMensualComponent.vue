<template>
    <div class="container">
        <form v-on:submit.prevent action="" method="post" enctype="multipart/form-data" class="row row-cols-lg-auto g-3 align-items-center">
            <div class="col-12">
                <label class="visually-hidden" for="month">Mes</label>
                <div class="input-group">
                    <div class="input-group-text">Mes</div>
                    <select class="form-select" v-model="month">
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <label class="visually-hidden" for="year">Año</label>
                <div class="input-group">
                    <div class="input-group-text">Mes</div>
                    <select class="form-select" v-model="year">
                        <option value="2022">2022</option>
                    </select>
                </div>
            </div>

            <div class="col-12">
                <button type="button" class="btn btn-primary" @click="loadCortes()">Filtrar</button>
            </div>
        </form>
        <hr>
        <template v-if="mostrar">
            <h3>Corte mensual {{fecha_ini }} al {{fecha_fin}}</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>TOTALES</th>
                        <th class="text-center" v-for="dia in array_days">{{dia.date}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-success">
                        <td>VENTAS</td>
                        <td><strong>{{ total_sales | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.sales | toCurrency}}</td>
                    </tr>
                    <tr class="table-warning">
                        <td>GASTOS TIENDA</td>
                        <td><strong>{{ total_expenses | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.expenses | toCurrency}}</td>
                    </tr>

                    <tr>
                        <td>TARJETA</td>
                        <td><strong>{{ total_card_sales | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.card_sales | toCurrency}}</td>
                    </tr>
                    <tr class="table-active">
                        <td>NETO</td>
                        <td><strong>{{ total_neto | toCurrency }}</strong></td>
                        <td v-for="data in array_days"><strong>{{data.total | toCurrency}}</strong></td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h4>Gastos totales</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>TOTALES</th>
                        <th class="text-center" v-for="dia in array_days">{{dia.date}}</th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="table-warning">
                        <td>GASTOS TIENDA</td>
                        <td><strong>{{ total_expenses | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.expenses | toCurrency}}</td>
                    </tr>
                    <tr class="table-warning">
                        <td>GASTOS OTROS</td>
                        <td><strong>{{ total_expenses_generales | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.expenses_generals | toCurrency}}</td>
                    </tr>
                    <tr class="table-warning">
                        <td>NOMINA</td>
                        <td><strong>{{ total_payrolls | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.payrolls | toCurrency}}</td>
                    </tr>
                    <tr class="table-active">
                        <td>GASTOS TOTALES</td>
                        <td>
                            <strong>{{ total_expenses_generales+total_expenses+total_payrolls  | toCurrency }}</strong>
                        </td>
                        <td v-for="data in array_days">
                            <strong>{{ data.expenses_generals+data.expenses+data.payrolls | toCurrency}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h4>Balance de ingresos y egresos</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>TOTALES</th>
                        <th class="text-center" v-for="dia in array_days">{{dia.date}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-success">
                        <td>VENTAS</td>
                        <td><strong>{{ total_sales | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{data.sales | toCurrency}}</td>
                    </tr>
                    <tr class="table-warning">
                        <td>GASTOS</td>
                        <td><strong>{{ total_expenses_generales+total_expenses+total_payrolls  | toCurrency }}</strong></td>
                        <td v-for="data in array_days">{{ data.expenses_generals+data.expenses+data.payrolls | toCurrency}}</td>
                    </tr>
                    <tr class="table-active">
                        <td>DIFERENCIA</td>
                        <td><strong>{{ (total_sales-(total_expenses_generales+total_expenses+total_payrolls))  | toCurrency }}</strong></td>
                        <td :class="((data.sales-(data.expenses_generals+data.expenses+data.payrolls))<0)?'bg-danger text-light':''"
                            v-for="data in array_days">
                            <strong>{{ (data.sales-(data.expenses_generals+data.expenses+data.payrolls)) | toCurrency}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                month:1,
                year:'2022',
                array_days:[],
                total_sales:0,
                total_expenses:0,
                total_card_sales:0,
                total_neto:0,
                total_expenses_generales:0,
                total_payrolls:0,
                fecha_ini:'',
                fecha_fin:'',
                mostrar:false,
            }
        },
        methods:{

            loadCortes(){
                let me=this;
                console.log(me.month+' '+me.year);
                var url = '/reporte/corte-mensual/get?month='+me.month+'&year='+me.year;
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.array_days=[];
                    me.array_days = respuesta.array_days;
                    me.total_sales= respuesta.total_sales;
                    me.total_expenses= respuesta.total_expenses;
                    me.total_card_sales= respuesta.total_card_sales;
                    me.total_payrolls= respuesta.total_payrolls;

                    me.total_neto= respuesta.total_neto;
                    me.total_expenses_generales= respuesta.total_expenses_generales;
                    me.fecha_ini= respuesta.fecha_ini;
                    me.fecha_fin= respuesta.fecha_fin;
                    me.mostrar=true;

                  })
                  .catch(function (error) {
                    // handle error
                    console.log(error);
                  })
                  .finally(function () {
                    // always executed
                  });
            },

        },
        mounted() {
            console.log('Corte Mensual Component mounted.')
        }
    }
</script>
