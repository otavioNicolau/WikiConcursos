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
            $table->longText('nome')->nullable();
            $table->unsignedInteger('id_banca')->nullable();
            $table->unsignedInteger('id_orgao')->nullable();
            $table->dateTime('prazo_inscricao')->nullable();
            $table->string('ano')->nullable();
            $table->dateTime('data_inclusao')->nullable();
            $table->string('vagas')->nullable();
            $table->decimal('salario_inicial_de', 10, 2)->nullable();
            $table->decimal('salario_inicial_ate', 10, 2)->nullable();
            $table->decimal('taxa_inscricao_de', 10, 2)->nullable();
            $table->decimal('taxa_inscricao_ate', 10, 2)->nullable();
            $table->string('pagina_concurso')->nullable();
            $table->string('url')->nullable();
            $table->boolean('publicado')->nullable();
            $table->boolean('ficticio')->nullable();
            $table->string('cargo_nome')->nullable();
            $table->string('cargo_sigla')->nullable();
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
