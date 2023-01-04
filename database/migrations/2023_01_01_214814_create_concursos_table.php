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
            $table->dateTime('data_aplicacao');
            $table->string('escolaridade_enum');
            $table->string('arquivo_gabarito');
            $table->string('arquivo_discursiva');
            $table->string('arquivo_objetiva');
            $table->string('arquivo_edital'); // analisar (duplicidade)
            $table->string('nome_completo');
            $table->string('url_concurso'); 
            $table->string('area'); // vem da questão
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
