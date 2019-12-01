<template>
  <div>
    <span>Importar Excel</span>:
    {{editable}}

    <b-container>

      <b-row>
        <b-col cols="4">
          <h5>IMPORTAR PERSONAS</h5>

          <b-row>
            <b-col cols="7">
              <button type="button" title="Exportar"><a v-bind:href="'/template/persona.xlsx'">Descargar Plantilla</a></button>
              <span v-if="errors.identificador">{{errors.identificador}}</span>
            </b-col>
          </b-row>

          <b-row>
            <b-col cols="5">
              <label>Importar:</label>
            </b-col>
            <b-col cols="7">
              <input type="file" @change="excelChanged">
              <span v-if="errors.descripcion">{{errors.descripcion}}</span>
            </b-col>
          </b-row>          

        </b-col>

      </b-row>
      <button type="button" @click="guardar">Guardar</button>
      <button type="button" @click="cancelar">Cancelar</button>

    </b-container>

  </div>
</template>
<script>
import {getUrl, postUrl, deleteUrl} from "@/api.js"

export default {
  props: {
    value: Object
  },
  created: function(){

    if(this.editable.id){
      this.editable.identificador = this.editable.id
    }
  },
  data() {
    return {
      editable: Object.assign({}, this.value),
      errors: [],

      //VARIABLES DINAMICAS
      sector_privado: [
        { id: "A", name: "A" },
        { id: "N.A.", name: "N.A." }
      ],
      sector_publico: [
        { id: "A", name: "A" },
        { id: "N.A.", name: "N.A." }
      ],
      otras_entidades: [
        { id: "A", name: "A" },
        { id: "N.A.", name: "N.A." }
      ],
    };
  },
  methods: {
    guardar() {
      let self = this;
      /* enviamos el objeto al nivel superior */

      postUrl("/reporte_excel/importar",this.editable)
        .then(function(response) {
          self.editable.estado = "guardado";
          console.log(response.data.name_file)
          self.editable.id = response.data.id;
          delete self.editable.estado;
          self.$emit("input", self.editable);
        })
        .catch(function(error) {
          if(error.response.status == 500){
            self.errors = error.response.data;
            alert(self.errors.data);
          }
          if(error.response.status == 422){
            self.errors = error.response.data.errors;
            console.log(self.errors);
          }
        });
    },
    cancelar() {
      /* enviamos el objeto al nivel superior */
      /*this.editable.estado = "cancelado";
      this.editable.id = 1;
      console.log(this.editable)
      delete this.editable.estado;
      this.$emit("input", this.editable);*/

      this.editable.id = 1;
      this.editable.estado = "cancelado";
      delete this.editable.estado;
      this.$emit("input", this.editable);
    },

    excelChanged(e){
      console.log(e.target.files[0])

      var fileReader = new FileReader()

      fileReader.readAsDataURL(e.target.files[0])
      fileReader.onload = (e) => {
        this.editable.excel_document = e.target.result
      }
    },
    

    //METOOS DINAMICOS
  },
  watch: {
    value(newvalue, oldvalue) {
      /* creamos una copia para trabajar localmente */
      this.editable = Object.assign({}, newvalue);
    },
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