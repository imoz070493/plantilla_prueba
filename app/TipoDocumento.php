<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use stdClass;

class TipoDocumento extends Model
{
    public static function listaTipoDocumento(){
        $tipo_documento = new stdClass();
        $array_tipo_documento = [];

        $tipo_documento->id = "1";
        $tipo_documento->descripcion = "DOCUMENTO NACIONAL DE IDENTIDAD";
        $tipo_documento->descripcion_abv = "DNI";
        array_push($array_tipo_documento, $tipo_documento);

        $tipo_documento1 = new stdClass();
        $tipo_documento1->id = "4";
        $tipo_documento1->descripcion = "CARNÉ DE EXTRANJERÍA";
        $tipo_documento1->descripcion_abv = "CARNÉ EXT.";
        array_push($array_tipo_documento, $tipo_documento1);

        $tipo_documento2 = new stdClass();
        $tipo_documento2->id = "6";
        $tipo_documento2->descripcion = "REG. ÚNICO DE CONTRIBUYENTES (1)";
        $tipo_documento2->descripcion_abv = "RUC";
        array_push($array_tipo_documento, $tipo_documento2);

        $tipo_documento3 = new stdClass();
        $tipo_documento3->id = "7";
        $tipo_documento3->descripcion = "PASAPORTE";
        $tipo_documento3->descripcion_abv = "PASAPORTE";
        array_push($array_tipo_documento, $tipo_documento3);

        $tipo_documento4 = new stdClass();
        $tipo_documento4->id = "9";
        $tipo_documento4->descripcion = "CARNÉ DE SOLICIT DE REFUGIO";
        $tipo_documento4->descripcion_abv = "CARNÉ SOLIC REFUGIO";
        array_push($array_tipo_documento, $tipo_documento4);

        $tipo_documento5 = new stdClass();
        $tipo_documento5->id = "11";
        $tipo_documento5->descripcion = "PARTIDA DE NACIMIENTO (2)";
        $tipo_documento5->descripcion_abv = "PART. NAC.";
        array_push($array_tipo_documento, $tipo_documento5);

        return $array_tipo_documento;
    }
}
