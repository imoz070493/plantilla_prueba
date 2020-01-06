<template>
  <div>
    <!--
    <b-row>
      <b-col sm="4">
        <label>Buscar:</label>
      </b-col>
      <b-col sm="8">
        <input
          v-model="persona.nombre"
          placeholder="nombre, apellidos o documentos"
          class="form-control"
        />
      </b-col>
    </b-row>
    -->
    <!--
      Todo: Crear filas seleccionables con el cursor o con el teclado
    -->
    <div class="table_container">
      <table class="table">
        <thead>
          <tr>
            <th>N°</th>
            <th
              v-for="head in columns"
              v-if="head.visible"
              :class="head.class"
              :width="head.width+'px'"
            >{{ head.text }}</th>
            <!-- visualizacion condicional de la columna acciones -->
            <td v-if="inline_actions">Acciones</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item,key) in array_personas">
            <th>{{key}}</th>
            <td
              v-for="head in columns"
              v-if="head.visible"
              :class="item.class"
            >{{format(head,item,key)}}</td>

            <th v-if="inline_actions">
              <b-button type="b-button" @click="borrar(item,key)">borrar</b-button>
              <b-button type="b-button" @click="editar(item,key)">editar</b-button>
            </th>
          </tr>
        </tbody>
      </table>
    </div>
    <b-row>
      <b-col v-if="ver_botones">
        <b-button type="b-button" @click="crear">Nuevo</b-button>
        <b-button type="b-button" @click="importar">Importar XLS</b-button>
        <a v-bind:href="'#'" @click="exportar()">
          <b-button type="b-button" title="Exportar">Exportar XLS</b-button>
        </a>
      </b-col>
      <b-col>
        <div class="pagination">
          <b-button
            class="btn btn-default"
            v-on:click="fetchPaginationPersonas(pagination.prev_page_url)"
            :disabled="!pagination.prev_page_url"
          ><<</b-button>
          <label>{{pagination.current_page}} de {{pagination.last_page}}</label>
          <b-button
            class="btn btn-default"
            v-on:click="fetchPaginationPersonas(pagination.next_page_url)"
            :disabled="!pagination.next_page_url"
          >>></b-button>
        </div>
      </b-col>
    </b-row>

    <!-- Crear Nuevo -->
    <!-- Usamos componente propio de dialogos -->
    <el-dialog title="Registrar Persona" :visible_lock="nuevo && nuevo._estado=='creando'">
      <!-- Aqui va el contenido del dialogo, , es necesario espeficicar el ref del componente para acceder a sus metodos -->
      <v-formulario-persona v-model="nuevo" ref="cmp_crear_persona" @guardado="actualizar()"></v-formulario-persona>

      <!-- Aqui va el pie de ventana con sus botones, notese que enlazamos a metodos dentro del componente -->
      <template v-slot:footer>
        <!-- contenido -->
        <b-button variant="danger" @click="$refs.cmp_crear_persona.cancelar()">Cancelar</b-button>
        <b-button variant="success" @click="$refs.cmp_crear_persona.guardar()">Guardar</b-button>
      </template>
    </el-dialog>
    <!-- Editar -->
    <el-dialog title="Editar Persona" :visible_lock="editable && editable._estado=='editando'">
      <!-- 
        Personalizamos el encabezado (opcional) 
      -->
      <!--
      <template v-slot:header="{ close }">
        <template
          v-if="editable.id"
        >Editar persona: {{editable.nombre}} {{editable.apellido_paterno}} {{editable.apellido_materno}}</template>
        <template v-else>Agregar persona</template>
      </template>
      -->
      <v-formulario-persona v-model="editable" ref="cmp_editar_persona" @guardado="actualizar()"></v-formulario-persona>
      <template v-slot:footer>
        <b-button variant="danger" @click="$refs.cmp_editar_persona.cancelar()">Cancelar</b-button>
        <b-button variant="success" @click="$refs.cmp_editar_persona.guardar()">Guardar</b-button>
      </template>
    </el-dialog>

    <!-- importar -->

    <el-dialog
      title="Importar Personas"
      ref="dlg_importando"
      :visible_lock="dlg_importar && dlg_importar._estado=='importando'"
      okTitle="Importar"
    >
      <v-importar-personas
        ref="cmp_importar_personas"
        v-model="dlg_importar"
        @importado="actualizar()"
      ></v-importar-personas>
      <template v-slot:footer>
        <b-button variant="danger" @click="$refs.cmp_importar_personas.cancelar()">Cancelar</b-button>
        <b-button variant="success" @click="$refs.cmp_importar_personas.importar()">Importar</b-button>
      </template>
    </el-dialog>
  </div>
</template>
<script>
//las funciones de la api las llamamos desde su propia libreria, esto es para que 1) podamos guardar todos los metodos de acceso a la api en un solo lugar y 2) para poder reusar esas funciones en otros componentes
import {
  listarPersonas,
  buscarPersonas,
  borrarPersonas,
  getPersonaColumns,
  reportePersonasExcel
} from "@/api/persona.js";
//registramos el componente para que solo se use en esta vista y solo se use cuando se necesita, si otro componente vuelve a importar este compnente se reusara el que se encuentra ya cargado en memoria
import FormularioPersona from "@/components/rrhh/persona/FormularioPersona";
import ImportarPersona from "@/components/rrhh/persona/ImportarPersona";

export default {
  //registramos el componente
  components: {
    "v-formulario-persona": FormularioPersona,
    "v-importar-personas": ImportarPersona
  },
  props: {
    value: Object,
    //pasar flag para mostrar u ocultar la columna de acciones (se usara la barra de herramientas), predeterminado False ( la razon es debido a un acuerdo interno mas que a algun standard)
    inline_actions: {
      type: Boolean,
      default: true
    },
    ver_botones: {
      type: Boolean,
      default: true
    }
  },
  created: function() {},
  mounted() {
    /* actualizar actualizar cada vez que se accede a la lista */
    this.actualizar();
  },
  data() {
    return {
      nuevo: {},
      editable: {},
      filtros: Object.assign({}, this.value),
      pagination: [],
      paginando: false,
      persona: { nombre: "" },
      array_personas: [],

      //VARIABLES DINAMICAS
      dlg_importar: {}, // inicializamos como objeto
      /* posicion inicial */
      startIndex: 0,
      /* items por pagina */
      num: 2,
      /* actualizar a mostrar (paginado) */
      columns: getPersonaColumns()
    };
  },
  methods: {
    format(column, item) {
      if (column.field) {
        if (column.fn) {
          return column.fn(item[column.field]);
        } else {
          return item[column.field];
        }
      } else {
        if (column.fn) {
          return column.fn(item);
        }
      }
    },
    actualizar() {
      let self = this;
      /** Todo: pasar parametros de la paginacion a la vista api {pagina: ## cantidad: ##} */
      listarPersonas(this.paginando)
        .then(personas => {
          self.array_personas = personas.data;
          self.makePagination(personas);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    buscar(name) {
      let self = this;
      this.filtros.q = name;
      buscarPersonas(this.filtros, this.paginando)
        .then(personas => {
          self.array_personas = personas.data;
          self.makePagination(personas);
        })
        .catch(function(error) {
          console.log(error);
        });
      //en lugar de pasar como slugs, mejor usar parametros
      //getUrl(this.url_buscar + name, this.paginando)
    },
    fetchPaginationPersonas(url) {
      /**
       * todo: Corregir paginacion
       * pasar como parametros el numero de pagina y el numero de items a la funcion listar/buscar
       */
      this.paginando = true;
      this.url = url;
      this.actualizar();
    },
    makePagination(data) {
      let pagination = {
        current_page: data.current_page,
        last_page: data.last_page,
        next_page_url: data.next_page_url,
        prev_page_url: data.prev_page_url
      };
      this.pagination = pagination;
    },
    crear() {
      this.nuevo = { _estado: "creando" };
    },
    editar(e, key) {
      this.editable = this.array_personas[key];
      e._estado = "editando";
    },
    borrar(e, key) {
      let self = this;
      if (confirm("esta seguro de borrar")) {
        /**
         * ToDo: permitir que funcion borrar pueda pasar un listado de items y luego en el api pasar como parametro esa lista
         */
        borrarPersonas(e.id)
          .then(function(response) {
            self.actualizar();
          })
          .catch(function(e) {
            alert("error al borrar " + e);
          });
      }
    },

    importar() {
      /**
       * 1) No reutiliza variables que no esten relacionadas
       * 2) para campos de control interno usemos _antesDelAtributo p.e. _estado, _loading
       */
      this.dlg_importar = { _estado: "importando" };
    },
    excelChanged(e) {
      console.log(e.target.files[0]);

      var fileReader = new FileReader();

      fileReader.readAsDataURL(e.target.files[0]);
      fileReader.onload = e => {
        this.editable.excel_document = e.target.result;
        postUrl("/reporte_excel/import", this.editable)
          .then(function(response) {
            /*self.editable._estado = "guardado";
          console.log(response.data.data)
          self.editable = response.data.data;
          delete self.editable._estado;
          self.$emit("input", self.editable);*/
          })
          .catch(function(error) {
            if (error.response.status == 422) {
              self.errors = error.response.data.errors;
              console.log(self.errors);
            }
          });
      };
    },
    exportar() {
      reportePersonasExcel(this.filtros);
    }
  },
  watch: {
    /*
    "persona.nombre": function(newvalue, oldvalue) {
      console.log(this.persona.nombre);
      this.buscar(this.persona.nombre);
    },
    value(newvalue, oldvalue) {
      // creamos una copia para trabajar localmente 
      this.editable = Object.assign({}, newvalue);
    },
    nuevo: function(newvalue, oldvalue) {
      //console.log(newvalue+"  --   "+oldvalue)
      //console.log("Function nuevo "+newvalue.doc)
      if (newvalue && newvalue.id) {
        //enviamos una copia
        //this.personas.push(Object.assign({}, newvalue));
        //console.log(newvalue.dni)
        this.actualizar();
      }
    },
    editable: function(newvalue, oldvalue) {
      //console.log(newvalue+"  --   "+oldvalue)
      //console.log("Function nuevo "+newvalue)
      if (newvalue && newvalue._estado != oldvalue._estado) {
        //enviamos una copia
        //this.personas.push(Object.assign({}, newvalue));
        //console.log(newvalue.dni)
        this.actualizar();
      }
    }
    */
  }
};
</script>
<style lang="css">
.nuevo > *,
.editable > * {
  position: fixed;
  top: 20px;
  left: 20px;
  right: 20px;
  bottom: 20px;
  border: solid 1px #000;
  overflow: auto;
  background: #fff;
  z-index: 4;
}
.nuevo:after,
.editable:after {
  position: fixed;
  display: block;
  content: " ";
  top: 0px;
  left: 0px;
  right: 0px;
  bottom: 0px;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1;
}
</style>