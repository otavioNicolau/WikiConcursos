<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    use HasFactory;

    protected $table = 'questoes';

    protected $fillable = [
        'ext_id', 'id_materia', 'capitulo','id_assunto_anexo_capitulo', 'id_cargo', 'concurso_id', 'numero_questao_atual', 'enunciado',
        'correcao_questao', 'numero_alternativa_correta', 'possui_comentario', 'anulada', 'tipo_questao', 'desatualizada',
        'formato_questao', 'data_atual', 'gabarito_preliminar', 'desatualizada_com_gabarito_preliminar',
        'desatualizada_com_gabarito_definivo', 'questao_oculta', 'data_publicacao'
    ];

    public function materia()
    {
        return $this->belongsTo('App\Materia', 'id_materia', 'ext_id');
    }

    public function assunto()
    {
        return $this->belongsTo('App\Assunto', 'id_assunto', 'ext_id');
    }

    public function cargo()
    {
        return $this->belongsTo('App\Cargo', 'id_cargo', 'ext_id');
    }
}
