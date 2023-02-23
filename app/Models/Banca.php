<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banca extends Model
{
    use HasFactory;

    protected $fillable = [
        'ext_id',
        'nome',
        'sigla',
        'url',
        'descricao',
        'next_run'
    ];
    
}
