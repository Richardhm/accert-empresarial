<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaixaEtariaValor extends Model
{
    protected $table = 'faixas_etarias_valores';

    protected $fillable = [
        'plano_id', 'cidade_id', 'faixa',
        'com_copart_apart', 'com_copart_enfer',
        'sem_copart_apart', 'sem_copart_enfer',
    ];

    public static array $labels = [
        0 => '0 a 18',
        1 => '19 a 23',
        2 => '24 a 28',
        3 => '29 a 33',
        4 => '34 a 38',
        5 => '39 a 43',
        6 => '44 a 48',
        7 => '49 a 53',
        8 => '54 a 58',
        9 => '59+',
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }
}
