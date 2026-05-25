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
        'codigo_vendedor',
        'status_pagamento',
        'cadastrado_por',
        'etapa_atual',
        'plano_saude_id',
        'saude_uf',
        'saude_cidade',
        'saude_user_id',
        'saude_acomodacao',
        'saude_coparticipacao',
        'plano_odonto_id',
        'odonto_uf',
        'odonto_cidade',
        'odonto_user_id',
        'planilha_path',
        'aditivo_path',
        'data_aditivo',
        'data_adesao',
        'boleto_adesao_path',
        'boleto_adesao_valor',
        'justificativa_diferenca',
        'tem_diferenca_valor',
        'data_pgto',
        'forma_pagamento',
        'oriundo',
        'data_vigencia',
        'carteirinha_paths',
        'data_carteirinha',
        'primeiro_boleto_valor',
        'primeiro_boleto_vencimento',
        'boleto_saude_path',
        'demonstrativo_saude_path',
        'boleto_odonto_path',
        'demonstrativo_odonto_path',
        'data_primeiro_boleto',
        'data_baixa_finalizado',
        'finalizado_pdf_path',
    ];

    protected $casts = [
        'carteirinha_paths' => 'array',
    ];

    // Associação com o modelo de plano
    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    // Associação com o modelo de usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }





}
