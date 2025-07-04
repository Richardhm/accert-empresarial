<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoEmpresarial extends Model
{
    protected $table = 'contrato_empresarial'; // Nome da tabela no banco de dados

    protected $fillable = [
        'plano_id',
        'tabela_origens_id',
        'user_id',
        'cnpj',
        'razao_social',
        'quantidade_vidas',
        'valor_plano',
        'vencimento_boleto',
        'senha_cliente',
        'valor_plano_odonto',
        'valor_plano_saude',
        'codigo_saude',
        'codigo_odonto',
        'codigo_externo',
        'data_boleto',
        'valor_pagar',
        'responsavel',
        'telefone',
        'celular',
        'email',
        'cidade',
        'uf',
        'plano_contrado',
        'pago',
        'codigo_vendedor'
    ];
}
