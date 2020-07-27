<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caexes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_campo_caex')->unsigned();
            $table->bigInteger('id_evento')->unsigned();
            $table->string('nome');
            $table->string('email');
            $table->string('celular');
            $table->text('cpf');
            $table->text('campo');
            $table->text('valor_salvo')->nullable();
            $table->string('cracha')->nullable();
            $table->text('palestras')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->foreign('id_campo_caex')->references('id')->on('campo_caexes')->onDelete('cascade');
            $table->foreign('id_evento')->references('id')->on('eventos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caexes');
    }
}
