<?php

namespace App\Exports;

use App\Sala;
use App\Registro;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalasExport implements FromView
{
    protected $exibir;

    public function __construct(Sala $id)
    {
        $this->sala = $id;
    }

    public function view(): View
    {

    $sala = Registro::where('id_sala', '=', $this->sala->id)->orderByRaw('nome')->get();

    return view('exportar.sala', ['sala' => $sala]);

    }
}