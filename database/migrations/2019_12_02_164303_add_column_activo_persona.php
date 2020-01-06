<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * crear migracion para agregar o quitar columnas a tablas
 */
class AddColumnActivoPersona extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personas',function(Blueprint $table){
            /**
             * Crear valores predeterminados o permitir que los campos soporten null
             */
            $table->boolean('activo')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personas',function(Blueprint $table){
            $table->dropColumn('activo');
        });
    }
}