<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carteirinha extends Model
{
    public $primaryKey = 'aluno_matricula';

	public $incrementing = false;

    public $timestamps = false;

    protected $table = 'carteirinhas';
}
