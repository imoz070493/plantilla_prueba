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
            'message' => 'creado correctamente',
            // 'rpt_name' => 'document/products.xlsx',
            'data' => $reporte_persona,
        ];

        return response()->json($result);

    }

    public function importExcel(Request $request)
    {
        $data = $request->all();

        // LOG::info($data);

        $data = PersonaImport::guardarExcel($request->excel_document);

        $response = PersonaImport::guardarDatos($data);

        if($response==2){
            /* enviar el resultado */
            return response()->json([
                'message' => 'creado correctamente',
                'data' => $data,
                'id' => $response,
            ]);
        }else{
            return response()->json([
                'message' => 'error al importar',
                'data' => $response,
            ], 500);
        }       

    }
}