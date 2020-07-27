<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampoCredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_creds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_evento')->unsigned();;
            $table->string('nome');
            $table->string('slug');
            $table->string('tipo');
            $table->string('obrigatorio')->nullable();
            $table->string('unico')->nullable();
            $table->string('cracha')->nullable();
            $table->text('opcoes')->nullable();
            $table->integer('tamanho')->nullable();
            $table->string('valor_salvo')->nullable();
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
        Schema::dropIfExists('campo_creds');
    }
}
