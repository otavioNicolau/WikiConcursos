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
        Schema::create('orgaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ext_id')->unique();
            $table->string('nome');
            $table->index('nome');
            $table->string('sigla');
            $table->string('url');
            $table->text('uuid_logo');
            $table->string('orgao_regiao')->nullable(); // VEM DA REQUISIÇÃO EDITAIS
           // $table->text('orgao_uuid')->nullable(); // VEM DA REQUISIÇÃO EDITAIS
           // $table->string('caminho_logotipo_orgao')->nullable(); // VEM DAS QUESTÕES
            $table->string('descricao')->nullable();
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
        Schema::dropIfExists('orgaos');
    }
};
