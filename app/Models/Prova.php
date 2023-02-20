<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    use HasFactory;

    protected $table = 'provas';
    protected $fillable = [
        'ext_id', 'concurso_id', 'nome', 'next_run'
    ];


}
