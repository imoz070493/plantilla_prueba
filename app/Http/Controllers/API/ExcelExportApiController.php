<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\Export\PersonaExport;
use App\Helpers\Import\PersonaImport;

class ExcelExportApiController extends Controller
{
    public function exportExcelPersona(Request $request)
    {
        $reporte_persona = PersonaExport::exportExcelPersona();

        /* enviar el resultado */
        $result = [
            'msg' => 'creado correctamente',
            // 'rpt_name' => 'document/products.xlsx',
            'name_file' => $reporte_persona,
        ];

        return response()->json($result);

    }

    public function importExcel(Request $request)
    {
        $data = $request->all();

        // LOG::info($data);

        $name_file = PersonaImport::guardarExcel($request->excel_document);

        $response = PersonaImport::guardarDatos($name_file);

        if($response==2){
            /* enviar el resultado */
            return response()->json([
                'msg' => 'creado correctamente',
                'name_file' => $name_file,
                'id' => $response,
            ]);
        }else{
            return response()->json([
                'msg' => 'error al importar',
                'data' => $response,
            ], 500);
        }       

    }
}
