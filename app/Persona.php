<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

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
    ];

    protected $guarded = [

    ];

	//PARAMETRO CONVERTIR A ARRAY
    public static function guardarDatos($request){
        
        if(isset($request['id'])){
            $persona = Persona::findOrFail($request['id']);
        }else{
            $persona = new Persona();
        }

        $persona->nombres = isset($request['nombres'])?$request['nombres']:'';
        $persona->apellido_paterno = isset($request['apellido_paterno'])?$request['apellido_paterno']:'';
        $persona->apellido_materno = isset($request['apellido_materno'])?$request['apellido_materno']:'';
        $persona->tipo_documento = isset($request['tipo_documento'])?$request['tipo_documento']:'';
        $persona->num_documento = isset($request['num_documento'])?$request['num_documento']:'';
        $persona->fecha_nacimiento = isset($request['fecha_nacimiento'])?$request['fecha_nacimiento']:'';
        $persona->sexo = isset($request['sexo'])?$request['sexo']:'';
        $persona->direccion = isset($request['direccion'])?$request['direccion']:'';
        $persona->telefono = isset($request['telefono'])?$request['telefono']:'';
        $persona->correo_electronico = isset($request['correo_electronico'])?$request['correo_electronico']:'';

        $persona->save();

        return $persona;
    }  

    public static function listarPersonas(){
        $personas = DB::table('personas')
                ->select('id','nombres','apellido_paterno','apellido_materno','tipo_documento','num_documento','fecha_nacimiento','sexo','direccion','telefono','correo_electronico')
                ->orderBy('id','desc')
                ->paginate(5);
        return $personas;
    }

    public static function listarPersonasReporte(){
        $personas = DB::table('personas')
                ->select('id','nombres','apellido_paterno','apellido_materno','tipo_documento','num_documento','fecha_nacimiento','sexo','direccion','telefono','correo_electronico')
                ->orderBy('id','desc')
                ->get();
        return $personas;
    }

    public static function buscarPersonas($name){
        $personas = DB::table('personas')
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
        Persona::where('id',$id)->delete();
    }
}
