<template>
  <div>
    <b-row>
      <label>
        Seleccionar Archivo a Importar:
        <input type="file" @change="excelChanged" />
      </label>
      <span v-if="errors.descripcion">{{errors.descripcion}}</span>
    </b-row>
    <span v-if="errors.identificador">{{errors.identificador}}</span>

    <br />Para importar adecuadamente los datos necesita seguir el formato de la plantilla de importación, la cual puede descargar del siguiente enlace
    <a
      v-bind:href="'/template/persona.xlsx'"
    >Descargar Plantilla</a>
    <!--
      <button type="button" @click="importar">Importar</button>
      <button type="button" @click="cancelar">Cancelar</button>
    -->
  </div>
</template>
<script>
import { getUrl, postUrl, deleteUrl } from "@/api/common.js";
import { importaPersonas } from "@/api/persona.js";
export default {
  props: {
    value: Object
  },
  created: function() {
    if (this.editable.id) {
      this.editable.identificador = this.editable.id;
    }
  },
  data() {
    return {
      editable: Object.assign({}, this.value),
      errors: [],

      //VARIABLES DINAMICAS
      sector_privado: [{ id: "A", name: "A" }, { id: "N.A.", name: "N.A." }],
      sector_publico: [{ id: "A", name: "A" }, { id: "N.A.", name: "N.A." }],
      otras_entidades: [{ id: "A", name: "A" }, { id: "N.A.", name: "N.A." }]
    };
  },
  methods: {
    /**
     * Importar archivo Excel
     * los metodos deben de tener nombes descripcion  para que sean llamados desde otros componentes
     */
    importar() {
      let self = this;
      /* enviamos el objeto al nivel superior */
      importaPersonas()
        //      postUrl("/persona/importar")
        .then(function(response) {
          self.editable._estado = "guardado";
          self.editable.id = response.data.id;
          self.$emit("input", self.editable);
          console.log(response.data.name_file);
        })
        .catch(function(error) {
          if (error.response.status == 500) {
            self.errors = error.response.data;
            alert(self.errors.data);
          }
          if (error.response.status == 422) {
            self.errors = error.response.data.errors;
            console.log(self.errors);
          }
        });
    },
    cancelar() {
      /* enviamos el objeto al nivel superior */
      /*this.editable._estado = "cancelado";
      this.editable.id = 1;
      console.log(this.editable)
      delete this.editable._estado;
      this.$emit("input", this.editable);*/

      this.editable.id = 1;
      this.editable._estado = "cancelado";
      this.$emit("input", this.editable);
    },

    excelChanged(e) {
      console.log(e.target.files[0]);

      var fileReader = new FileReader();

      fileReader.readAsDataURL(e.target.files[0]);
      fileReader.onload = e => {
        this.editable.excel_document = e.target.result;
      };
    }

    //METOOS DINAMICOS
  },
  watch: {
    value(newvalue, oldvalue) {
      /* creamos una copia para trabajar localmente */
      this.editable = Object.assign({}, newvalue);
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