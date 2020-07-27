<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendaMesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_mesas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_evento')->unsigned();
            $table->bigInteger('id_mesa')->unsigned();
            $table->string('cpf');
            $table->string('qual');
            $table->string('valor');
            $table->date('data_compra');
            $table->string('pago')->default('nao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('id_evento')
                ->references('id')
                ->on('eventos')
                ->onDelete('cascade');

            $table->foreign('id_mesa')
                ->references('id')
                ->on('mesas')
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
        Schema::dropIfExists('venda_mesas');
    }
}
