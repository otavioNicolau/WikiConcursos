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
        Schema::table('comentarios', function (Blueprint $table) {
            $table->foreign('id_questao')->references('ext_id')->on('questoes')->onDelete('cascade');
        });

        Schema::table('editais', function (Blueprint $table) {
            $table->foreign('id_banca')->references('ext_id')->on('bancas');
            $table->foreign('id_orgao')->references('ext_id')->on('orgaos');
        });

        Schema::table('concursos', function (Blueprint $table) {
            $table->foreign('edital_id')->references('ext_id')->on('editais');
            $table->foreign('escolaridade_enum')->references('ext_id')->on('escolaridades');
        });

        Schema::table('questoes', function (Blueprint $table) {
            $table->foreign('concurso_id')->references('ext_id')->on('concursos')->onDelete('cascade');
            $table->foreign('id_materia')->references('ext_id')->on('materias')->onDelete('cascade');
            $table->foreign('id_assunto')->references('ext_id')->on('assuntos')->onDelete('cascade');
            $table->foreign('id_cargo')->references('ext_id')->on('cargos')->onDelete('cascade');
        });

        Schema::table('alternativas', function (Blueprint $table) {
            $table->foreign('id_questao')->references('ext_id')->on('questoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropForeign(['id_questao']);
        });

        Schema::table('editais', function (Blueprint $table) {
            $table->dropForeign(['id_banca']);
            $table->dropForeign(['id_orgao']);
        });

        Schema::table('concursos', function (Blueprint $table) {
            $table->dropForeign(['edital_id']);
            $table->dropForeign(['escolaridade_enum']);
        });

        Schema::table('questoes', function (Blueprint $table) {
            $table->dropForeign(['concurso_id']);
            $table->dropForeign(['id_materia']);
            $table->dropForeign(['id_assunto']);
            $table->dropForeign(['id_cargo']);
        });

        Schema::table('alternativas', function (Blueprint $table) {
            $table->dropForeign(['id_questao']);
        });
    }
};
