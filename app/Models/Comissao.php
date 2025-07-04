<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    protected $table = 'comissao'; // Nome da tabela no banco de dados

    protected $fillable = [
        'user_id',
        'valor',
    ];
}
