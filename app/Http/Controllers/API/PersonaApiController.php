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
    	
        $personas = Persona::listarPersonas();

        // LOG::info($data);

        $result = [
            'msg' => 'listado correctamente',
            'personas' => $personas,
        ];
        return response()->json($result);
    }

    public function create(PersonaFormRequest $request)
    {
        $data = $request->all();

        // LOG::info($data);

        $persona = Persona::guardarDatos($request->all());

        $result = [
            'msg' => 'creado correctamente',
            'persona' => $persona,
        ];
        return response()->json($result);
    }

    public function update(PersonaFormRequest $request)
    {
        $data = $request->all();

        // LOG::info($data);

        $persona = Persona::guardarDatos($data);
        
        $result = [
            'msg' => 'Actualizado correctamente',
            'persona' => $persona,
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
                'msg' => 'error al eliminar',
                'data' => [
                    'tipo_doc' => $tipo_doc,
                    'doc' => $doc,
                ],
            ], 500);
            /* los errores deben de tener codigos de estado */
        }

        $tipo_doc = 0;
        $doc = 0;

        $result = [
            'msg' => 'Eliminado correctamente',
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
            'msg' => 'listado correctamente',
            'tipos_documento' => $tipos_documento,
        ];
        return response()->json($result);
    }

    public function buscarPersona($name='', Request $request)
    {
        /* obtenemos las varibles de busqueda */

        $personas = Persona::buscarPersonas($name);

        /* enviar el resultado */
        $result = [
            'msg' => 'listado correctamente',
            'personas' => $personas,
        ];

        return response()->json($result);

    }
}
