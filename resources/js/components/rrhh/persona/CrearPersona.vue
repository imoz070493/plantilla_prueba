<template>
  <div>
    <span v-if="editable.id">Editar persona a</span><span v-else>Agregar persona a</span>:    {{editable.nombre}} {{editable.apellido_paterno}} {{editable.apellido_materno}} {{editable.hola}}
    {{editable}}

    <b-container fluid>
      

      <b-row class="my-1">
        <b-col cols="8">
          <h5>Datos Personales</h5>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Nombre:*</label>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="editable.nombres"></b-form-input>
            </b-col>
            <b-alert v-if="errors.nombres" show variant="warning">{{errors.nombres}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Apellido Paterno:*</label>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="editable.apellido_paterno"></b-form-input>
            </b-col>
            <b-alert v-if="errors.apellido_paterno" show variant="warning">{{errors.apellido_paterno}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Apellido Materno:*</label>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="editable.apellido_materno"></b-form-input>
            </b-col>
            <b-alert v-if="errors.apellido_materno" show variant="warning">{{errors.apellido_materno}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="3">
              <label>Doc Identidad:*</label>
            </b-col>
            <b-col cols="4">              
              <b-select v-model="editable.tipo_documento">
                <option v-for="item in array_tipos_documento" v-bind:value="item.id">{{ item.descripcion_abv }}</option>
              </b-select>
              <b-alert v-if="errors.tipo_documento" show variant="warning">{{errors.tipo_documento}}</b-alert>
            </b-col>
            <b-col cols="5">
              <b-form-input v-model="editable.num_documento"></b-form-input>
              <b-alert v-if="errors.num_documento" show variant="warning">{{errors.num_documento}}</b-alert>
            </b-col>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Sexo:*</label>
            </b-col>
            <b-col cols="7">
              <b-form-group>
                <b-form-radio-group
                  v-model="editable.sexo"
                  :options="array_sexo"
                  name="radio-inline"
                ></b-form-radio-group>
              </b-form-group>
            </b-col>
            <b-alert v-if="errors.sexo" show variant="warning">{{errors.sexo}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Fecha Nacimiento:*</label>
            </b-col>
            <b-col cols="7">       
              <!--<input type="date" v-model="editable.fecha_nacimiento">-->
              <date-picker v-model="editable.fecha_nacimiento" lang="es" type="date" format="DD-MM-YYYY"></date-picker>
            </b-col>
            <b-alert v-if="errors.fecha_nacimiento" show variant="warning">{{errors.fecha_nacimiento}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Direccion:</label>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="editable.direccion"></b-form-input>
            </b-col>
            <b-alert v-if="errors.direccion" show variant="warning">{{errors.direccion}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Telefono:</label>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="editable.telefono"></b-form-input>
            </b-col>
            <b-alert v-if="errors.telefono" show variant="warning">{{errors.telefono}}</b-alert>
          </b-row>

          <b-row class="my-1">
            <b-col cols="5">
              <label>Correo Electrónico:*</label>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="editable.correo_electronico"></b-form-input>
            </b-col>
            <b-alert v-if="errors.correo_electronico" show variant="warning">{{errors.correo_electronico}}</b-alert>
          </b-row>
        </b-col>

      </b-row>
      <b-button variant="success" @click="guardar">Guardar</b-button>
      <b-button variant="danger" @click="cancelar">Cancelar</b-button>

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
     
  },
  mounted:function(){
    this.listarTipoDocumento()
    
    
    if(this.editable.id){
    }else{
      this.editable.tipo_documento = 1
    }
  },
  data() {
    return {
      editable: Object.assign({}, this.value),
      errors: [],

      //VARIABLES DINAMICAS
      array_sexo: [
        { value: "M", text: "Masculino" },
        { value: "F", text: "Femenino" }
      ],
      array_tipos_documento: [],
    };
  },
  methods: {
    guardar() {
      let self = this;

      /* enviamos el objeto al nivel superior */
      postUrl("/persona",this.editable)
        .then(function(response) {
          self.editable.estado = "guardado";
          self.editable = response.data.persona;
          delete self.editable.estado;
          self.$emit("input", self.editable);
        })
        .catch(function(error) {
          if(error.response.status == 422){
            self.errors = error.response.data.errors;
            console.log(self.errors);
          }
        });
    },    
    cancelar() {
      /* enviamos el objeto al nivel superior */
      this.editable.estado = "cancelado";
      delete this.editable.estado;
      this.$emit("input", this.editable.situacion_educativa_id);
    },

    //METOOS DINAMICOS

    listarTipoDocumento(){
      getUrl("/tipo_documento/lista_tipo_documento_to_persona").then(response => {
        this.array_tipos_documento = response.data.tipos_documento
      });
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