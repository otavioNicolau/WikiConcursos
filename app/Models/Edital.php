<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edital extends Model
{
    use HasFactory;

    protected $fillable = [
        'ext_id', 'nome', 'id_banca', 'id_orgao', 'prazo_inscricao', 'ano', 'id_arquivo', 'data_inclusao',
        'possui_guia', 'vagas', 'salario_inicial_de', 'salario_inicial_ate', 'taxa_inscricao_de', 'taxa_inscricao_ate',
        'pagina_concurso', 'url', 'publicado', 'ficticio', 'cargo_nome', 'cargo_sigla'
    ];

    public function banca()
    {
        return $this->belongsTo('App\Banca', 'id_banca', 'ext_id');
    }

    public function orgao()
    {
        return $this->belongsTo('App\Orgao', 'id_orgao', 'ext_id');
    }
}
