<template>
  <li class="nav-item d-md-down-none">
    <a class="nav-link" href="#" data-toggle="dropdown">
      <i class="icon-bell"></i>
      <span class="badge badge-pill badge-danger">{{notifications.length}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
      <div class="dropdown-header text-center">
        <strong>Notificaciones</strong>
      </div>
      <div v-if="notifications.length">

        <li v-for="(item, index) in listar" :key="index">
          <a class="dropdown-item" href="#">
            <i class="fa fa-envelope-o"></i> {{item.ingresos.msj}}
            <span class="badge badge-success">{{item.ingresos.numero}}</span>
          </a>
          <a class="dropdown-item" href="#">
            <i class="fa fa-tasks"></i> {{item.egresos.msj}}
            <span class="badge badge-danger">{{item.egresos.numero}}</span>
          </a>
        </li>
      </div>
      <div v-else>
        <a><span>No tiene notificaciones</span></a>
      </div>
    </div>
  </li>
</template>
<script>
export default {
  props: ['notifications'],
  data() {
    return {
      arrNotifications: []
    }
  },
  computed: {
    listar: function(){
      // return this.notifications[0];
      this.arrNotifications = Object.values(this.notifications[0]);
      if (this.notifications == '') {
        return this.arrNotifications = [];
      } else {
        //capturo la última notificación agregada
        this.arrNotifications = Object.values(this.notifications[0]);
        //validación por índice fuera de rango
        if(this.arrNotifications.length > 3) {
          //si el tamaño es > 3 es cuando las notificaciones son obtenidas desde el mismo servidor
          return Object.values(this.arrNotifications[4]);
        } else {
          //si el tamaño es < 3 es cuando las notificaciones son obtenidas desde el canal privado (laravel-echo y pusher)
          return Object.values(this.arrNotifications[0]);
        }
      }
    }
  },
}
</script>
