<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendaIngressosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_ingressos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_evento')->unsigned();
            $table->bigInteger('id_ingresso')->unsigned();
            $table->string('nome');
            $table->string('email');
            $table->string('celular');
            $table->string('cpf');
            $table->string('qntd');
            $table->string('preco');
            $table->string('total');
            $table->date('data_compra');
            $table->string('pago')->default('nao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('id_evento')
                ->references('id')
                ->on('eventos')
                ->onDelete('cascade');

            $table->foreign('id_ingresso')
                ->references('id')
                ->on('ingressos')
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
        Schema::dropIfExists('venda_ingressos');
    }
}
