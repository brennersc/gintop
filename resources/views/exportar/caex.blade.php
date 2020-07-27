@php
//pegar valores dos visitantes ja cadastrados
//nome email celular e todos valores salvos ja cadastrados juntos
$itens = DB::table('caexes')
        ->select('nome','cpf', 'palestras')
        ->selectRaw('GROUP_CONCAT(DISTINCT valor_salvo ORDER BY id ASC) as valor_salvo')
        ->selectRaw('GROUP_CONCAT(DISTINCT id ORDER BY id ASC) as id')
        ->where('id_evento', [$exibir->id])
        ->where('ativo', true)
        ->orderByRaw('nome')
        ->groupBy('nome','cpf', 'palestras')
        ->get();

@endphp

<table class="table table-ordered table-bordered table-hover table-striped table-sm " id="tabelaUsuario">
        <thead>
                <tr>
                        {{-- <th><b>Nome</b></th>
                        <th><b>Email</b></th>
                        <th><b>Celular</b></th> --}}
                        @foreach ($campoCaex as $cr)
                                <th><b>{{$cr->nome}}</b></th>
                        @endforeach
                </tr>
        </thead>
        <tbody>
                @foreach ($itens as $tabela)
                @php
                $i = 0;
                $strExemple = $tabela->valor_salvo;
                $valor_campos = explode(',', $strExemple);
                @endphp
                <tr>
                        {{-- <td>{{$tabela->nome}}</td>
                        <td>{{$tabela->email}}</td>
                        <td>{{$tabela->celular}}</td> --}}
                        @foreach ($valor_campos as $valor)
                        @php
                        if((preg_replace("/[0-9]/" , "" , $valor ) === 'null')){
                                $valor = '';
                        }
                        $i++;
                        @endphp
                        <td>{{$valor}}</td>
                        @endforeach
                </tr>
                @endforeach
        </tbody>
</table>