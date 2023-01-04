<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orgao extends Model
{
    use HasFactory;

    protected $fillable = [
        'ext_id', 'nome', 'sigla', 'url', 'uuid_logo', 'orgao_regiao', 'orgao_uuid', 'caminho_logotipo_orgao', 'descricao'
    ];
}
