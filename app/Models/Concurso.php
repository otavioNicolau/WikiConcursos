<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    use HasFactory;

    protected $fillable = [
        'ext_id',
        'edital_id',
        'data_aplicacao',
        'escolaridade_enum',
        'arquivo_gabarito',
        'arquivo_discursiva',
        'arquivo_objetiva',
        'arquivo_edital',
        'nome_completo',
        'url_concurso',
        'area',
        'especialidade',
        'next_run'
    ];
    

    public function edital()
    {
        return $this->belongsTo('App\Edital', 'edital_id', 'ext_id');
    }
}
