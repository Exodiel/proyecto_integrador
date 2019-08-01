<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li v-on:click="menu=0" class="nav-item">
        <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
      </li>
      <li class="nav-title">
        Mantenimiento
      </li>
      <li v-on:click="menu=1" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-tag"></i> Categorías</a>
      </li>
      <li v-on:click="menu=2" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-bag"></i> Items</a>
      </li>

      <li v-on:click="menu=3" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-wallet"></i> Ingresos</a>
      </li>
      <li v-on:click="menu=4" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedores</a>
      </li>
      <li v-on:click="menu=5" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-basket-loaded"></i> Egresos</a>
      </li>
      <li v-on:click="menu=6" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
      </li>

      <li v-on:click="menu=7" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
      </li>
      <li v-on:click="menu=8" class="nav-item">
        <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">TeamJS</span></a>
      </li>
    </ul>
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
