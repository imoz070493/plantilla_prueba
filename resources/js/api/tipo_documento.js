/**
 * Llamadas api a las funciones relacionadas con tipo_documnto
 */
/**
 * devuelve un array de los tipos de documento
 */
import { getUrl, postUrl, deleteUrl } from "@/api/common.js";


export function listarTipoDocumento() {
	/**
	 * Todo: Guardar resultados en memoria hasta que se actualice la pagina o pase un tiempo limite
	 */

	return getUrl("/tipo_documento/lista_tipo_documento_to_persona").then(
		response => {
			return response.data;
		}
	);
}
