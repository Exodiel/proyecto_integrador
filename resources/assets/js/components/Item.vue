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
          <i class="fa fa-align-justify"></i> Items
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('item', 'registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio" >
                  <option value="nombre">Nombre</option>
                  <option value="descripcion">Descripción</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarItem(1, buscar, criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                >
                <button type="submit" class="btn btn-primary" @click="listarItem(1, buscar, criterio)">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-responsive table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in arrayItem" :key="item.id">
                <td>
                  <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    @click="abrirModal('item','actualizar', item)"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <template v-if="item.condicion">
                    <button
                      class="btn btn-danger btn-sm"
                      @click="desactivarItem(item.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button class="btn btn-info btn-sm" @click="activarItem(item.id)">
                      <i class="icon-check"></i>
                    </button>
                  </template> &nbsp;
                  <template v-if="item.condicion">
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="cargarPdf(item.id)"
                    >
                      <i class="icon-doc"></i>
                    </button>
                  </template>

                </td>
                <td v-text="item.codigo"></td>
                <td v-text="item.nombre"></td>
                <td v-text="item.nombre_categoria"></td>
                <td v-text="item.precio_compra"></td>
                <td v-text="item.precio_venta"></td>
                <td v-text="item.stock"></td>
                <td v-text="item.descripcion"></td>
                <td>
                  <div v-if="item.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current - 1, buscar, criterio)">Ant</a>
              </li>
              <li class="page-item" v-for="page in pageNumber" :key="page" :class="[page == isActived ? 'active': '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page" ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)" >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar': modal}"
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
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="idcategoria" >
                    <option value="0" disabled>Seleccione</option>
                    <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Código</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="codigo"
                    class="form-control"
                    placeholder="Código del Item"
                  >
                  <barcode :value="codigo" :option="{ format: 'EAN-13' }">
                    Generando código de barras.
                  </barcode>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre del Item"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Precio de Compra</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model.number="precio_compra"
                    class="form-control"

                  >
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-md-3 form-control-label">Porcentaje de Utilidad</label>
                <div class="col-md-9">
                  <select class="form-control" v-model.number="utilidad">
                    <option value="0">0%</option>
                    <option value="20">20%</option>
                    <option value="25">25%</option>
                    <option value="30">30%</option>
                    <option value="35">35%</option>
                    <option value="40">40%</option>
                    <option value="45">45%</option>
                    <option value="50">50%</option>
                    <option value="55">55%</option>
                    <option value="60">60%</option>
                    <option value="65">65%</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Precio de Venta</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model.number="calcularPV"
                    class="form-control"

                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model.number="stock"
                    class="form-control"

                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Ingrese descripción"
                  >
                </div>
              </div>
              <div v-show="errorItem" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjItem" v-text="error" :key="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              @click="registrarItem()"
              class="btn btn-primary"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarItem()"
            >Actualizar</button>
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
import VueBarcode from 'vue-barcode';
export default {
  props: ["ruta"],
  data() {
    return {
      item_id: 0,
      idcategoria: 0,
      nombre_categoria: '',
      codigo: '',
      nombre: '',
      precio_compra: 0,
      utilidad: 0,
      precio_venta: 0,
      stock: 0,
      descripcion: '',
      arrayItem: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorItem: 0,
      errorMostrarMsjItem: [],
      pagination: {
        'total':0  ,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0
      },
      offset: 3,
      criterio: 'nombre',
      buscar: '',
      arrayCategoria: []
    };
  },
  components: {
    'barcode': VueBarcode
  },
  computed: {
      isActived: function () {
        return this.pagination.current_page;
      },
      pageNumber: function() {
        if(!this.pagination.to) {
          return [];
        }
        let from = this.pagination.current_page - this.offset;
        if (from < 1) {
          from = 1;
        }
        let to = from + (this.offset * 2);
        if(to >= this.pagination.last_page){
          to = this.pagination.last_page;
        }
        let pagesArray = [];
        while (from <= to) {
          pagesArray.push(from);
          from++;
        }
        return pagesArray;
      },
      calcularPV: function(){
        this.precio_venta = (parseFloat(this.precio_compra)+(parseFloat(this.precio_compra)*(parseFloat(this.utilidad)/100))).toFixed(2);
        return (parseFloat(this.precio_compra)+(parseFloat(this.precio_compra)*(parseFloat(this.utilidad)/100))).toFixed(2);
      }
  },
  methods: {
    listarItem(page, buscar, criterio) {
      let me = this;
      let url = this.ruta + "/item?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          me.arrayItem = respuesta.items.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cargarPdf(id){
      window.open(`${this.ruta}/item/listarPdf/${id}`, '_blank')
    },
    selectCategoria(){
      let me = this;
      let url = this.ruta + "/categoria/selectCategoria";
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          me.arrayCategoria = respuesta.categorias;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio){
      let me = this;
      me.pagination.current_page = page;
      me.listarItem(page, buscar, criterio);
    },
    registrarItem() {
      if (this.validarItem()) {
        return;
      }

      let me = this;
      axios
        .post(this.ruta + "/item/registrar", {
          'idcategoria': me.idcategoria,
          'codigo': me.codigo,
          'nombre': me.nombre,
          'stock': me.stock,
          'precio_compra': me.precio_compra,
          'utilidad': me.utilidad,
          'precio_venta': me.precio_venta,
          'descripcion': me.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarItem(1, '', 'nombre');
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarItem() {
      if (this.validarItem()) {
        return;
      }

      let me = this;
      axios
        .put(this.ruta+"/item/actualizar", {
          'idcategoria': me.idcategoria,
          'codigo': me.codigo,
          'nombre': me.nombre,
          'stock': me.stock,
          'precio_compra': me.precio_compra,
          'utilidad': me.utilidad,
          'precio_venta': me.precio_venta,
          'descripcion': me.descripcion,
          'id': me.item_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarItem(1, '', 'nombre');
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    desactivarItem(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Estás seguro de desactivar este item?",
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
              .put(this.ruta+"/item/desactivar", {
                'id': id
              })
              .then(function(response) {
                me.listarItem(1, '', 'nombre');
                swalWithBootstrapButtons.fire(
                    "Desactivado!",
                    "El registro ha sido desactivado con éxito.",
                    "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });

          }
        });
    },
    activarItem(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Estás seguro de activar este item?",
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
              .put(this.ruta+"/item/activar", {
                'id': id
              })
              .then(function(response) {
                me.listarItem(1, '', 'nombre');
                swalWithBootstrapButtons.fire(
                    "Activado!",
                    "El registro ha sido activado con éxito.",
                    "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          }
        });
    },
    validarItem() {
      this.errorItem = 0;
      this.errorMostrarMsjItem = [];

      const schema = {
        nombre: Joi.string().min(2).max(100).required(),
        stock: Joi.number().min(1).required(),
        precio_compra: Joi.number().min(1).precision(2).required(),
        precio_venta: Joi.number().min(1).precision(2).required()
      }

      const value = {
        nombre: this.nombre,
        stock: this.stock,
        precio_compra: this.precio_compra,
        precio_venta: this.precio_venta
      }

      const {error} = Joi.validate(value,schema)

      if(!/^([a-zA-ZñÑáéíóúÁÉÍÓÚ])*$/.test(this.nombre)) this.errorMostrarMsjItem.push('El nombre solo permite letras');
      if(!/^([0-9])*$/.test(this.codigo)) this.errorMostrarMsjItem.push('El codigo solo permite números');
      if(!/^([a-zA-ZñÑáéíóúÁÉÍÓÚ])*$/.test(this.descripcion)) this.errorMostrarMsjItem.push('La descripción solo permite letras');

      if(this.idcategoria == 0) this.errorMostrarMsjItem.push('Seleccione una categoría.');
      if (error)this.errorMostrarMsjItem.push(error.details[0].message);


      if (this.errorMostrarMsjItem.length) this.errorItem = 1;

      return this.errorItem;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.idcategoria = 0;
      this.nombre_categoria = '';
      this.codigo = '';
      this.nombre = "";
      this.precio_compra = 0;
      this.utilidad = 0;
      this.precio_venta = 0;
      this.stock = 0;
      this.descripcion = "";
      this.errorItem = 0;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {

        case "item": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Item";
              this.idcategoria = 0;
              this.nombre_categoria = '';
              this.codigo = '';
              this.nombre = "";
              this.precio_compra= 0;
              this.utilidad = 0;
              this.precio_venta = 0;
              this.stock = 0;
              this.descripcion = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              //console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar Item";
              this.tipoAccion = 2;
              this.item_id = data["id"];
              this.idcategoria = data["idcategoria"];
              this.codigo = data["codigo"];
              this.nombre = data["nombre"];
              this.precio_compra = data["precio_compra"];
              this.utilidad = data["utilidad"];
              // this.precio_venta = data["precio_venta"];

              this.stock = data["stock"];
              this.descripcion = data["descripcion"];
              break;
            }
          }
        }

      }
      this.selectCategoria();
    }
  },
  mounted() {
    this.listarItem(1, this.buscar, this.criterio);
  }
};
</script>
<style >
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
  overflow-y: auto;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>
