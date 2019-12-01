<?php
namespace App\Helpers\Import;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;
use DateTime;

use App\Persona;

class PersonaImport{

	public static function guardarExcel($document_excel){
		$exploded = explode(',',$document_excel);
        $decoded = base64_decode($exploded[1]);

        if(str_contains($exploded[0],'vnd.openxmlformats-officedocument.spreadsheetml.sheet')){
            $extension = 'xlsx';
        }else{
            $extension = '';
        }

        $fileName = str_random().'.'.$extension;

        $path = public_path().'/document/imports/'.$fileName;

        file_put_contents($path, $decoded);

        //INSERTAR MEDIANTE MODELO
        // DB::table("document")->insert('path')

        return $fileName;
	}

	public static function guardarDatos($fileName){
		$path = public_path().'/document/imports/'.$fileName;

		$reader = new Xlsx();
		$spreadsheet = $reader->load($path);
		$objWorksheet = $spreadsheet->getActiveSheet();
	    $highestRow = $objWorksheet->getHighestRow();

	    $registros = true;
	    $filas = 1;
	    $errors_total = [];
	    $errors = [];
	    while($registros){
	    	LOG::info($filas);
	    	$filas++;
	    	$val = $objWorksheet->getCell('A'.$filas)->getFormattedValue();

	    	$errors = self::validarUidAndDocAndRuc($objWorksheet->getCell('F'.$filas)->getFormattedValue());
	    	LOG::info($val);
	    	if($val==""){
	    		$registros=false;
	    	}

	    	$errors_total = array_merge($errors_total,$errors);
	    	unset($errors);
	    }

	    LOG::info("Filas: ".$filas);

	    LOG::info($errors_total);

	    if(count($errors_total)>0){
	    	return $errors_total;
	    }

		for ($row = 2; $row <= ($filas-1); $row++) {

			// $fecha_nacimiento = new DateTime($objWorksheet->getCell('G'.$row)->getFormattedValue());
			$arr_persona = [
	            'nombres' => $objWorksheet->getCellByColumnAndRow(2, $row),
	            'apellido_paterno' => $objWorksheet->getCellByColumnAndRow(3, $row),
	            'apellido_materno' => $objWorksheet->getCellByColumnAndRow(4, $row),
	            'tipo_documento' => self::extraerCodigo($objWorksheet->getCellByColumnAndRow(5, $row),0),
	            'num_documento' => $objWorksheet->getCellByColumnAndRow(6, $row),
	            'fecha_nacimiento' => self::formatearFecha($objWorksheet->getCell('G'.$row)->getFormattedValue()),
	            'sexo' => self::extraerCodigo($objWorksheet->getCellByColumnAndRow(8, $row),0),
	            'direccion' => $objWorksheet->getCellByColumnAndRow(9, $row),
	            'telefono' => $objWorksheet->getCellByColumnAndRow(10, $row),
	        ];

	        // LOG::info(var_dump($arr_persona));

	        $persona = Persona::guardarDatos($arr_persona);
			unset($arr_persona);
			LOG::info("Persona: ".$persona->id);
	    }
	    return 2;
	}

	public static function extraerCodigo($dato,$index){
		$exploded = explode('.',$dato);
		return $exploded[$index];
	}

	public static function formatearFecha($fecha){
		$exploded = explode('/',$fecha);
		return $exploded[2]."-".$exploded[0]."-".$exploded[1];
	}

	public static function validarUidAndDocAndRuc($doc){
		$errors = [];
		$validate_doc = Persona::where('num_documento',$doc)->get();
		if(count($validate_doc)>0){
			array_push($errors, "El doc ".$doc." ya existe.");
		}
		return $errors;
	}
}