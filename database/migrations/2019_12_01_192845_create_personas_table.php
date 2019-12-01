<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres',60);
            $table->string('apellido_paterno',100);
            $table->string('apellido_materno',100);
            $table->string('tipo_documento',20);
            $table->string('num_documento',20);
            $table->date('fecha_nacimiento');
            $table->string('sexo',10);
            $table->string('direccion',70)->nullable();
            $table->string('telefono',20)->nullable();
            $table->string('correo_electronico',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
