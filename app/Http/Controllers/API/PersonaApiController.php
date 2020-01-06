<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\TipoDocumento;
use App\Persona;

use App\Http\Requests\PersonaFormRequest;

class PersonaApiController extends Controller
{
	public function index(Request $request)
    {
        
        /**
         * ToDo: Implementar ordenamiento
         * Todo: Implementar paginacion usando como parametro el numero de items por página 
         * Todo: Unir Lista y busqueda en una sola funcion 
         * Todo: optimizar la consulta en caso sea una consula sin filtros
         * Todo: validar los parametros de busqueda 
         */

        $personas = Persona::listarPersonas();

        // LOG::info($data);

        $result = [
            //usamos message en lugar de msg para que se use los mismos campos de laravel
            'message' => 'listado correctamente',
            //devolvemos TODOS los resultado como data, para poder reutilizar componentes en la interfaz
            'data' => $personas
                ];
        return response()->json($result);
    }

    public function create(PersonaFormRequest $request)
    {
        $data = $request->all();

        // LOG::info($data);

        $persona = Persona::guardarDatos($request->all());

        $result = [
            'message' => 'creado correctamente',
            'data' => $persona,
        ];
        return response()->json($result);
    }

    public function update(PersonaFormRequest $request)
    {
        $data = $request->all();

        // LOG::info($data);

        $persona = Persona::guardarDatos($data);
        
        $result = [
            'message' => 'Actualizado correctamente',
            'data' => $persona,
        ];
        return response()->json($result);

    }

    public function delete($id)
    {
        /* en caso de error enviar con codigo de estado
        p.s. en este ejemplo devuelve error cuando es divisible por 5
         */
        $persona = Persona::eliminarDatos($id);

        $doc = 2;

        if ($doc % 5 == 0) {

            return response()->json([
                'message' => 'error al eliminar',
                'data' => [
                    'tipo_doc' => $doc,
                    'doc' => $doc,
                ],
            ], 500);
            /* los errores deben de tener codigos de estado */
        }

        $tipo_doc = 0;
        $doc = 0;

        $result = [
            'message' => 'Eliminado correctamente',
            'data' => [
                'tipo_doc' => $tipo_doc,
                'doc' => $doc,
            ],
        ];
        return response()->json($result);
    }

    public function listaTipoDocumento(Request $request)
    {
        $tipos_documento = TipoDocumento::listaTipoDocumento();

        $result = [
            'message' => 'listado correctamente',
            'data' => $tipos_documento,
        ];
        return response()->json($result);
    }

    public function buscarPersona(Request $request)
    {
        /* obtenemos las varibles de busqueda */
        /**
         * Todo:validar la busqueda y leer tambien datos de ordenamiensot
         */
        $name='';
/**
 * Todo: completar y unir con listado
 */
        $personas = Persona::buscarPersonas($name);

        /* enviar el resultado */
        $result = [
            'message' => 'listado correctamente',
            'data' => $personas,
        ];

        return response()->json($result);

    }
}