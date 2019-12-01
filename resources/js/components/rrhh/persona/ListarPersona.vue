<template>
  <div>DESDE COMPONETE PERSONA

    

    <b-row>
      <b-col cols="6">
        <label>Buscar:</label>
        <input v-model="persona.nombre" placeholder="nombre, apellidos o documentos">
      </b-col>
    </b-row>
    <button type="button" title="Exportar"><a v-bind:href="'#'" @click="reporteExcel()">XLS</a></button>

    <table>
      <thead>
        <tr>
          <th>N°</th>
          <th
          v-for="head in columns"
            v-if="head.visible"
            :class="head.class"
            :width="head.width+'px'"
          >{{ head.text }}
            </th>
          <td>Debug</td>
          <td>Acciones</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,key) in array_personas">
          <th>{{key}}</th>
          <td v-for="head in columns"
            v-if="head.visible"
            :class="item.class"
          >{{format(head,item,key)}}</td>
          <td></td>
          <th>
            <button type="button" @click="eliminar(item,key)">eliminar</button>
            <button type="button" @click="modificar(item,key)">modificar</button>
          </th>
        </tr>
      </tbody>
    </table>

    <table>
      <tr>
        <td>
          <button type="button" @click="crear">Nuevo</button>
          <button type="button" @click="importar">Importar</button>
        </td>
        <td>&nbsp;</td>

        
      </tr>
    </table>

    <div class="pagination">
      <button class="btn btn-default" v-on:click="fetchPaginationPersonas(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">
        Previous
      </button>
      <span>Page {{pagination.current_page}} of {{pagination.last_page}}</span>
      <button class="btn btn-default" v-on:click="fetchPaginationPersonas(pagination.next_page_url)" :disabled="!pagination.next_page_url">
        Next
      </button>

    </div>

    <div class="editable" v-if="editable && editable.estado=='editando'">
      <v-editar-persona v-model="editable"></v-editar-persona>
    </div>
    <div class="nuevo" v-if="nuevo && nuevo.estado">
      <v-crear-persona v-model="nuevo"></v-crear-persona>
    </div>
    <div class="editable" v-if="editable && editable.estado=='importando'">
      <v-importar-excel-persona v-model="editable"></v-importar-excel-persona>
    </div>

  </div>
</template>
<script>
import {getUrl, postUrl, deleteUrl} from "@/api.js"


export default {
    props: {
    value: Object
  },
  created: function(){
    this.listado();
  },
  data() {
    return {
      editable: Object.assign({}, this.value),
      url: '/personas',
      url_buscar: '/personas/buscar/',
      pagination: [],
      paginando: false,
      persona: { nombre: ""},
      array_personas: [],

      //VARIABLES DINAMICAS

      nuevo: null,
      /* posicion inicial */
      startIndex: 0,
      /* items por pagina */
      num: 5,
      /* listado a mostrar (paginado) */
      columns: [
        {
          field: "nombres",
          class: "",
          width: "200",
          text: "Nombres",
          visible: true
        },        
        {
          class: "",
          width: "200",
          text: "Apellidos",
          fn: this.formatApellidos,
          visible: true
        },
        {
          field: "num_documento",
          class: "",
          width: "100",
          text: "DNI",
          visible: true
        },
        {
          field: "fecha_nacimiento",
          class: "",
          width: "100",
          text: "Fecha de Nacimiento",
          visible: true
        },
      ],
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
    listado(){
      let $this = this
      getUrl(this.url, this.paginando).then(response => {
        //console.log(response.data.data.data)
        this.array_personas = response.data.personas.data

        $this.makePagination(response.data.personas)
      });
    },
    buscar(name){
      let $this = this
      getUrl(this.url_buscar + name, this.paginando).then(response => {
        //console.log(response.data.personas)
        this.array_personas = response.data.personas.data

        $this.makePagination(response.data.personas)
      }).catch(function(error) {
        console.log(error);
      });
    },
    makePagination(data){
      let pagination = {
        current_page: data.current_page,
        last_page: data.last_page,
        next_page_url: data.next_page_url,
        prev_page_url: data.prev_page_url
      }

      this.pagination = pagination
    },
    fetchPaginationPersonas(url){
      this.paginando = true
      this.url = url
      this.listado()
    },
    crear() {
      this.nuevo = { estado: "creando" };
    },
    modificar(e, key) {
      this.editable = this.array_personas[key];
      e.estado = "editando";
    },
    eliminar(e, key) {
      let self = this;
      if (confirm("esta seguro de eliminar")) {
        deleteUrl("/persona/" + e.id)
          .then(function(response) {
            //self.personas.splice(key, 1);
            self.listado()
          })
          .catch(function(e) {
            alert("error al eliminar "+e);
          });
      } else {
      }
    },


    //METODOS DINAMICOS
    
    
    importar() {
      this.editable = { estado: "importando" };
    },
    formatApellidos(e) {
      return e.apellido_paterno + " " + e.apellido_materno;
    },
    excelChanged(e){
      console.log(e.target.files[0])

      var fileReader = new FileReader()

      fileReader.readAsDataURL(e.target.files[0])
      fileReader.onload = (e) => {
        this.editable.excel_document = e.target.result
        postUrl("/reporte_excel/import",
          this.editable
        )
        .then(function(response) {
          /*self.editable.estado = "guardado";
          console.log(response.data.data)
          self.editable = response.data.data;
          delete self.editable.estado;
          self.$emit("input", self.editable);*/
        })
        .catch(function(error) {
          if(error.response.status == 422){
            self.errors = error.response.data.errors;
            console.log(self.errors);
          }
        });
      }
    },
    reporteExcel(){
      getUrl("/export/persona",this.paginando).then(response => {
        //console.log(response.data.rpt_name)
        location.href=response.data.name_file
      });
    }
  },
  watch: {
    "persona.nombre": function(newvalue, oldvalue) {
      console.log(this.persona.nombre)
      this.buscar(this.persona.nombre)
    },
    value(newvalue, oldvalue) {
      /* creamos una copia para trabajar localmente */
      this.editable = Object.assign({}, newvalue);
    },
    nuevo: function(newvalue, oldvalue) {
      //console.log(newvalue+"  --   "+oldvalue)
      //console.log("Function nuevo "+newvalue.doc)
      if (newvalue && newvalue.id) {
        /*enviamos una copia*/
        //this.personas.push(Object.assign({}, newvalue));
        //console.log(newvalue.dni)
        this.listado();
      }
    },
    editable: function(newvalue, oldvalue) {
      //console.log(newvalue+"  --   "+oldvalue)
      //console.log("Function nuevo "+newvalue)
      if (newvalue && newvalue.id) {
        /*enviamos una copia*/
        //this.personas.push(Object.assign({}, newvalue));
        //console.log(newvalue.dni)
        this.listado();
      }
    }
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