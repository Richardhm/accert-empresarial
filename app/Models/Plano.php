<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $table = 'planos'; // Nome da tabela no banco de dados

    protected $fillable = [
        'nome',
        'empresarial',
    ];
}
