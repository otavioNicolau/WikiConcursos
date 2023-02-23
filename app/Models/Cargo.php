<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ext_id',
        'orgao_id',
        'nome',
        'tipo',
        'descendentes_de',
        'descricao',
        'title',
        'next_run',
        'gpt_worked'
    ];
    
}
