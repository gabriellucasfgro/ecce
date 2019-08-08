<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Aluno extends Authenticatable
{
	public $primaryKey = 'matricula';

	protected $fillable = [
        'matricula',
        'nome',
        'curso',
        'ano',
        'cpf',
        'rg',
        'nascimento',
        'naturalidade',
        'campus',
        'modalidade',
        'password',
    ];

	public $incrementing = false;

    public $timestamps = false;

    protected $table = 'alunos';

    public function tipo()
   {
      return 'aluno';
   }
}
