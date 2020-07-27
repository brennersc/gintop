<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Evento;
use App\Campo_cred;


class UsersExport implements FromView
{
        protected $exibir;

        public function __construct(Evento $id)
        {
            $this->evento = $id;
        }

    public function view(): View
    {
        $campoCred = Campo_cred::where('id_evento', '=', $this->evento->id)->where('ativo', true)->get();

        $exibir = Evento::where('id', $this->evento->id)->first();

        return view('exportar.visitantes', ['exibir' => $exibir, 'campoCred' => $campoCred]);

    }
}
