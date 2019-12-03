<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->string('matricula');
            $table->string('nome');
            $table->string('curso');
            $table->string('ano');
            $table->string('cpf');
            $table->string('rg');
            $table->string('nascimento');
            $table->string('modalidade');
            $table->string('campus');
            $table->string('naturalidade');
            $table->string('foto')->default('default.png');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alunos');
    }
}
