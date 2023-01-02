<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assunto extends Model
{
    protected $table = 'assuntos';
    use HasFactory;


    protected $fillable = [
        'ext_id',
        'nome',
        'materia_id',
        'hierarquia',
        'descendentes',
        'descricao',
    ];
}
