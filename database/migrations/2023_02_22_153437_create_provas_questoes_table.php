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
        Schema::create('provas_questoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('concurso_id'); 
            $table->text('prova_nome'); 
            $table->integer('numero_questao'); 
            $table->unsignedInteger('questao_id'); 
            $table->text('cargo_nome')->nullable(); // cargoSigla // questao
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
        Schema::dropIfExists('provas_questoes');
    }
};
