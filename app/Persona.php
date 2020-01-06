<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Carbon\Carbon;
class Persona extends Model
{
	protected $table = 'personas';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
    	'nombres',
    	'apellido_paterno',
    	'apellido_materno',
        'tipo_documento',
        'num_documento',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'telefono',
        'correo_electronico',
        'activo',
    ];
    protected $cast=[
        'fecha_nacimiento' => 'date',
        'activo'=>'boolean'
    ];

    protected $guarded = [

    ];

/**
 * Columnas que sera devueltas por las consultas
 *
 * @var array
 */
     public static  $campos_a_devolver=[
'id','nombres','apellido_paterno','apellido_materno','tipo_documento','num_documento','fecha_nacimiento','sexo','direccion','telefono','correo_electronico','activo'
    ];

    public static function guardarDatos($datos){
        
        if(isset($datos['id'])){
            $persona = Persona::findOrFail($datos['id']);
        }else{
            $persona = new Persona();
        }
/**
 * Usar array_key_exists e if en lugar de isset para: 
 * 1) no actualizar datos cuando estos no se envian p.e. 
 *  request=['clave'=>123] -> 
 *      array_key_exists('clave_no_existente',$datos) devuelve false y mantiene el valor previo
 *      array_key_exists('clave',$datos) devuelve true y actualiza solo ese atributo
 * 2) actualizar datos null p.e 
 *  request=['clave'=>null] -> array_key_exists('clave',$datos) devuelve true y actualiza el campo a null
 *  request=['clave'=>null] -> isset($datos['clave']) devuelve false y no actualiza el campo
 *   
 */
        if(array_key_exists('nombres',$datos)) $persona->nombres = $datos['nombres'];
        if(array_key_exists('apellido_paterno',$datos)) $persona->apellido_paterno = $datos['apellido_paterno'];
        if(array_key_exists('apellido_materno',$datos)) $persona->apellido_materno = $datos['apellido_materno'];
        if(array_key_exists('tipo_documento',$datos)) $persona->tipo_documento = $datos['tipo_documento'];
        if(array_key_exists('num_documento',$datos)) $persona->num_documento = $datos['num_documento'];
        if(array_key_exists('fecha_nacimiento',$datos)) $persona->fecha_nacimiento = Carbon::parse($datos['fecha_nacimiento']);
        if(array_key_exists('sexo',$datos)) $persona->sexo = $datos['sexo'];
        if(array_key_exists('direccion',$datos)) $persona->direccion = $datos['direccion'];
        if(array_key_exists('telefono',$datos)) $persona->telefono = $datos['telefono'];
        if(array_key_exists('correo_electronico',$datos)) $persona->correo_electronico = $datos['correo_electronico'];
        if(array_key_exists('activo',$datos)) $persona->activo = $datos['activo'];
        $persona->save();

        return $persona;
    }  

    public static function listarPersonas(){
        $personas = Persona
                ::select(self::$campos_a_devolver)
                ->orderBy('id','desc')
                ->paginate(5);
        return $personas;
    }

    public static function listarPersonasReporte(){
        /**
         * Usar el modelo en lugar de eloquent para aprovechar las funcionalidades del framework()
         */
        $personas= Persona::select(self::$campos_a_devolver)
        ->orderBy('id','desc')
        ->get();
        return $personas;
    }

    public static function buscarPersonas($name){
        $personas= Persona::select(self::$campos_a_devolver)
                ->orderBy('id','desc')
                ->where('nombres','LIKE','%'.$name.'%')
                ->orWhere('apellido_paterno','LIKE','%'.$name.'%')
                ->orWhere('apellido_materno','LIKE','%'.$name.'%')
                ->orWhere('num_documento','LIKE','%'.$name.'%')
                // ->get();
                ->paginate(5);

        return $personas;
    } 


    public static function eliminarDatos($id){
        /**
         * siempre devolver un resultado
         */
        return Persona::where('id',$id)->delete();
    }
}