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

Route::group(['preffix' => 'api'], function () {

    Route::get('tipo_documento/lista_tipo_documento_to_persona', 'API\PersonaApiController@listaTipoDocumento');

    Route::get('personas', 'API\PersonaApiController@index');
    Route::post('persona', 'API\PersonaApiController@create');
    Route::get('personas/buscar/{name?}', 'API\PersonaApiController@buscarPersona');
    Route::put('persona', 'API\PersonaApiController@update');
	Route::delete('persona/{persona_id}', 'API\PersonaApiController@delete');    

	Route::get('export/persona', 'API\ExcelExportApiController@exportExcelPersona');

	Route::post('reporte_excel/importar', 'API\ExcelExportApiController@importExcel');
    

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
