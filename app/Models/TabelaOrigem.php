<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelaOrigem extends Model
{
    protected $table = 'tabela_origens'; // Nome da tabela no banco de dados

    protected $fillable = [
        'nome',
        'uf',
        'descricao',
    ];
}
