<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alteracoes extends Model
{
    public $primaryKey = 'id';

    protected $table = 'alteracoes';

    public $timestamps = false;
}