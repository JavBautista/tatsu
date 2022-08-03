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
                <button type="button" class="btn btn-primary" @click="loadCortes()">Filtrar</button>
            </div>
        </form>
        <hr>
        <template v-if="mostrar">
            <h3>Corte semanal tienda {{fecha_ini }} al {{fecha_fin}}</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th class="text-center" v-for="dia in array_days">{{ dia.dd }} <br> {{dia.date}}</th>
                        <th>TOTALES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-success">
                        <td>VENTAS</td>
                        <td v-for="data in array_days">{{data.sales | toCurrency}}</td>
                        <td><strong>{{ total_sales | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-warning">
                        <td>GASTOS TIENDA</td>
                        <td v-for="data in array_days">{{data.expenses | toCurrency}}</td>
                        <td><strong>{{ total_expenses | toCurrency }}</strong></td>
                    </tr>

                    <tr>
                        <td>TARJETA</td>
                        <td v-for="data in array_days">{{data.card_sales | toCurrency}}</td>
                        <td><strong>{{ total_card_sales | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-active">
                        <td>NETO</td>
                        <td v-for="data in array_days"><strong>{{data.total | toCurrency}}</strong></td>
                        <td><strong>{{ total_neto | toCurrency }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h4>Gastos totales</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th class="text-center" v-for="dia in array_days">{{ dia.dd }} <br> {{dia.date}}</th>
                        <th>TOTALES</th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="table-warning">
                        <td>GASTOS TIENDA</td>
                        <td v-for="data in array_days">{{data.expenses | toCurrency}}</td>
                        <td><strong>{{ total_expenses | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-warning">
                        <td>GASTOS OTROS</td>
                        <td v-for="data in array_days">{{data.expenses_generals | toCurrency}}</td>
                        <td><strong>{{ total_expenses_generales | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-warning">
                        <td>NOMINA</td>
                        <td v-for="data in array_days">{{data.payrolls | toCurrency}}</td>
                        <td><strong>{{ total_payrolls | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-active">
                        <td>GASTOS TOTALES</td>
                        <td v-for="data in array_days">
                            <strong>{{ data.expenses_generals+data.expenses+data.payrolls | toCurrency}}</strong>
                        </td>
                        <td>
                            <strong>{{ total_expenses_generales+total_expenses+total_payrolls  | toCurrency }}</strong>
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
                        <th class="text-center" v-for="dia in array_days">{{ dia.dd }} <br> {{dia.date}}</th>
                        <th>TOTALES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-success">
                        <td>VENTAS</td>
                        <td v-for="data in array_days">{{data.sales | toCurrency}}</td>
                        <td><strong>{{ total_sales | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-warning">
                        <td>GASTOS</td>
                        <td v-for="data in array_days">{{ data.expenses_generals+data.expenses+data.payrolls | toCurrency}}</td>
                        <td><strong>{{ total_expenses_generales+total_expenses+total_payrolls  | toCurrency }}</strong></td>
                    </tr>
                    <tr class="table-active">
                        <td>DIFERENCIA</td>
                        <td :class="((data.sales-(data.expenses_generals+data.expenses+data.payrolls))<0)?'bg-danger text-light':''"
                            v-for="data in array_days">
                            <strong>{{ (data.sales-(data.expenses_generals+data.expenses+data.payrolls)) | toCurrency}}</strong>
                        </td>
                        <td><strong>{{ (total_sales-(total_expenses_generales+total_expenses+total_payrolls))  | toCurrency }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </template>
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
              array_days:[],
              fecha:new Date(),
                total_sales:0,
                total_expenses:0,
                total_card_sales:0,
                total_neto:0,
                total_expenses_generales:0,
                total_payrolls:0,
                fecha_ini:'',
                fecha_fin:'',
                mostrar:false,
                disabledDates: {
                    to:   new Date(2022, 0, 1), // Disable all dates up to specific date
                    from: new Date(), // Disable all dates after specific date
                },
            }
        },
        methods:{

            loadCortes(){
                let me=this;
                console.log(me.fecha);
                let fecha_formateada = moment(me.fecha).format('YYYY-MM-DD');
                console.log(fecha_formateada);
                var url = '/reporte/corte-semanal/get?fecha='+fecha_formateada;
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.array_days=[];
                    me.array_days = respuesta.array_days;
                    me.total_sales= respuesta.total_sales;
                    me.total_expenses= respuesta.total_expenses;
                    me.total_card_sales= respuesta.total_card_sales;
                    me.total_neto= respuesta.total_neto;
                    me.total_expenses_generales= respuesta.total_expenses_generales;
                    me.total_payrolls= respuesta.total_payrolls;
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
            console.log('Corte Semanal Component mounted.')
        }
    }
</script>
