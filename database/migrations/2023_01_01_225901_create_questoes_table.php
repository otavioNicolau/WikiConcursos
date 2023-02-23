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
            $table->unsignedInteger('id_materia')->nullable();
            $table->unsignedInteger('nome_assunto')->nullable();
            $table->text('enunciado')->nullable();
            $table->boolean('correcao_questao')->nullable();
            $table->integer('numero_alternativa_correta')->nullable();
            $table->boolean('possui_comentario')->nullable();
            $table->boolean('anulada')->nullable();
            $table->string('tipo_questao')->nullable();
            $table->boolean('desatualizada')->nullable();
            $table->string('formato_questao')->nullable();
            $table->integer('data_atual')->nullable();
            $table->boolean('gabarito_preliminar')->nullable();
            $table->boolean('desatualizada_com_gabarito_preliminar')->nullable();
            $table->boolean('desatualizada_com_gabarito_definivo')->nullable();
            $table->boolean('questao_oculta')->nullable();
            $table->string('data_publicacao')->nullable();
            $table->date('next_run')->nullable();
            $table->date('next_comentario_run')->nullable();

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
