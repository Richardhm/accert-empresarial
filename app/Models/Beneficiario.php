<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'planilha_beneficiarios';

    protected $fillable = [
        'contrato_empresarial_id',
        'tipo',
        'nome_completo',
        'nome_titular',
        'cpf',
        'data_nascimento',
        'idade',
        'nome_mae',
        'acomodacao',
        'sexo',
        'grau_parentesco',
        'data_casamento',
        'telefone',
        'valor',
    ];

    public function contrato()
    {
        return $this->belongsTo(ContratoEmpresarial::class, 'contrato_empresarial_id');
    }
}
