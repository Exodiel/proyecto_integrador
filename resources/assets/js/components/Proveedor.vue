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
          <i class="fa fa-align-justify"></i> Proveedores
          <button
            type="button"
            @click="abrirModal('proveedor','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="num_documento">Documento</option>
                  <option value="telefono">Teléfono</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarProveedor(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                >
                <button
                  type="submit"
                  @click="listarProveedor(1,buscar,criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-responsive table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Tipo Documento</th>
                <th>Número</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="proveedor in ArrayProveedor" :key="proveedor.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('proveedor','actualizar',proveedor)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <template v-if="proveedor.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="desactivarProveedor(proveedor.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarProveedor(proveedor.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
                <td v-text="proveedor.nombre"></td>
                <td v-text="proveedor.tipo_documento"></td>
                <td v-text="proveedor.num_documento"></td>
                <td v-text="proveedor.direccion"></td>
                <td v-text="proveedor.telefono"></td>
                <td>
                  <div v-if="proveedor.condicion">
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
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
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
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre del proveedor"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Tipo documento</label>
                <div class="col-md-9">
                  <select v-model="tipo_documento" class="form-control">
                    <option value="RUC_PN">RUC PERSONA NATURAL</option>
                    <option value="RUC_SPR">RUC SOCIEDAD PRIVADA</option>
                    <option value="RUC_SP">RUC SOCIEDAD PÚBLICA</option>
                    <option value="CEDULA">CEDULA</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Número documento (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    :maxlength="maximoCaracteres"
                    v-model="num_documento"
                    class="form-control"
                    placeholder="Número de documento"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Dirección (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="direccion"
                    class="form-control"
                    placeholder="Dirección"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Teléfono (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="telefono"
                    class="form-control"
                    placeholder="Teléfono"
                  >
                </div>
              </div>

              <div v-show="errorProveedor" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjProveedor" :key="error" v-text="error"></div>
                </div>
              </div>
              <div v-show="errorProveedor" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorDocumento" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              class="btn btn-primary"
              @click="registrarProveedor()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarProveedor()"
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
import {strlen, str_split, substr} from 'locutus/php/strings'
import {array_sum} from 'locutus/php/array'
import {empty} from 'locutus/php/var'

export default {
  props: ["ruta"],
  data() {
    return {
      proveedor_id: 0,
      nombre: "",
      tipo_documento: "RUC_PN",
      num_documento: "",
      direccion: "",
      telefono: "",
      ArrayProveedor: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorProveedor: 0,
      errorMostrarMsjProveedor: [],
      errorDocumento: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
      buscar: ""
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
    maximoCaracteres: function () {
      return this.tipo_documento === 'CEDULA' ? 10 : 13;
    }
  },
  methods: {
    listarProveedor(page, buscar, criterio) {
      let me = this;
      var url = this.ruta +
        "/proveedor?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.ArrayProveedor = respuesta.proveedores.data;
          me.pagination = respuesta.pagination;

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
      me.listarProveedor(page, buscar, criterio);
    },
    registrarProveedor() {
      if (this.validarProveedor()) {
        return;
      }

      let me = this;

      axios
        .post(this.ruta +"/proveedor/registrar", {
          'nombre': this.nombre,
          'tipo_documento': this.tipo_documento,
          'num_documento': this.num_documento,
          'direccion': this.direccion,
          'telefono': this.telefono
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarProveedor(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarProveedor() {
      if (this.validarProveedor()) {
        return;
      }

      let me = this;

      axios
        .put(this.ruta+"/proveedor/actualizar", {
          'nombre': this.nombre,
          'tipo_documento': this.tipo_documento,
          'num_documento': this.num_documento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'id': this.proveedor_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarProveedor(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    validarProveedor() {
      this.errorProveedor = 0;
      this.errorMostrarMsjProveedor = [];

      const schema = {
        nombre : Joi.string().min(4).max(100).required()
      }

      const value = {
        nombre : this.nombre
      }

      const {error} = Joi.validate(value,schema);

      if(!/^([a-zA-ZñÑáéíóúÁÉÍÓÚ])*$/.test(this.nombre)) this.errorMostrarMsjProveedor.push("Ingrese solo letras en el nombre");
      if(!/^([a-zA-ZñÑáéíóúÁÉÍÓÚ0-9])*$/.test(this.direccion)) this.errorMostrarMsjProveedor.push("No ingrese carácteres especiales");
      if(!/^([0-9])*$/.test(this.telefono)) this.errorMostrarMsjProveedor.push('Ingrese solo números');

      if (error) this.errorMostrarMsjProveedor.push(error.details[0].message);

      if (this.tipo_documento === 'CEDULA') {
        if (!this.validarCedula(this.num_documento)) this.errorProveedor = 1;
      } else if(this.tipo_documento === 'RUC_PN') {
        if (!this.validarRucPersonaNatural(this.num_documento)) this.errorProveedor = 1;
      } else if (this.tipo_documento === 'RUC_SPR') {
        if (!this.validarRucSociedadPrivada(this.num_documento)) this.errorProveedor = 1;
      }else {
         if (!this.validarRucSociedadPublica(this.num_documento)) this.errorProveedor = 1;
      }

      if (this.errorMostrarMsjProveedor.length) this.errorProveedor = 1;

      return this.errorProveedor;
    },
    validarCedula(numero = '') {

      if (!this.validarInicial(numero, '10') &&
      !this.validarCodigoProvincia(substr(numero,0,2)) &&
      !this.validarTercerDigito(numero[2], 'cedula') &&
      !this.algoritmoModulo10(substr(numero,0,9), numero[9])) {
        return false;
      }

      return true;
    },
    validarRucPersonaNatural(numero = ''){

      if (!this.validarInicial(numero, '13') &&
      !this.validarCodigoProvincia(substr(numero,0,2)) &&
      !this.validarTercerDigito(numero[2], 'ruc_natural') &&
      !this.algoritmoModulo10(substr(numero,0,9), numero[9])) {
        return false;
      }

      return true;
    },
    validarRucSociedadPrivada(numero = ''){
      if (!this.validarInicial(numero, '13') &&
      !this.validarCodigoProvincia(substr(numero,0,2)) &&
      !this.validarTercerDigito(numero[2], 'ruc_privada') &&
      !this.algoritmoModulo11(substr(numero,0,9), numero[9], 'ruc_privada')) {
        return false;
      }

      return true;
    },
    validarRucSociedadPublica(numero = ''){
      if (!this.validarInicial(numero, '13') &&
      !this.validarCodigoProvincia(substr(numero,0,2)) &&
      !this.validarTercerDigito(numero[2], 'ruc_publica') &&
      !this.validarCodigoEstablecimiento(substr(numero, 9, 4)) &&
      !this.algoritmoModulo11(substr(numero,0,9), numero[9], 'ruc_publica')) {
        return false;
      }

      return true;
    },
    validarInicial(numero, caracteres){
      if(empty(numero)){
        this.errorDocumento.push("Número de documento no puede estar vacío");
        return false;
      }
      if(!/^\d+$/.test(numero)) {
        this.errorDocumento.push("Valor ingresado solo puede tener dígitos");
        return false;
      }
      if (strlen(numero) != caracteres) {
        this.errorDocumento.push(`Valor ingresado debe tener ${caracteres} caracteres`);
        return false;
      }

      return true;

    },
    validarCodigoProvincia(numero){
      if (numero < 0 || numero > 24) {
        this.errorDocumento.push(`Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0`);
        return false;
      }

      return true;
    },
    validarTercerDigito(numero, tipo){
      switch (tipo) {
        case 'cedula':
          if (numero < 0 || numero < 5) {
            this.errorDocumento.push("Tercer dígito debe ser menor a  6");
            return false;
          }
          break;
        case 'ruc_natural':
          if (numero < 0 || numero > 5) {
            this.errorDocumento('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
            return false;
          }
          break;

        case 'ruc_privada':
            if (numero != 9) {
              this.errorDocumento('Tercer dígito debe ser igual a 9 para sociedades privadas');
              return false;
            }
          break;

        case 'ruc_publica':
          if (numero != 6) {
            this.errorDocumento('Tercer dígito debe ser igual a 6 para sociedades públicas');
            return false;
          }
          break;

        default:
          this.errorDocumento('Tipo de identificación no existe');
          break;
      }

      return true;
    },
    validarCodigoEstablecimiento(numero){
      if(numero < 1){
        this.errorDocumento.push("Código de establecimiento no puede ser cero");
        return false;
      }
      return true;
    },
    algoritmoModulo10(digitosIniciales, digitoVerificador){
      let arrCoeficientes = [2,1,2,1,2,1,2,1,2];

      digitoVerificador = parseInt(digitoVerificador);
      digitosIniciales = str_split(digitosIniciales);

      let total = 0;

      digitosIniciales.forEach((val, i) => {
        let valorPosicion = (parseInt(val) * arrCoeficientes[i]);

        if (valorPosicion >= 10) {
          valorPosicion = str_split(valorPosicion);
          valorPosicion = array_sum(valorPosicion);
          valorPosicion = parseInt(valorPosicion);
        }

        total = total + valorPosicion;
      });

      const residuo = total % 10;

      let resultado = 0;

      if (residuo == 0) {
        resultado = 0;
      }else {
        resultado = 10 - residuo;
      }

      if (resultado != digitoVerificador) {
        this.errorDocumento.push('Dígitos iniciales no validan contra Dígito Idenficador');
        return false;
      }

      return true;
    },
    algoritmoModulo11(digitosIniciales, digitoVerificador, tipo){
      let arrCoeficientes = [];
      switch (tipo) {
        case 'ruc_privada':
          arrCoeficientes = [4, 3, 2, 7, 6, 5, 4, 3, 2];
          break;
        case 'ruc_publica':
          arrCoeficientes = [3, 2, 7, 6, 5, 4, 3, 2];
          break;

        default:
          this.errorDocumento.push("Tipo de identificación no existe");
          break;
      }

      digitoVerificador = parseInt(digitoVerificador);
      digitosIniciales = str_split(digitosIniciales);

      let total = 0;

      digitosIniciales.forEach((val,i) => {
        let valorPosicion = (parseInt(val) * arrCoeficientes[i]);
        total = total + valorPosicion;
      });

      const residuo = total % 11;
      let resultado = 0;

      if (residuo == 0) {
        resultado = 0;
      }else {
        resultado = 11 - residuo;
      }

      if (resultado != digitoVerificador) {
        this.errorDocumento.push('Dígitos iniciales no validan contra Dígito Idenficador');
        return false;
      }

      return true;

    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.tipo_documento = "RUC_PN";
      this.num_documento = "";
      this.direccion = "";
      this.telefono = "";
      this.errorProveedor = 0;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "proveedor": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Proveedor";
              this.nombre = "";
              this.tipo_documento = "RUC_PN";
              this.num_documento = "";
              this.direccion = "";
              this.telefono = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              //console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar Proveedor";
              this.tipoAccion = 2;
              this.proveedor_id = data["id"];
              this.nombre = data["nombre"];
              this.tipo_documento = data["tipo_documento"];
              this.num_documento = data["num_documento"];
              this.direccion = data["direccion"];
              this.telefono = data["telefono"];
              break;
            }
          }
        }
      }
    },
    desactivarProveedor(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Estás segura de desactivar este proveedor?",
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
              .put(this.ruta+"/proveedor/desactivar", {
                'id': id
              })
              .then(function(response) {
                me.listarProveedor(1, "", "nombre");
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
    activarProveedor(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Estás segura de activar este proveedor?",
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
              .put(this.ruta+"/proveedor/activar", {
                'id': id
              })
              .then(function(response) {
                me.listarProveedor(1, "", "nombre");
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
    }
  },
  mounted() {
    this.listarProveedor(1, this.buscar, this.criterio);
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
