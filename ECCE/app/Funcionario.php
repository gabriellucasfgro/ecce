<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Funcionario extends Authenticatable
{
	public $primaryKey = 'matricula';

	protected $fillable = [
        'matricula',
        'nome',
        'cpf',
        'password',
    ];

	public $incrementing = false;

    public $timestamps = false;

    protected $table = 'funcionarios';

    public function tipo()
   {
      return 'secretaria';
   }
}