<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
    Route::any('user/info', 'API\UsersApiController@info');
    Route::put('user/update', 'API\PersonaApiController@updateProfile');

Route::group(['preffix' => 'api'], function () {

    Route::get('tipo_documento/lista_tipo_documento_to_persona', 'API\PersonaApiController@listaTipoDocumento');
    
    /**
     * Todas las rutas deben de estar con la misma raiz (si estan relacionadas obiamente)
     */
    Route::post('persona/importar', 'API\ExcelExportApiController@importExcel');
	Route::get('persona/reporte', 'API\ExcelExportApiController@exportExcelPersona');
    // no se pasan slugs a las busquedas, te limita a busquedas sencillas, no guarda los resultados en el cache y ademas te desordena todo el historial, pasar los filtrso como parametros
    //Route::get('personas/buscar/{name?}', 'API\PersonaApiController@buscarPersona');
    Route::get('personas/buscar', 'API\PersonaApiController@buscarPersona');
    
    Route::get('personas', 'API\PersonaApiController@index');
    Route::get('personas', 'API\PersonaApiController@index');
    Route::post('persona', 'API\PersonaApiController@create');
    Route::put('persona', 'API\PersonaApiController@update');
	Route::delete('persona/{persona_id}', 'API\PersonaApiController@delete');    


    /**
     * ToDo:validar la importacion, que exista el archivo y luego validar que este correcto
     */
    

/**
 * Todo: agregar informacion de areas
 */
/**
 * Todo: agregar informacion de afps
 */

});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });