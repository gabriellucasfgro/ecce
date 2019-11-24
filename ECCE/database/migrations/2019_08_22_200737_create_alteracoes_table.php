<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlteracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alteracoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aluno_matricula');
            $table->string('nome');
            $table->string('curso');
            $table->string('ano');
            $table->string('cpf');
            $table->string('rg');
            $table->string('nascimento');
            $table->string('modalidade');
            $table->string('campus');
            $table->string('naturalidade');
            $table->string('foto');
            $table->integer('status');
            $table->integer('aprovado');
            $table->string('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alteracoes');
    }
}
