<template>
  <div>
    <!-- definir aqui el encabezado del componente -->
    <h4>Lista de Personas</h4>
    <!-- escuchamos los eventos del toolbar y redirigimos el mensaje al componente correspondiente, usamos los refs para identificarlos y al ser metodos muy pequeños los escribimos inline en lugar de crear un metodo nuevo -->
    <v-toolbar-persona
      @crear="$refs.cmp_listado_persona.crear()"
      @editar="$refs.cmp_listado_persona.editar()"
      @borrar="$refs.cmp_listado_persona.borrar()"
      @exportar="$refs.cmp_listado_persona.exportar()"
      @importar="$refs.cmp_listado_persona.importar()"
      @actualizar="$refs.cmp_listado_persona.actualizar()"
    ></v-toolbar-persona>

    <v-listar-persona ref="cmp_listado_persona" :ver_botones="false"></v-listar-persona>
  </div>
</template>
<script>
/**
 * Unificacion de los modelos de persona en una sola vista
 */

//cargamos el componente solo cuando se cree el componente
import ToolbarPersona from "@/components/rrhh/persona/ToolbarPersona";

export default {
  components: {
    "v-toolbar-persona": ToolbarPersona
  },
  data() {
    return {};
  }
};
</script>