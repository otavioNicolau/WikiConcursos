<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvaQuestao extends Model
{
    use HasFactory;

    protected $table = 'provas_questoes';
    protected $fillable = ['concurso_id', 'prova_nome', 'numero_questao', 'questao_id', 'cargo_nome'];

}
