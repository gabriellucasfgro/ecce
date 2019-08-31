<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Funcionario extends Authenticatable
{
	public $primaryKey = 'id';

	protected $fillable = [
        'id',
        'nome',
        'username',
        'password',
    ];

    public $timestamps = false;

    protected $table = 'funcionarios';

    public function tipo()
   {
      return 'secretaria';
   }
}