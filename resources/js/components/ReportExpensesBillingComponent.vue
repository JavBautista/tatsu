<template>
    <div class="container">
            <h3>Facturas</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Evidencia</th>
                        <th>Costo</th>
                        <th>Facturado</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="expense in arrayExpensesBilling" :key="expense.id">
                        <td v-text="expense.date"></td>
                        <td v-text="expense.description"></td>
                        <td v-text="expense.evidence"></td>
                        <td v-text="'$'+expense.cost"></td>
                        <td>
                            <span v-if="expense.billing" class="badge bg-info">Facturado</span>
                            <span v-else class="badge bg-secondary">Sin Facturar</span>
                        </td>
                        <td v-text="expense.billing_reference"></td>

                    </tr>
                </tbody>

            </table>
    </div>
</template>

<script>
    export default {
        data(){
            return {
              arrayExpensesBilling:[],
            }
        },
        methods:{

            loadExpensesBilling(){
                let me=this;
                var url = '/reporte/facturas/get';
                axios.get(url).then(function (response){
                    console.log(response)
                    var respuesta  = response.data;
                    me.arrayExpensesBilling = respuesta.expenses_billing;
                    console.log(me.arrayExpensesBilling);
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
            console.log('Report Expense Billing Component mounted.')
            this.loadExpensesBilling();
        }
    }
</script>
