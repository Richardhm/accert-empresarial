<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $table = 'pagamentos';

    protected $fillable = [
        'contrato_empresarial_id',
        'tipo_planilha',
        'empresa_conveniada',
        'codigo_identificado',
        'razao_social_planilha',
        'vencimento',
        'parcela',
        'vl_base_com',
        'pct_imposto',
        'vl_liquido',
        'pc_dist',
        'vl_a_pagar',
        'arquivo_original',
    ];

    public function contrato()
    {
        return $this->belongsTo(ContratoEmpresarial::class, 'contrato_empresarial_id');
    }
}
