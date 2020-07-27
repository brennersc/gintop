<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Empresa;


class UsersExportEmpresa implements FromView
{

     protected $exibir;

        public function __construct(Empresa $id)
        {
            $this->empresa = $id;
        }

    public function view(): View
    {
        
        $empresa = Empresa::where('id', '=', $this->empresa->id)->get();

        $exibir = Empresa::where('id', $this->empresa->id)->first();

        return view('exportar.empresa', ['exibir' => $exibir, 'empresa' => $empresa]);

    }
}