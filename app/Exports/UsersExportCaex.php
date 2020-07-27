<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Evento;
use App\Campo_caex;


class UsersExportCaex implements FromView
{
        protected $exibir;

        public function __construct(Evento $id)
        {
            $this->evento = $id;
        }

    public function view(): View
    {
        $campoCaex = Campo_caex::where('id_evento', '=', $this->evento->id)->get();

        $exibir = Evento::where('id', $this->evento->id)->first();

        return view('exportar.caex', ['exibir' => $exibir, 'campoCaex' => $campoCaex]);

    }
}