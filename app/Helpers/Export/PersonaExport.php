<?php
namespace App\Helpers\Export;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;
use DateTime;

use App\Persona;

class PersonaExport{

	/*
    * Export los datos de persona en excel
    */
	public static function exportExcelPersona(){

		$documento = new Spreadsheet();
		$documento
		    ->getProperties()
		    ->setCreator("Aquí va el creador, como cadena")
		    ->setLastModifiedBy('Parzibyte') // última vez modificado por
		    ->setTitle('Mi primer documento creado con PhpSpreadSheet')
		    ->setSubject('El asunto')
		    ->setDescription('Este documento fue generado para parzibyte.me')
		    ->setKeywords('etiquetas o palabras clave separadas por espacios')
		    ->setCategory('La categoría');
		 
		$styleArray = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '000000'),
	        'size'  => 11,
	        'name'  => 'Arial'
	    ));

		$hoja = $documento->getActiveSheet();
		$hoja->getColumnDimension('A')->setAutoSize(true);
		$hoja->getColumnDimension('B')->setAutoSize(true);
		$hoja->getColumnDimension('C')->setAutoSize(true);
		$hoja->getColumnDimension('D')->setAutoSize(true);
		$hoja->getColumnDimension('E')->setAutoSize(true);
		$hoja->getColumnDimension('F')->setAutoSize(true);
		$hoja->getColumnDimension('G')->setAutoSize(true);
		$hoja->getColumnDimension('H')->setAutoSize(true);
		$hoja->getColumnDimension('I')->setAutoSize(true);
		$hoja->getColumnDimension('J')->setAutoSize(true);
		$hoja->getColumnDimension('K')->setAutoSize(true);
		$hoja->getColumnDimension('L')->setAutoSize(true);
		$hoja->getColumnDimension('M')->setAutoSize(true);
		$hoja->getColumnDimension('N')->setAutoSize(true);
		$hoja->getColumnDimension('O')->setAutoSize(true);
		$hoja->getColumnDimension('P')->setAutoSize(true);
		$hoja->getColumnDimension('Q')->setAutoSize(true);
		$hoja->getColumnDimension('R')->setAutoSize(true);
		$hoja->getColumnDimension('S')->setAutoSize(true);
		$hoja->getColumnDimension('T')->setAutoSize(true);
		$hoja->getColumnDimension('U')->setAutoSize(true);
		$hoja->getColumnDimension('V')->setAutoSize(true);
		$hoja->getColumnDimension('W')->setAutoSize(true);

		$hoja->getStyle("A1:W1")->applyFromArray($styleArray);

		$hoja->setTitle("Personas");
		// $hoja->setCellValueByColumnAndRow(1, 1, "Un valor en 1, 1");
		$hoja->setCellValue("A1", "N°");
		$hoja->setCellValue("B1", "NOMBRES");
		$hoja->setCellValue("C1", "APELLIDO PATERNO");
		$hoja->setCellValue("D1", "APELLIDO MATERNO");
		$hoja->setCellValue("E1", "TIPO DOCUMENTO");
		$hoja->setCellValue("F1", "N° DOCUMENTO");
		$hoja->setCellValue("G1", "FECHA NACIMIENTO");
		$hoja->setCellValue("H1", "SEXO");
		$hoja->setCellValue("I1", "DIRECCION");
		$hoja->setCellValue("J1", "TELEFONO");
		$hoja->setCellValue("K1", "CORREO ELECTRONICO");

		$personas = Persona::listarPersonasReporte();

		foreach ($personas as $key => $persona) {
			$hoy = new DateTime("now");
			$fecha_nacimiento = new DateTime($persona->fecha_nacimiento);
			$edad = $hoy->diff($fecha_nacimiento);

			$hoja->setCellValue("A".($key+2), ($key+1));
			$hoja->setCellValue("B".($key+2), $persona->nombres);
			$hoja->setCellValue("C".($key+2), $persona->apellido_paterno);
			$hoja->setCellValue("D".($key+2), $persona->apellido_materno);
			$hoja->setCellValue("E".($key+2), $persona->tipo_documento);
			$hoja->setCellValue("F".($key+2), $persona->num_documento);
			$hoja->setCellValue("G".($key+2), $persona->fecha_nacimiento);
			$hoja->setCellValue("H".($key+2), $persona->sexo);
			$hoja->setCellValue("I".($key+2), $persona->direccion);
			$hoja->setCellValue("J".($key+2), $persona->telefono);
			$hoja->setCellValue("K".($key+2), $persona->correo_electronico);
		}
		 
		$writer = new XlsxWriter($documento);

		$document_name = "/document/exports/"."persona-".date("Y-m-d-H-i-s").".xlsx";  
		 
		# Le pasamos la ruta de guardado
		$writer->save(public_path().$document_name);

        return $document_name;
	}
}