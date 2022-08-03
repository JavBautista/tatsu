/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('cashouts-component', require('./components/CashOutsComponent.vue').default);
Vue.component('corte-semanal-component', require('./components/CorteSemanalComponent.vue').default);
Vue.component('corte-mensual-component', require('./components/CorteMensualComponent.vue').default);
Vue.component('employees-component', require('./components/EmployeesComponent.vue').default);
Vue.component('expenses-component', require('./components/ExpensesComponent.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('insumos-component', require('./components/InsumosComponent.vue').default);
Vue.component('payroll-component', require('./components/PayrollComponent.vue').default);
Vue.component('types-employees-component', require('./components/TypesEmployeesComponent.vue').default);

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN'
    });
    return formatter.format(value);
});

import moment from "moment";

Vue.filter('toDateShort', function (value) {
    let fecha = moment(value).format('YYYY-MM-DD');
    return fecha;
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

