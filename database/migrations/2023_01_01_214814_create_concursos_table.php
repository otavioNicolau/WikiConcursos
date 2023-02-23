<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ext_id')->unique();
            $table->unsignedInteger('edital_id');
            $table->dateTime('data_aplicacao')->nullable();
            $table->string('escolaridade_enum')->nullable();
            $table->string('arquivo_gabarito')->nullable();
            $table->string('arquivo_discursiva')->nullable();
            $table->string('arquivo_objetiva')->nullable();
            $table->string('arquivo_edital')->nullable();
            $table->string('nome_completo')->nullable();
            $table->string('url_concurso')->nullable();
            $table->string('area')->nullable(); 
            $table->string('especialidade')->nullable(); 
            $table->date('next_run')->nullable();
            $table->date('next_provas_run')->nullable();
            $table->timestamps(); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concursos');
    }
};
