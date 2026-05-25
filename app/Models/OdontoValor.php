<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontoValor extends Model
{
    protected $table = 'odonto_valores';

    protected $fillable = ['plano_id', 'cidade_id', 'valor'];

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }
}
