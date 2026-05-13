<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vale extends Model
{
    protected $table = 'vales';

    protected $fillable = [
        'user_id',
        'valor',
        'mes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
