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
          <i class="fa fa-align-justify"></i> Ingresos
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
                    @keyup.enter="listarIngreso(1,buscar,criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  >
                  <button
                    type="submit"
                    @click="listarIngreso(1,buscar,criterio)"
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
                    <th>Proveedor</th>
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
                  <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                    <td>
                      <button
                        type="button"
                        @click="verIngreso(ingreso.id)"
                        class="btn btn-warning btn-sm"
                      >
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="desactivarIngreso(ingreso.id)"
                        >
                          <i class="icon-trash"></i>
                        </button>
                    </td>
                    <td v-text="ingreso.usuario"></td>
                    <td v-text="ingreso.proveedor"></td>
                    <td v-text="ingreso.descripcion"></td>
                    <td v-text="ingreso.tipo_comprobante"></td>
                    <td v-text="ingreso.num_comprobante"></td>
                    <td v-text="ingreso.fecha_hora"></td>
                    <td v-text="ingreso.descuento"></td>
                    <td v-text="ingreso.iva"></td>
                    <td v-text="ingreso.total"></td>

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
              <div class="col-md-3">
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
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label >Descripcion(*)</label>
                  <select class="form-control" v-model="descripcion">
                    <option value="0">Seleccione</option>
                    <option value="ENTRADA">Entrada</option>
                    <option value="DEVOLUCION_E">Devolución</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for>Descuento (General)</label>
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
                <div v-show="errorIngreso" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjIngreso" v-text="error" :key="error"></div>
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
                    Descuento
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
                    <th>Descuento</th>
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
                  @click="registrarIngreso()"
                >Registrar Compra</button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin Detalle -->

        <!-- Ver ingreso -->
        <template v-else-if="listado==2">
          <div class="card-body">
            <div class="form-group row border">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Proveedor(*)</label>
                  <v-select
                    v-model="proveedor"
                    @search="selectProveedor"
                    label="nombre"
                    :options="arrayProveedor"
                    placeholder="Buscar Proveedores..."
                    @input="getDatosProveedor"

                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label >Descripcion(*)</label>
                  <select class="form-control" v-model="descripcion">
                    <option value="0">Seleccione</option>
                    <option value="ENTRADA">Entrada</option>
                    <option value="DEVOLUCION">Devolución</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for>Descuento</label>
                <input type="number" class="form-control" value="0" v-model="descuentoT">
              </div>
              <div class="col-md-3">
                <label for>IVA(*)</label>
                <input type="number" class="form-control" value="0" v-model="iva">
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
                <input type="number" class="form-control" value="0" v-model="num_comprobante">
              </div>
              <div class="col-md-12">
                <div v-show="errorIngreso" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjIngreso" v-text="error" :key="error"></div>
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
                    Descuento
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
                    <th>Descuento</th>
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
                      <td >
                        <input
                          v-model="detalle.precio"
                          type="number"
                          class="form-control"
                        >
                      </td>
                      <td >
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
                      <td>$ {{totalSubtotal=(calcularTotalSubtotal).toFixed(2)}}</td>
                    </tr>

                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Base Imponible:</strong>
                      </td>
                      <td>$ {{totalBI= parseFloat(totalSubtotal-totalDescuento).toFixed(2)}}</td>
                    </tr>

                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total Descuento:</strong>
                      </td>
                      <td>$ {{totalDescuento = parseFloat(totalSubtotal*(descuentoT/100)).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total IVA:</strong>
                      </td>
                      <td>$ {{totalIVA = parseFloat(totalBI*iva).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="5" align="right">
                        <strong>Total Neto:</strong>
                      </td>
                      <td>$ {{total=parseFloat((totalSubtotal-totalDescuento)+(totalBI*iva)).toFixed(2)}}</td>
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
                <button class="btn btn-primary" type="button" @click="actualizarIngreso()">Actualizar Ingreso</button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin ver ingreso -->
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
                    <th>Precio Compra</th>
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
                      <td v-text="item.precio_compra"></td>
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  props: ['ruta'],
  data() {
    return {
      ingreso_id: 0,
      idproveedor: 0,
      proveedor: '',
      descripcion: "ENTRADA",
      tipo_comprobante: "BOLETA",
      serie_comprobante: "",
      num_comprobante: "",
      iva: 0.12,
      descuentoT: 0.0,
      total: 0.0,
      totalDescuento: 0.0,
      totalIVA: 0.0,
      totalSubtotal: 0.0,
      totalBI: 0.0,
      arrayIngreso: [],
      arrayDetalle: [],
      arrayDetalleEliminado: [],
      arrayProveedor: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorIngreso: 0,
      errorMostrarMsjIngreso: [],
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
      descuentoD: 0,
      cantidad: 0,
      stock: 0
    };
  },
  components: {
    vSelect
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
    listarIngreso(page, buscar, criterio) {
      let me = this;
      let url = this.ruta +
        "/ingreso?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayIngreso = respuesta.ingresos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    selectProveedor(search, loading) {
      let me = this;
      loading(true);

      let url = `${this.ruta}/proveedor/selectProveedor?filtro=${search}`;

      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          console.log(respuesta.proveedores);
          me.arrayProveedor = respuesta.proveedores.filter(proveedor => proveedor.condicion === 1);
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosProveedor(val1) {
      let me = this;
      me.loading = true;
      me.idproveedor = val1.id;
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
      me.listarIngreso(page, buscar, criterio);
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
          me.arrayDetalle.push({
            iditem: me.iditem,
            item: me.item,
            cantidad: me.cantidad,
            precio: me.precio,
            descuento: me.descuentoD,
            stock: me.stock
          });
          me.codigo = "";
          me.iditem = 0;
          me.item = "";
          me.cantidad = 0;
          me.descuentoD = 0;
          me.precio = 0;
          me.stock = 0;
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
          precio: data['precio_compra'],
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
          me.arrayItem = respuesta.items.data.filter(item => item.condicion);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    registrarIngreso() {
      if (this.validarIngreso()) {
        return;
      }

      let me = this;

      axios
        .post(this.ruta+"/ingreso/registrar", {
          'idproveedor': this.idproveedor,
          'descripcion': this.descripcion,
          'tipo_comprobante': this.tipo_comprobante,
          'serie_comprobante': this.serie_comprobante,
          'num_comprobante': this.num_comprobante,
          'descuento': this.descuentoT,
          'iva': this.iva,
          'total': this.total,
          'data': this.arrayDetalle
        })
        .then(function(response) {
          me.listado = 1;
          me.listarIngreso(1, "", "num_comprobante");
          me.idproveedor = 0;
          me.tipo_comprobante = "BOLETA";
          me.serie_comprobante = "";
          me.num_comprobante = "";
          me.descuentoT = 0;
          me.iva = 0.12;
          me.total = 0.0;
          me.iditem = 0;
          me.item = "";
          me.cantidad = 0;
          me.precio = 0;
          me.descuentoD = 0;
          me.arrayDetalle = [];
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarIngreso(){
      if (this.validarIngreso()) {
        return;
      }

      let me = this;

      axios
        .put(this.ruta+"/ingreso/actualizar", {
          'idproveedor': me.idproveedor,
          'descripcion': me.descripcion,
          'tipo_comprobante': me.tipo_comprobante,
          'serie_comprobante': me.serie_comprobante,
          'num_comprobante': me.num_comprobante,
          'descuento': me.descuentoT,
          'iva': me.iva,
          'total': me.total,
          'data': me.arrayDetalle,
          'dataDeleted': me.arrayDetalleEliminado,
          'id': me.ingreso_id
        })
        .then(function(response) {
          me.listado = 1;
          me.listarIngreso(1, "", "num_comprobante");
          me.descripcion = "SALIDA";
          me.tipo_comprobante = "BOLETA";
          me.serie_comprobante = "";
          me.num_comprobante = "";
          me.descuentoT = 0;
          me.iva = 0.12;
          me.total = 0.0;
          me.iditem = 0;
          me.item = "";
          me.cantidad = 0;
          me.descuentoD = 0;
          me.precio = 0;
          me.arrayDetalle = [];
          me.arrayDetalleEliminado = [];
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    validarIngreso() {
      this.errorIngreso = 0;
      this.errorMostrarMsjIngreso = [];

      if(this.idproveedor==0) this.errorMostrarMsjIngreso.push('Seleccione un Proveedor');
      if(!this.descripcion) this.errorMostrarMsjIngreso.push('Seleccione la descripción del ingreso');
      if(this.tipo_comprobante==0) this.errorMostrarMsjIngreso.push('Seleccione el comprobante');
      if(!this.num_comprobante) this.errorMostrarMsjIngreso.push('Ingrese el número de comprobante');
      if(!this.iva) this.errorMostrarMsjIngreso.push('Ingrese el iva');
      if(this.arrayDetalle.length<=0) this.errorMostrarMsjIngreso.push('Ingrese detalles');

      if (this.errorMostrarMsjIngreso.length) this.errorIngreso = 1;

      return this.errorIngreso;
    },
    mostrarDetalle() {
      this.listado = 0;
      this.idproveedor = 0;
      this.descripcion = "ENTRADA";
      this.tipo_comprobante = "BOLETA";
      this.serie_comprobante = "";
      this.num_comprobante = "";
      this.descuentoT = 0;
      this.iva = 0.12;
      this.total = 0.0;
      this.iditem = 0;
      this.item = "";
      this.cantidad = 0;
      this.precio = 0;
      this.descuentoD = 0;
      this.stock = 0;
      this.arrayDetalle = [];
      this.arrayProveedor = [];
      this.arrayDetalleEliminado = [];
    },
    ocultarDetalle() {
      this.listado = 1;
      this.errorMostrarMsjIngreso = [];
    },
    verIngreso(id){
      let me = this;
      me.listado = 2;

      //obtener los datos del ingreso
      let arrayIngresoT = [];
      me.arrayDetalleEliminado = [];
      me.arrayDetalle = [];
      let url =`${this.ruta}/ingreso/obtenerCabecera?id=${id}`;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          arrayIngresoT = respuesta.ingreso;

          me.ingreso_id = arrayIngresoT[0]['id'];
          me.idproveedor = arrayIngresoT[0]['idproveedor'];
          me.proveedor = arrayIngresoT[0]['proveedor'];
          me.descripcion = arrayIngresoT[0]['descripcion'];
          me.tipo_comprobante = arrayIngresoT[0]['tipo_comprobante'];
          me.serie_comprobante = arrayIngresoT[0]['serie_comprobante'];
          me.num_comprobante = arrayIngresoT[0]['num_comprobante'];
          me.descuentoT = arrayIngresoT[0]['descuento'];
          me.iva = arrayIngresoT[0]['iva'];
          me.total = arrayIngresoT[0]['total'];
        })
        .catch(function(error) {
          console.log(error);
        });
      //obtener los datos de los detalles
      let urld =`${this.ruta}/ingreso/obtenerDetalles?id=${id}`;
      axios
        .get(urld)
        .then(function(response) {
          let respuesta = response.data;
          arrayIngresoT = respuesta.detalles;

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
    desactivarIngreso(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Estás seguro de eliminar este ingreso?",
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
              .delete(`${this.ruta}/ingreso/eliminar/${id}`)
              .then(function(response) {
                me.listarIngreso(1, "", "num_comprobante");
                swalWithBootstrapButtons.fire(
                  "Eliminado!",
                  "El ingreso ha sido eliminado con éxito.",
                  "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          }
        });
    },

  },
  mounted() {
    this.listarIngreso(1, this.buscar, this.criterio);
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
