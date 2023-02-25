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
        Schema::create('bancas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ext_id')->unique();
            $table->string('nome')->nullable();
            $table->string('sigla')->nullable();
            $table->string('url')->nullable();
            $table->boolean('gpt_worked')->default(false);
            $table->longText('descricao')->nullable();
            $table->date('next_run')->nullable();
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
        Schema::dropIfExists('bancas');
    }
};
