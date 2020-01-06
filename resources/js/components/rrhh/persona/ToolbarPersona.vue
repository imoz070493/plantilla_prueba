<template>
  <div>
    <b-button-toolbar aria-label="Toolbar with button groups and input groups">
      <b-button-group size="sm" class="mr-1">
        <!-- notificamos del evento sin pasar ningun objeto, ya que solo necesitamos la notificacion -->
        <b-button variant="basic" @click="$emit('crear')">Nuevo</b-button>
        <b-button variant="info" @click="$emit('editar')">Editar</b-button>
        <b-button variant="danger" @click="$emit('borrar')">Borrar</b-button>
        <b-button variant="success" @click="$emit('ver')">Ver</b-button>
      </b-button-group>
      <b-button-group size="sm" class="mr-1">
        <b-button variant="primary">Promover</b-button>
        <!-- al hacer click notificamos del evento y enviamos los filtros seleccionados actualmente para que sean pasados al componente que los visualizara -->
        <b-button variant="info" @click="$emit('actualizar',filtros)">Actualizar</b-button>
        <b-button :pressed.sync="busqueda" variant="morado">Busqueda</b-button>
      </b-button-group>
      <b-button-group size="sm" class="mr-1">
        <b-button variant="warning" @click="$emit('importar')">Importar EXCEL</b-button>
        <b-button variant="success" @click="$emit('exportar')">Exportar EXCEL</b-button>
      </b-button-group>
      <!--
    <b-input-group size="sm" prepend="$" append=".00">
      <b-form-input value="100" class="text-right"></b-form-input>
    </b-input-group>
      -->
    </b-button-toolbar>

    <b-collapse bg-variant="light" v-model="busqueda" class="row form-row">
      <b-form-group
        label-cols-lg="3"
        label="Busqueda avanzada"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="col-lg-4 col-md-6 col-12"
      >
        <b-input v-model="txt"></b-input>
      </b-form-group>
      <b-form-group
        label-cols-lg="3"
        label="Busqueda avanzada"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="col-lg-4 col-md-6 col-12"
      >b</b-form-group>
      <b-form-group
        label-cols-lg="3"
        label="Busqueda avanzada"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="col-lg-4 col-md-6 col-12"
      >c</b-form-group>

      <b-form-group
        label-cols-lg="3"
        label="Busqueda avanzada"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="col-lg-4 col-md-6 col-12"
      >
        <b-form-group
          label-cols-sm="3"
          label="Street:"
          label-align-sm="right"
          label-for="nested-street"
        >
          <b-form-input id="nested-street"></b-form-input>
        </b-form-group>

        <b-form-group
          label-cols-sm="3"
          label="City:"
          label-align-sm="right"
          label-for="nested-city"
        >
          <b-form-input id="nested-city"></b-form-input>
        </b-form-group>

        <b-form-group
          label-cols-sm="3"
          label="State:"
          label-align-sm="right"
          label-for="nested-state"
        >
          <b-form-input id="nested-state"></b-form-input>
        </b-form-group>

        <b-form-group
          label-cols-sm="3"
          label="Country:"
          label-align-sm="right"
          label-for="nested-country"
        >
          <b-form-input id="nested-country"></b-form-input>
        </b-form-group>

        <b-form-group label-cols-sm="3" label="Ship via:" label-align-sm="right" class="mb-0">
          <b-form-radio-group class="pt-2" :options="['Air', 'Courier', 'Mail']"></b-form-radio-group>
        </b-form-group>
      </b-form-group>
    </b-collapse>
  </div>
</template>
<script>
/**
 * 1) Importamos saveState para guardar los campos de la table entre recargas de pagina
 */
import saveState from "vue-save-state";
export default {
  props: {
    value: {
      type: Object,
      default: function() {
        return {};
      }
    }
  },
  //2) incluimos las funcion de saveState
  mixins: [saveState],
  data() {
    return {
      filtros: Object.assign({}, this.value),
      busqueda: false,
      txt: ""
    };
  },
  methods: {
    /**
     * 3) Aca definimos que cosa guardar
     */
    getSaveStateConfig() {
      return {
        cacheKey: (":" + this.$route.fullPath).hashCode
      };
    }
  },
  created() {
    window.self = this;
  }
};
</script>