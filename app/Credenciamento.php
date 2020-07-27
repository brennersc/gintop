<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credenciamento extends Model
{
    //
    protected $fillable = [
        'id',
        'id_campo_cred',
        'id_evento',
        'nome',
        'email',
        'celular',
        'campo',
        'valor_salvo',
        'cracha',
    ];
}
