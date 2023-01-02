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
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ext_id')->unique();
            $table->unsignedInteger('id_materia');
            $table->unsignedInteger('id_assunto');
            $table->unsignedInteger('id_cargo');
            $table->unsignedInteger('concurso_id');
            $table->integer('numero_questao_atual');
            $table->text('enunciado');
            $table->boolean('correcao_questao');
            $table->integer('numero_alternativa_correta');
            $table->boolean('possui_comentario');
            $table->boolean('anulada');
            $table->string('tipo_questao');
            $table->boolean('desatualizada');
            $table->string('formato_questao');
            $table->integer('data_atual');
            $table->boolean('gabarito_preliminar');
            $table->boolean('desatualizada_com_gabarito_preliminar');
            $table->boolean('desatualizada_com_gabarito_definivo');
            $table->boolean('questao_oculta');
            $table->dateTime('data_publicacao');
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
        Schema::dropIfExists('questoes');
    }
};
