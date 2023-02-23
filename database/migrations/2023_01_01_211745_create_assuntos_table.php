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
        Schema::create('assuntos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ext_id')->unique();
            $table->string('nome')->nullable();
            $table->unsignedBigInteger('materia_id');
            $table->string('hierarquia')->nullable();
            $table->text('descendentes')->nullable();
            $table->longText('descricao')->nullable();
            $table->date('next_run')->nullable();
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
        Schema::dropIfExists('assuntos');
    }
};
