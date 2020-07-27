<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngressosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingressos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_evento')->unsigned();
            $table->bigInteger('id_ingresso')->nullable();
            $table->string('nome');
            $table->string('qntd');
            $table->string('preco');
            $table->string('total');
            $table->string('meia')->nullable();
            $table->string('meia_entrada')->nullable();
            $table->string('qntd_meia_entrada')->nullable();
            $table->string('preco_meia_entrada')->nullable();
            $table->string('total_meia_entrada')->nullable();
            $table->string('periodo');            
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('hora_inicio');
            $table->string('hora_fim');
            $table->string('dispo');
            $table->string('convidados');
            $table->string('qntd_min_compra');
            $table->string('qntd_max_compra');
            $table->string('vendidos')->default(0);
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('id_evento')
                ->references('id')
                ->on('eventos')
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
        Schema::dropIfExists('ingressos');
    }
}
