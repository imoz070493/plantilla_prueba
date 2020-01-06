/**
 * Llamadas api a las funciones relacionadas con tipo_documnto
 */

import { getUrl, postUrl, deleteUrl, download } from "@/api/common.js";

export function guardarPersona(editable) {
  return postUrl("/persona", editable)
    .then(function (data) {
      return data;
    })
}

/**
 * ToDo: Convertir objeto "paginando" a objeto simple con el formato sgte {
 * current_page: int, // pagina a obtener
 * max: int //numero maximo de items por pagina
 * sort: {}  // objeto con los filtros
 * filter: {} // criterio de busqueda
 */
export function listarPersonas(paginando) {
  return getUrl("/personas", paginando).then(response => {
    console.log('listar Personas', response.message)
    return response.data;
  });
}
/**
 * Todo: unir el listar y buscar en un solo procedimiento, tanto a nivel de servidor como a nivel de interfaz
 */
export function buscarPersonas(paginando) {
  return getUrl("/personas/buscar", paginando).then(response => {
    console.log('listar Personas', response.message)
    return response.data;
  });
}
export function borrarPersonas(id) {
  return deleteUrl('/personas/' + id)
}
export function importaPersonas() {

  return postUrl("/persona/importar")
}

export function reportePersonasExcel(parametros) {
  /**
   * Todo: corregir ruta de exportacion Primero el nombre y leugo los metodos PERSONA/ACCION
   */
  /**
   * Todo: Cuando se exporta se exporta todo el listado, no solo la pagina visible
   */
  /**
   * Respetar los parametros, ordenamiento y filtros
   */
  return getUrl("/persona/reporte", parametros).then(response => {
    download(response.data)
  });
}
/**
 * Guardamos los datos de las columnas en un solo lugar
 */
export function getPersonaColumns() {
  return [
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
      fn: function (e) { return e.apellido_paterno + " " + e.apellido_materno; }, //la funcion de formateado si es sencilla podemos ponerla aqui, si se necesita usar alguna variable de la vista, tendremos que pasar como parametro a la funcion "getPersonacolumns" el contexto de la vista (variable this)
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
    }
  ]
}