<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alteracoes extends Model
{
    public $timestamps = false;

    public $primaryKey = 'matricula';

    public $incrementing = false;
}
