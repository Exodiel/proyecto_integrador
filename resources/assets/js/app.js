/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.$ = window.JQuery = require('jquery');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('categoria', require('./components/Categoria.vue').default);

Vue.component('user', require('./components/User.vue').default);

Vue.component('item', require('./components/Item.vue').default);

Vue.component('proveedor', require('./components/Proveedor.vue').default);

Vue.component('ingreso', require('./components/Ingreso.vue').default);

Vue.component('egreso', require('./components/Egreso.vue').default);

Vue.component('dashboard', require('./components/Dashboard.vue').default);

Vue.component('notification', require('./components/Notification.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  data() {
    return {
      menu : 0,
      ruta: 'http://localhost/proyecto_integrador/public',
      notifications: []
    }
  },
  created() {
    let me = this;
    axios.post(this.ruta + '/notification/get').then(response => {
      // console.log(response.data);
      me.notifications = response.data;
    })
    .catch(err => {
      console.log(err);
    });

    var userId = $('meta[name="userId"]').attr('content');

    Echo.private('App.User.'+userId).notification(notification => {
      // console.log(notification);
      me.notifications.unshift(notification);
    });
  },
});
