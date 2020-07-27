<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caex extends Model
{
    //
    protected $fillable = [
        'id',
        'id_campo_caex',
        'id_evento',
        'nome',
        'email',
        'celular',
        'campo',
        'valor_salvo',
        'cracha',
    ];
}
