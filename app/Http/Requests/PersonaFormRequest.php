<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PersonaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {    
        return [
            'nombres' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'tipo_documento' => 'required',
            'num_documento' => [
                'required',
                function ($attribute, $value, $fail) {
                    LOG::info($this->get('tipo_documento'));
                    LOG::info("Longitud Documento:".strlen($value));
                    if($this->get('tipo_documento')=== 1){
                        LOG::info("Longitud Documento:".strlen($value));
                        if(strlen($value)!=8){
                            $fail('El DNI debe contener 8 caracteres');
                        }
                        else{
                            $patron = "/^[[:digit:]]+$/";
                            if (!preg_match($patron, $value)) {
                                $fail('El DNI debe contener solo numeros');
                            }
                        }
                    }
                    if($this->get('tipo_documento')==='4'){
                        if(strlen($value)>12 || strlen($value)<5){
                            $fail('El carnet de extranjeria no cumple con los requisitos de longitud');
                        }else{
                            $patron = "/^[[:alnum:]]+$/";
                            if (!preg_match($patron, $value)) {
                                $fail('El carnet de extranjeria debe contener solo numeros y letras');
                            }    
                        }
                    }
                    if($this->get('tipo_documento')==='6'){
                        if(strlen($value)!=11){
                            $fail('El RUC debe contener 11 caracteres');
                        }
                        else{
                            $patron = "/^[[:digit:]]+$/";
                            if (!preg_match($patron, $value)) {
                                $fail('El RUC debe contener solo numeros');
                            }
                        }
                    }
                    if($this->get('tipo_documento')==='9'){
                        if(strlen($value)>15 || strlen($value)<5){
                            $fail('El carne de solicitud de refugio no cumple con los requisitos de longitud');
                        }else{
                            $patron = "/^[[:alnum:]]+$/";
                            if (!preg_match($patron, $value)) {
                                $fail('El carne de solicitud de refugio debe contener solo numeros y letras');
                            }    
                        }
                    }
                    if($this->get('tipo_documento')==='11'){
                        if(strlen($value)>15 || strlen($value)<5){
                            $fail('La partidad de nacimiento no cumple con los requisitos de longitud');
                        }else{
                            $patron = "/^[[:alnum:]]+$/";
                            if (!preg_match($patron, $value)) {
                                $fail('La partidad de nacimiento debe contener solo numeros y letras');
                            }    
                        }
                    }
                }
            ],
            'fecha_nacimiento' => 'required',
            'sexo' => 'required',
        ];        
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nombres.required' => 'El :attribute es obligatorio',
            'apellido_paterno.required' => 'El :attribute es obligatorio',
            'apellido_materno.required' => 'El :attribute es obligatorio',
            'tipo_documento.required' => 'Seleccione el :attribute',
            'num_documento.required' => 'El :attribute es obligatorio',
            'fecha_nacimiento.required' => 'Seleccione la :attribute',
            'sexo.required' => 'Seleccione el :attribute',
        ];
    }

    public function attributes()
    {
        return [
            
        ];
    }
}
