<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';

    protected $fillable = ['nome', 'uf'];

    public function faixas()
    {
        return $this->hasMany(FaixaEtariaValor::class, 'cidade_id');
    }
}
