<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Egresos
          <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <!-- Listado -->
        <template v-if="listado==1">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="tipo_comprobante">Tipo Comprobante</option>
                    <option value="num_comprobante">Número Comprobante</option>
                    <option value="fecha_hora">Fecha-Hora</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarEgreso(1,buscar,criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  >
                  <button
                    type="submit"
                    @click="listarEgreso(1,buscar,criterio)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Tipo Comprobante</th>
                    <th>Número Comprobante</th>
                    <th>Fecha Hora</th>
                    <th>Descuento</th>
                    <th>IVA</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="egreso in arrayEgreso" :key="egreso.id">
                    <td>
                      <button
                        type="button"
                        @click="verEgreso(egreso.id)"
                        class="btn btn-warning btn-sm"
                      >
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="desactivarEgreso(egreso.id)"
                        >
                          <i class="icon-trash"></i>
                        </button>
                    </td>
                    <td v-text="egreso.usuario"></td>
                    <td v-text="egreso.descripcion"></td>
                    <td v-text="egreso.tipo_comprobante"></td>
                    <td v-text="egreso.num_comprobante"></td>
                    <td v-text="egreso.fecha_hora"></td>
                    <td v-text="egreso.descuento"></td>
                    <td v-text="egreso.iva"></td>
                    <td v-text="egreso.total"></td>

                  </tr>
                </tbody>
              </table>
            </div>

            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                  >Ant</a>
                </li>
                <li
                  class="page-item"
                  v-for="page in pagesNumber"
                  :key="page"
                  :class="[page == isActived ? 'active' : '']"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(page,buscar,criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                  >Sig</a>
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!-- Fin Listado -->

        <!-- Detalle -->
        <template v-else-if="listado==0">
          <div class="card-body">
            <div class="form-group row border">
              <!-- <div class="col-md-6">
                <div class="form-group">
                  <label>Proveedor(*)</label>
                  <v-select
                    @search="selectProveedor"
                    label="nombre"
                    :options="arrayProveedor"
                    placeholder="Buscar Proveedores..."
                    @input="getDatosProveedor"
                  />
                </div>
              </div> -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Descripción(*)</label>
                  <select class="form-control" v-model="descripcion">
                    <option value="0">Seleccione</option>
                    <option value="SALIDA">Salida</option>
                    <option value="DEVOLUCION">Devolución</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for>Descuento % (General)</label>
                <input type="text" class="form-control" v-model="descuentoT">
              </div>
              <div class="col-md-3">
                <label for>IVA(*)</label>
                <input type="text" class="form-control" v-model="iva">
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Tipo Comprobante(*)</label>
                  <select class="form-control" v-model="tipo_comprobante">
                    <option value="0">Seleccione</option>
                    <option value="BOLETA">Boleta</option>
                    <option value="TICKET">Ticket</option>
                    <option value="FACTURA">Factura</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <label>Número Comprobante(*)</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="num_comprobante"
                  placeholder="000xx"
                >
              </div>
              <div class="col-md-12">
                <div v-show="errorEgreso" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjEgreso" v-text="error" :key="error"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row border">
              <div class="col-md-4">
                <div class="form-group">
                  <label>
                    Item
                    <span style="color:red;" v-show="iditem==0">(*Seleccione)</span>
                  </label>
                  <div class="form-inline">
                    <input
                      type="text"
                      class="form-control"
                      v-model="codigo"
                      @keyup.enter="buscarItem()"
                      placeholder="Ingrese item"
                    >
                    <button @click="abrirModal()" class="btn btn-primary">...</button>
                    <input type="text" readonly class="form-control" v-model="item">
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>
                    Precio
                    <span style="color:red;" v-show="precio==0">(*Ingrese precio)</span>
                  </label>
                  <input type="number" step="any" value="0" class="form-control" v-model="precio">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>
                    Cantidad
                    <span style="color:red;" v-show="cantidad==0">(*Ingrese cantidad)</span>
                  </label>
                  <input type="number" class="form-control" value="0" v-model="cantidad">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>
                    Descuento %
                  </label>
                  <input type="number" class="form-control" value="0" v-model="descuentoD">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button class="btn btn-success form-control btnagregar" @click="agregarDetalle()">
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="form-group row border">
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <th>Opciones</th>
                    <th>Item</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Descuento %</th>
                    <th>Subtotal</th>
                  </thead>
                  <tbody v-if="arrayDetalle.length">
                    <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                      <td>
                        <button
                          @click="eliminarDetalle(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td v-text="detalle.item"></td>
                      <td>
                        <input
                          v-model="detalle.precio"
                          type="number"
                          class="form-control"
                        >
                      </td>
                      <td>
                        <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                        <input
                          v-model="detalle.cantidad"
                          type="number"
                          class="form-control"
                        >
                      </td>

                      <td>
                        <span style="color:red;" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento superior</span>
                        <input v-model="detalle.descuento" type="number" class="form-control">
                      </td>

                      <td>{{(detalle.precio*detalle.cantidad-(detalle.precio*(detalle.descuento/100))).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>SubTotal Parcial:</strong>
                      </td>
                      <td>$ {{totalSubtotal= (calcularTotalSubtotal).toFixed(2)}}</td>
                    </tr>
                    <!-- <tr style="background-color: #CEECF5;">
                      <td colspan="4" align="right">
                        <strong>Total Parcial:</strong>
                      </td>
                      <td>$ {{totalParcial=(totalSubtotal-totalIVA).toFixed(2)}}</td>
                    </tr> -->
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Base Imponible:</strong>
                      </td>
                      <td>$ {{totalBI= (totalSubtotal-totalDescuento).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total Descuento:</strong>
                      </td>
                      <td>$ {{totalDescuento = (totalSubtotal*(descuentoT/100)).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total IVA:</strong>
                      </td>
                      <td>$ {{totalIVA = (totalBI*iva).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total Neto:</strong>
                      </td>
                      <td>$ {{total = parseFloat((totalSubtotal-totalDescuento)+(totalBI*iva)).toFixed(2)}}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay Items agregados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-12">
                <button class="btn btn-secondary" type="button" @click="ocultarDetalle()">Cerrar</button>
                <button
                  class="btn btn-primary"
                  type="button"
                  @click="registrarEgreso()"
                >Registrar Egreso</button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin Detalle -->

        <!-- Ver egreso -->
        <template v-else-if="listado==2">
          <div class="card-body">
            <div class="form-group row border">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Descripción(*)</label>
                  <select class="form-control" v-model="descripcion">
                    <option value="0">Seleccione</option>
                    <option value="SALIDA">Salida</option>
                    <option value="DEVOLUCION_S">Devolución</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label >Descuento % (General)</label>
                <input class="form-control" type="text" v-model="descuentoT">
              </div>
              <div class="col-md-3">
                <label >IVA (*)</label>
                <input class="form-control" type="text" v-model="iva">
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Tipo Comprobante(*)</label>
                  <select class="form-control" v-model="tipo_comprobante">
                    <option value="0">Seleccione</option>
                    <option value="BOLETA">Boleta</option>
                    <option value="TICKET">Ticket</option>
                    <option value="FACTURA">Factura</option>
                  </select>
                </div>
              </div>


              <div class="col-md-4">
                <label>Número Comprobante (*)</label>
                <input class="form-control" type="text" v-model="num_comprobante">
              </div>
            </div>

            <div class="form-group row border">
              <div class="col-md-4">
                <div class="form-group">
                  <label>
                    Item
                    <span style="color:red;" v-show="iditem==0">(*Seleccione)</span>
                  </label>
                  <div class="form-inline">
                    <input
                      type="text"
                      class="form-control"
                      v-model="codigo"
                      @keyup.enter="buscarItem()"
                      placeholder="Ingrese item"
                    >
                    <button @click="abrirModal()" class="btn btn-primary">...</button>
                    <input type="text" readonly class="form-control" v-model="item">
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>
                    Precio
                    <span style="color:red;" v-show="precio==0">(*Ingrese precio)</span>
                  </label>
                  <input type="number" step="any" value="0" class="form-control" v-model="precio">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>
                    Cantidad
                    <span style="color:red;" v-show="cantidad==0">(*Ingrese cantidad)</span>
                  </label>
                  <input type="number" class="form-control" value="0" v-model="cantidad">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>
                    Descuento %
                  </label>
                  <input type="number" class="form-control" value="0" v-model="descuentoD">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button class="btn btn-success form-control btnagregar" @click="agregarDetalle()">
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="form-group row border">
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <th>Opciones</th>
                    <th>Item</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Descuento %</th>
                    <th>Subtotal</th>
                  </thead>
                  <tbody v-if="arrayDetalle.length">
                    <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                      <td>
                        <button
                          @click="eliminarDetalle(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td v-text="detalle.item"></td>
                      <td>
                        <input
                          v-model="detalle.precio"
                          type="number"
                          class="form-control"
                        >
                      </td>
                      <td>
                        <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                        <input
                          v-model="detalle.cantidad"
                          type="number"
                          class="form-control"
                        >
                      </td>

                      <td>
                        <span style="color:red;" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento superior</span>
                        <input v-model="detalle.descuento" type="number" class="form-control">
                      </td>

                      <td>{{(detalle.precio*detalle.cantidad-(detalle.precio*(detalle.descuento/100))).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>SubTotal Parcial:</strong>
                      </td>
                      <td>$ {{totalSubtotal= (calcularTotalSubtotal).toFixed(2)}}</td>
                    </tr>
                    <!-- <tr style="background-color: #CEECF5;">
                      <td colspan="4" align="right">
                        <strong>Total Parcial:</strong>
                      </td>
                      <td>$ {{totalParcial=(totalSubtotal-totalIVA).toFixed(2)}}</td>
                    </tr> -->
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Base Imponible:</strong>
                      </td>
                      <td>$ {{totalBI= (totalSubtotal-totalDescuento).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total Descuento:</strong>
                      </td>
                      <td>$ {{totalDescuento = (totalSubtotal*(descuentoT/100)).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total IVA:</strong>
                      </td>
                      <td>$ {{totalIVA = (totalBI*iva).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total Neto:</strong>
                      </td>
                      <td>$ {{total = parseFloat((totalSubtotal-totalDescuento)+(totalBI*iva)).toFixed(2)}}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay Items agregados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-12">
                <button class="btn btn-secondary" type="button" @click="ocultarDetalle()">Cerrar</button>
                <button class="btn btn-primary" type="button" @click="actualizarEgreso()">Actualizar Egreso</button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin ver egreso -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>

    <!--Inicio del modal agregar al detalle-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' : modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterioA">
                    <option value="nombre">Nombre</option>
                    <option value="descripcion">Descripción</option>
                    <option value="codigo">Código</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarA"
                    @keyup.enter="listarItem(buscarA, criterioA)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  >
                  <button
                    type="submit"
                    class="btn btn-primary"
                    @click="listarItem(buscarA, criterioA)"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in arrayItem" :key="item.id">
                    <template v-if="item.condicion">
                      <td>
                        <button
                          type="button"
                          class="btn btn-success btn-sm"
                          @click="agregarDetalleModal(item)"
                        >
                          <i class="icon-check"></i>
                        </button>
                      </td>
                      <td v-text="item.codigo"></td>
                      <td v-text="item.nombre"></td>
                      <td v-text="item.nombre_categoria"></td>
                      <td v-text="item.precio_venta"></td>
                      <td v-text="item.stock"></td>
                      <td>
                        <div v-if="item.condicion">
                          <span class="badge badge-success">Activo</span>
                        </div>
                        <div v-else>
                          <span class="badge badge-danger">Desactivado</span>
                        </div>
                      </td>
                    </template>

                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import {Joi} from 'vue-joi-validation'
export default {
  props: ['ruta'],
  data() {
    return {
      egreso_id: 0,
      tipo_comprobante: "BOLETA",
      num_comprobante: "",
      descripcion: "SALIDA",
      iva: 0.12,
      descuentoT: 0.0,
      total: 0.0,
      totalDescuento: 0.0,
      totalIVA: 0.0,
      totalSubtotal: 0.0,
      totalBI: 0.0,
      arrayEgreso: [],
      arrayDetalle: [],
      arrayDetalleEliminado: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorEgreso: 0,
      errorMostrarMsjEgreso: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "num_comprobante",
      buscar: "",
      criterioA: "nombre",
      buscarA: "",
      arrayItem: [],
      iditem: 0,
      codigo: "",
      item: "",
      precio: 0,
      precioC: 0,
      cantidad: 0,
      descuentoD: 0,
      stock: 0
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    calcularTotalSubtotal: function(){
      let resultado = 0.0;
      for (let i = 0; i < this.arrayDetalle.length; i++) {
        resultado = resultado+(this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - (this.arrayDetalle[i].precio*(this.arrayDetalle[i].descuento/100)));
      }
      return resultado;
    }
  },
  methods: {
    listarEgreso(page, buscar, criterio) {
      let me = this;
      let url = this.ruta +
        "/egreso?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayEgreso = respuesta.egresos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    buscarItem() {
      let me = this;
      let url = `${this.ruta}/item/buscarItem?filtro=${me.codigo}`;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          me.arrayItem = respuesta.items;

          if (me.arrayItem.length > 0) {
            me.item = me.arrayItem[0]["nombre"];
            me.iditem = me.arrayItem[0]["id"];
            me.precio = me.arrayItem[0]['precio_venta'];
            me.precioC = me.arrayItem[0]["precio_compra"];
            me.cantidad = 1;
            me.stock = me.arrayItem[0]["stock"];
          } else {
            me.item = "No existe item";
            me.iditem = 0;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarEgreso(page, buscar, criterio);
    },
    encuentra(id) {
      let sw = false;
      for (let i = 0; i < this.arrayDetalle.length; i++) {
        if (this.arrayDetalle[i].iditem == id) {
          sw = true;
        }
      }
      return sw;
    },
    eliminarDetalle(index) {
      let me = this;
      me.arrayDetalleEliminado.push(me.arrayDetalle[index]);
      me.arrayDetalle.splice(index, 1);
    },
    agregarDetalle() {
      let me = this;
      //agregar descuento
      if (me.iditem == 0 || me.cantidad == 0 || me.precio == 0) {
      } else {
        if (me.encuentra(me.iditem)) {
          Swal.fire({
            type: "error",
            text: "Error...",
            text: "Este item ya se encuentra agregado"
          });
        } else {
          if (me.cantidad>me.stock) {
            Swal.fire({
              type: "error",
              text: "Error...",
              text: "No hay stock disponible"
            });
          }else {
            me.arrayDetalle.push({
              iditem: me.iditem,
              item: me.item,
              cantidad: me.cantidad,
              precio: me.precio,
              precioC: me.precioC,
              descuento: me.descuentoD,
              stock: me.stock
            });
            me.codigo = "";
            me.iditem = 0;
            me.item = "";
            me.cantidad = 0;
            me.descuentoD = 0;
            me.precio = 0;
            me.precioC = 0;
            me.stock = 0;
          }
        }
      }
    },
    agregarDetalleModal(data = []) {
      let me = this;
      if (me.encuentra(data['id'])) {
        Swal.fire({
          type: "error",
          text: "Error...",
          text: "Este item ya se encuentra agregado"
        });
      } else {
        me.arrayDetalle.push({
          iditem: data['id'],
          item: data['nombre'],
          cantidad: 1,
          precio: data['precio_venta'],
          precioC: data['precio_compra'],
          descuento: 0,
          stock: data['stock']
        });
        for (let i = 0; i < me.arrayDetalleEliminado.length; i++) {
          if(me.arrayDetalleEliminado[i].iditem==data['id']){
            me.arrayDetalleEliminado.splice(i,1);
          }
        }
      }
    },
    listarItem(buscar, criterio) {
      let me = this;
      let url = `${this.ruta}/item/listarItem?buscar=${buscar}&criterio=${criterio}`;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          me.arrayItem = respuesta.items.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    registrarEgreso() {
      if (this.validarEgreso()) {
        return;
      }

      let me = this;

      axios
        .post(this.ruta+"/egreso/registrar", {
          'descripcion': this.descripcion,
          'tipo_comprobante': this.tipo_comprobante,
          'num_comprobante': this.num_comprobante,
          'descuento': this.descuentoT,
          'iva': this.iva,
          'total': this.total,
          'data': this.arrayDetalle
        })
        .then(function(response) {
          me.listado = 1;
          me.listarEgreso(1, "", "num_comprobante");
          me.descripcion = "SALIDA";
          me.tipo_comprobante = "BOLETA";
          me.num_comprobante = "";
          me.descuentoT = 0;
          me.iva = 0.12;
          me.total = 0.0;
          me.iditem = 0;
          me.item = "";
          me.cantidad = 0;
          me.descuentoD = 0;
          me.precio = 0;
          me.precioC = 0;
          me.stock = 0;
          me.arrayDetalle = [];
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarEgreso(){
      if (this.validarEgreso()) {
        return;
      }

      let me = this;

      axios.put(`${this.ruta}/egreso/actualizar?id=${me.egreso_id}`,
        {
          'descripcion': this.descripcion,
          'tipo_comprobante': this.tipo_comprobante,
          'num_comprobante': this.num_comprobante,
          'descuento': this.descuentoT,
          'iva': this.iva,
          'total': this.total,
          'data': this.arrayDetalle,
          'dataDeleted': me.arrayDetalleEliminado,
          'id': this.egreso_id
        }
      )
      .then(function(response) {
        me.listado = 1;
        me.listarEgreso(1, "", "num_comprobante");
        me.descripcion = "SALIDA";
        me.tipo_comprobante = "BOLETA";
        me.num_comprobante = "";
        me.descuentoT = 0;
        me.iva = 0.12;
        me.total = 0.0;
        me.iditem = 0;
        me.item = "";
        me.cantidad = 0;
        me.descuentoD = 0;
        me.precio = 0;
        me.precioC = 0;
        me.stock = 0;
        me.arrayDetalle = [];
        me.arrayDetalleEliminado = [];
        console.log(response.data)
      })
      .catch(function(error) {
        console.log(error);
      });
    },
    validarEgreso() {
      let me = this;
      me.errorEgreso = 0;
      me.errorMostrarMsjEgreso = [];
      let art;

      me.arrayDetalle.map(x => {
        if (x.cantidad > x.stock){
          art = x.articulo + " con stock insuficiente";
          me.errorMostrarMsjVenta.push(art);
        }
      });

      const schema = {
        num_comprobante: Joi.string().min(3).max(10).required(),
        descuentoT: Joi.number(),
        iva: Joi.number().precision(2).required()
      }

      const value = {
        num_comprobante: this.num_comprobante,
        descuentoT: this.descuentoT,
        iva: this.iva
      }

      const {error} = Joi.validate(value,schema);

      if(error) me.errorMostrarMsjEgreso.push(error.details[0].message);
      if(me.arrayDetalle.length<=0) me.errorMostrarMsjEgreso.push('Ingrese detalles');

      if (me.errorMostrarMsjEgreso.length) me.errorEgreso = 1;

      return me.errorEgreso;
    },
    mostrarDetalle() {
      this.listado = 0;
      this.descripcion = "SALIDA";
      this.tipo_comprobante = "BOLETA";
      this.num_comprobante = "";
      this.descuentoT = 0;
      this.iva = 0.12;
      this.total = 0.0;
      this.iditem = 0;
      this.item = "";
      this.cantidad = 0;
      this.precio = 0;
      this.precioC = 0;
      this.stock = 0;
      this.descuentoD = 0;
      this.arrayDetalle = [];
      this.arrayDetalleEliminado = [];
    },
    ocultarDetalle() {
      this.listado = 1;
      this.errorMostrarMsjEgreso = [];
    },
    verEgreso(id){
      let me = this;
      me.listado = 2;

      //obtener los datos del egreso
      let arrayEgresoT = [];
      me.arrayDetalleEliminado = [];
      me.arrayDetalle = [];
      let url =`${this.ruta}/egreso/obtenerCabecera?id=${id}`;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          arrayEgresoT = respuesta.egreso;

          me.egreso_id = arrayEgresoT[0]['id'];
          me.descripcion = arrayEgresoT[0]['descripcion'];
          me.tipo_comprobante = arrayEgresoT[0]['tipo_comprobante'];
          me.num_comprobante = arrayEgresoT[0]['num_comprobante'];
          me.descuentoT = arrayEgresoT[0]['descuento'];
          me.iva = arrayEgresoT[0]['iva'];
          me.total = arrayEgresoT[0]['total'];
        })
        .catch(function(error) {
          console.log(error);
        });
      //obtener los datos de los detalles
      let urld =`${this.ruta}/egreso/obtenerDetalles?id=${id}`;
      axios
        .get(urld)
        .then(function(response) {
          let respuesta = response.data;

          me.arrayDetalle = respuesta.detalles;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.arrayItem = [];
      this.modal = 1;
      this.tituloModal = "Seleccione uno o varios items";
    },
    desactivarEgreso(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Estás seguro de eliminar este egreso?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .delete(`${this.ruta}/egreso/eliminar/${id}`)
              .then(function(response) {
                me.listarEgreso(1, "", "num_comprobante");
                swalWithBootstrapButtons.fire(
                  "Anulado!",
                  "El egreso ha sido eliminado con éxito.",
                  "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          }
        });
    }
  },
  mounted() {
    this.listarEgreso(1, this.buscar, this.criterio);
  }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>
