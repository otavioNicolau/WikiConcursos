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
        Schema::create('editais', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ext_id')->unique();
            $table->string('nome');
            $table->unsignedInteger('id_banca');
            $table->unsignedInteger('id_orgao');
            $table->dateTime('prazo_inscricao');
            $table->year('ano');
            $table->unsignedInteger('id_arquivo'); // analisar
            $table->dateTime('data_inclusao');
            $table->boolean('possui_guia');
            $table->string('vagas');
            $table->decimal('salario_inicial_de', 10, 2);
            $table->decimal('salario_inicial_ate', 10, 2);
            $table->decimal('taxa_inscricao_de', 10, 2);
            $table->decimal('taxa_inscricao_ate', 10, 2);
            $table->string('pagina_concurso');
            $table->string('url');
            $table->boolean('publicado');
            $table->boolean('ficticio');
            $table->string('cargo_nome'); // organizar
            $table->string('cargo_sigla'); // organizar
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
        Schema::dropIfExists('editais');
    }
};
