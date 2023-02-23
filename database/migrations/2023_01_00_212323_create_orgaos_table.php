<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
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
            $table->string('nome')->nullable();
            $table->string('sigla')->nullable();
            $table->string('url')->nullable();
            $table->text('uuid_logo')->nullable();
            $table->string('orgao_regiao')->nullable();
            $table->longText('descricao')->nullable();
            $table->date('next_run')->nullable();
            $table->date('next_cargo_run')->nullable();
            $table->boolean('gpt_worked')->default(false);
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
