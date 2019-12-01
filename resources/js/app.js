/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import BoostrapVue from 'bootstrap-vue'
import DatePicker from 'vue2-datepicker'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'

Vue.use(VueRouter)
Vue.use(BoostrapVue)
Vue.use(DatePicker)
Vue.use(VueBootstrapTypeahead)

Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/**
 * Aca definimos las rutas
 */

const router = new VueRouter({
    mode: 'history',
    routes: [
        // {
        //     path: '/home',
        //     name: 'home',
        //     component: require('./views/Home').default
        // },
        // {
        //     path: '/prueba',
        //     name: 'prueba',
        //     component: require('./views/Pruebas').default
        // },
        {
            path: '/persona',
            name: 'persona',
            component: require('./views/Persona').default
        },
    ],
});

Vue.component(
    "v-listar-persona",
    require("./components/rrhh/persona/ListarPersona.vue").default
);
Vue.component(
    "v-crear-persona",
    require("./components/rrhh/persona/CrearPersona.vue").default
);
Vue.component(
    "v-editar-persona",
    require("./components/rrhh/persona/CrearPersona.vue").default
);

//Importar
Vue.component(
    "v-importar-excel-persona",
    require("./components/rrhh/persona/ImportarExcel.vue").default
);

/**
 * Inicializamos el constructor (esto debe ir al final)
 */
import App from './views/App';

const app = new Vue({
    el: '#app',
    components: { App, DatePicker },
    router,
});
