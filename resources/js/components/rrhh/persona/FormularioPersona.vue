<template>
  <div v-bind:class="{'loading':loading}">
    <!-- mostramos solo los componentes necesarios para que luego sean incluidos en un  dialogo, dentro de un formulario mas grande o en su propia ventana -->
    <!-- 
      Quitar/ocultar las variables de depuracion antes de subir el codigo
       {{editable.hola}}
    {{editable}}
    -->
    <loading-bar />

    <h5>Datos Personales</h5>

    <!-- definimos una sola fila por campo -->
    <b-row>
      <!-- creamos columnas para poder dividir contenido de etiqueta -->
      <b-col sm="5">
        <label>Nombre:*</label>
      </b-col>
      <b-col sm="7">
        <b-form-input v-model="editable.nombres"></b-form-input>
      </b-col>
      <b-alert v-if="errors.nombres" show variant="warning">{{errors.nombres}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Apellido Paterno:*</label>
      </b-col>
      <b-col sm="7">
        <b-form-input v-model="editable.apellido_paterno"></b-form-input>
      </b-col>
      <b-alert v-if="errors.apellido_paterno" show variant="warning">{{errors.apellido_paterno}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Apellido Materno:*</label>
      </b-col>
      <b-col sm="7">
        <b-form-input v-model="editable.apellido_materno"></b-form-input>
      </b-col>
      <b-alert v-if="errors.apellido_materno" show variant="warning">{{errors.apellido_materno}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Doc Identidad:*</label>
      </b-col>
      <b-col sm="7">
        <b-row>
          <b-col sm="4">
            <b-select v-model="editable.tipo_documento">
              <option
                v-for="item in array_tipos_documento"
                v-bind:value="item.id"
              >{{ item.descripcion_abv }}</option>
            </b-select>
            <b-alert v-if="errors.tipo_documento" show variant="warning">{{errors.tipo_documento}}</b-alert>
          </b-col>
          <b-col sm="8">
            <b-form-input v-model="editable.num_documento"></b-form-input>
            <b-alert v-if="errors.num_documento" show variant="warning">{{errors.num_documento}}</b-alert>
          </b-col>
        </b-row>
      </b-col>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Sexo:*</label>
      </b-col>
      <b-col sm="7">
        <b-form-group>
          <b-form-radio-group
            v-model="editable.sexo"
            :options="[  { value: 'M', text: 'Masculino' },
        { value: 'F', text: 'Femenino' }]"
            name="radio-inline"
          ></b-form-radio-group>
        </b-form-group>
      </b-col>
      <b-alert v-if="errors.sexo" show variant="warning">{{errors.sexo}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Fecha Nacimiento:*</label>
      </b-col>
      <b-col sm="7">
        <!--<input type="date" v-model="editable.fecha_nacimiento">-->
        <date-picker v-model="editable.fecha_nacimiento" lang="es" type="date" format="DD-MM-YYYY"></date-picker>
      </b-col>
      <b-alert v-if="errors.fecha_nacimiento" show variant="warning">{{errors.fecha_nacimiento}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Direccion:</label>
      </b-col>
      <b-col sm="7">
        <b-form-input v-model="editable.direccion"></b-form-input>
      </b-col>
      <b-alert v-if="errors.direccion" show variant="warning">{{errors.direccion}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Telefono:</label>
      </b-col>
      <b-col sm="7">
        <b-form-input v-model="editable.telefono"></b-form-input>
      </b-col>
      <b-alert v-if="errors.telefono" show variant="warning">{{errors.telefono}}</b-alert>
    </b-row>

    <b-row>
      <b-col sm="5">
        <label>Correo Electrónico:*</label>
      </b-col>
      <b-col sm="7">
        <b-form-input v-model="editable.correo_electronico"></b-form-input>
      </b-col>
      <b-alert v-if="errors.correo_electronico" show variant="warning">{{errors.correo_electronico}}</b-alert>
    </b-row>
    <b-row>
      <b-col sm="5">
        <label>Activo:*</label>
      </b-col>
      <b-col sm="7">
        <b-form-checkbox v-model="editable.activo" value="1" unchecked-value="0">&nbsp;</b-form-checkbox>
      </b-col>
      <b-alert v-if="errors.activo" show variant="warning">{{errors.activo}}</b-alert>
    </b-row>
    <!--
        <b-button variant="success" @click="guardar">Guardar</b-button>
      <b-button variant="danger" @click="cancelar">Cancelar</b-button>
    -->
  </div>
</template>
<script>
/**
 * separar las llmaadas al api en su propia clase
 */

import { listarTipoDocumento } from "@/api/tipo_documento.js";
import { guardarPersona } from "@/api/persona.js";

export default {
  props: {
    value: {
      type: Object,
      default: {}
    }
  },
  created: function() {},
  mounted: function() {
    //al visualizar obtenemos los datos adicionales

    listarTipoDocumento().then(data => {
      this.array_tipos_documento = data;
    });
    /*
    valor predeterminado de tipo documento
     */
    if (!this.editable.id) {
      this.editable.tipo_documento = 1;
    }
  },
  data() {
    return {
      /**
       * tratamos de usar nombres genericos de variables para poder reusar este codigo como plantilla para otra fuente de datos
       */
      editable: Object.assign({}, this.value),
      errors: [],
      loading: false,

      //VARIABLES DINAMICAS
      array_tipos_documento: []
    };
  },
  methods: {
    guardar() {
      this.editable._loading = true;
      // notificamos que se hizo un cambio en los datos ( en caso sea necesario escuchar este cambio desde fuera del componente)
      this.$emit("input", this.editable);
      guardarPersona(this.editable)
        .then(respuesta => {
          console.log("sssss", respuesta);
          /**actualizar primero los datos, luego el estado */
          this.editable._estado = "guardado";
          this.editable._loading = false;
          // notificamos el cambio correcto de los datos
          this.$emit("input", this.editable);
          // notificamos especificamente el evento
          this.$emit("guardado", this.editable);
        })
        .catch(error => {
          if (error.errors) {
            this.errors = error.errors;
            this.editable._loading = false;
            this.$emit("input", this.editable);
            //notificamos del error (opcional)
            //this.$emit("error", this.editable);
          } else {
            console.log("error", error);
          }
        });
    },
    cancelar() {
      /* enviamos el objeto al nivel superior */
      this.editable._estado = "cancelado";
      this.editable._loading = false;
      /* en input emitir siempre el modelo */
      this.$emit("input", this.editable);
      // notificamos la accion
      this.$emit("cancelado", this.editable);
    }
  }
};
</script> 