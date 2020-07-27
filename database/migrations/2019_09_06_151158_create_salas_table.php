<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_evento')->unsigned();
            $table->string('nome');
            $table->text('nome_local')->nullable();
            $table->text('palestrante')->nullable();
            $table->integer('quantidade')->default(0);
            $table->integer('visitantes')->default(0);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('hora_inicio');
            $table->string('hora_fim');
            $table->boolean('ativo')->default(true);
            
            $table->timestamps();

            $table->foreign('id_evento')
            ->references('id')
            ->on('eventos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salas');
    }
}
