<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternativa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ext_id',
        'id_questao',
        'alternativa',
        'next_run',
    ];


    public function questao()
    {
        return $this->belongsTo('App\Questao', 'id_questao', 'ext_id');
    }
}
