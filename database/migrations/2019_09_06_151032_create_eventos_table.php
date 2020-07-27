<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_empresa')->unsigned();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('hora_inicio');
            $table->string('hora_fim');
            $table->text('tamanho_impressao');
            $table->text('codigo')->nullable();
            $table->text('descricao')->nullable();
            $table->text('nome_local')->nullable();
            $table->text('endereco_local')->nullable();
            $table->text('sala')->nullable();
            $table->text('ingresso')->nullable();
            $table->text('mesa')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('url_imagem')->nullable();
            $table->timestamps();

            $table->foreign('id_empresa')
                ->references('id')
                ->on('empresas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
