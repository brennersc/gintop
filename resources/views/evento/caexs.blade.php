@php
//pegar valores dos visitantes ja cadastrados
//nome email celular e todos valores salvos ja cadastrados juntos
$items = DB::table('caexes')
->select('palestras','nome','email','cpf','celular')
->selectRaw('GROUP_CONCAT(valor_salvo ORDER BY id ASC SEPARATOR ";") as valor_salvo')
->selectRaw('GROUP_CONCAT(id ORDER BY id ASC SEPARATOR ";") as id')
->where('id_evento', [$exibir->id])
->where('ativo', true)
->orderByRaw('nome')
->groupBy('palestras','nome','email','cpf','celular')
->paginate(20);

// var_export($items);
// die;

use App\Sala;

@endphp
@extends('layout.app', ["current" => "evento"])

@section('body')

@if(session('mensagem'))
<div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
        style="box-shadow: 0px 0px 20px #A4A4A4;">
        Visitente {{ session('mensagem') }} com Sucesso!!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
</div>
@endif

<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-9">
                                <h3>Lista Visitantes - <b>{{$exibir->nome}}</b></h3>
                        </div>

                        <div class="col-3">
                                <a href="#" class="btn btn-md btn-outline-primary ml-1 float-right" data-toggle="modal"
                                        data-target="#ExemploModalCentralizado"><i class="fas fa-file-import"></i>
                                        Importar</a>
                                <a href="{{route('excelcaex', ['exibir' => $exibir->id])}}"
                                        class="btn btn-md btn-outline-success float-right"><i
                                                class="fas fa-file-export"></i>
                                        Exportar</a>
                        </div>
                </div>
                <br>
                <div class="row">
                        <div class="col-12">
                                <form class="form-sm" id="form-procurarcred" method="POST" role="search"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="input-group input-group-sm">
                                                <input type="hidden" id="id_evento_procurar" name="id_evento"
                                                        value="{{$exibir->id}}">
                                                <input name="busca" id="procurar" class="form-control"
                                                        placeholder="Busque pelo Visitante...">
                                                <div class="input-group-prepend ">
                                                        <div class="input-group-text"><i class="fas fa-search"></i>
                                                        </div>
                                                </div>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
        <div class="card-body">
                <div id="retornar" class="procurar"></div>
                <div class="table-responsive">
                        <table class="table table-ordered table-bordered table-hover table-striped table-sm"
                                id="tabelaUsuario">
                                <thead>
                                        <tr>
                                                {{-- <th>Nome</th>
                                                <th>Email</th>
                                                <th>Celular</th> --}}
                                                @foreach ($campoCred as $cr)
                                                <th>{{$cr->nome}}</th>
                                                @endforeach
                                                <th>Palestras</th>
                                                <th>Ações</th>
                                        </tr>
                                </thead>
                                <tbody style="font-size: 13px;">
                                        @foreach ($items as $tabela)
                                        @php
                                        $i = 0;
                                        $cod = str_replace(';' ,'', $tabela->id);
                                        // $cod = base64_encode($cod);
                                        $strExemple = $tabela->valor_salvo;
                                        $valor_campos = explode(';', $strExemple);

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
                                                <td>{{$tabela->palestras}}</td>
                                                <td style="text-align:center;">
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                <button class="btn btn-sm btn-outline-primary"
                                                                        role="button" data-toggle="modal"
                                                                        data-target="#edit{{$cod}}"><i
                                                                                class="far fa-edit"></i> Editar</button>
                                                                <a href="{{route('imprimircaex', ['cpf' => $cpf->email, 'id' => $exibir->id])}}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary"><i
                                                                                class="fas fa-print"></i> Imprimir</a>
                                                                <button role="button" data-toggle="modal"
                                                                        data-target="#apagar{{$cod}}"
                                                                        class="btn btn-sm btn-outline-danger"><i
                                                                                class="fas fa-trash-alt"></i>
                                                                        Apagar</button>
                                                        </div>
                                                </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                        </table>
                </div>
        </div>
        <div class="card-footer">
                <div class="row">
                        <div class="col-5">
                                <button class="btn btn-sm btn-primary" role="button" data-toggle="modal"
                                        data-target="#modalvisitantes">Novo Usuário</button>
                        </div>
                        <div class="col-7">
                                {{$items->links()}}
                        </div>
                </div>
        </div>
</div>
<!-- Modal importar -->
<div class="modal fade" id="ExemploModalCentralizado" tabindex="-1" role="dialog"
        aria-labelledby="TituloModalCentralizado" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="TituloModalCentralizado">Importar lista de Visitantes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="alert alert-warning" role="alert">
                                <strong>Atenção</strong>, antes de enviar o arquivo verifique se os campos do cabeçalho
                                são os mesmo seguintes,
                                @foreach ($campoCred as $cr)
                                <b>{{$cr->nome}}</b>,
                                @endforeach
                                caso haja campos a mais ou faltando, apague ou insira os mesmos!
                        </div>
                        <div class="modal-body">
                                <form class="form" id="formImport"
                                        action="{{route('importcaex', ['id' => $exibir->id])}}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                                <div class="row">
                                                        <div class="col-9">
                                                                <div class="custom-file">
                                                                        <label for="import"
                                                                                class="custom-file-label">Selecione um
                                                                                arquivo tipo .xlsx</label>
                                                                        <input type="file" name="import"
                                                                                class="custom-file-input" id="import">
                                                                </div>
                                                        </div>
                                                        <div class="col-3">
                                                                <button type="submit" class="btn btn-md btn-success"><i
                                                                                class="fas fa-paper-plane"></i>
                                                                        Enviar</button>
                                                        </div>
                                                </div>
                                        </div>
                                </form>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                </div>
        </div>
</div>

<!-- Modal Novo visitente -->
<div class="modal fade bd-example-modal-xl" id="modalvisitantes" tabindex="-1" role="dialog"
        aria-labelledby="TituloModalCentralizado" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="TituloModalCentralizado">Novo Visitante</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <form class="form" id="formCredencianto" action="/cadastrarcaex/{{$exibir->id}}"
                                        method="POST">
                                        @csrf
                                        {{-- <div class="row">
                                                        <div class="col-md-4 col-12">
                                                                <label for="nome">Nome:</label>
                                                                <input type="text" class="form-control valor" id='valor1' custo='custo1' id="nome" placeholder="nome" required maxlength="50" custo='custo1'>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                                <label for="email">Email:</label>
                                                                <input type="email" class="form-control valor" id='valor2' custo='custo2' id="nome" placeholder="Email" required maxlength="100">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                                <label for="celular">Celular:</label>
                                                                <input type="text" class="form-control valor phone" id='valor3' custo='custo3' id="celular" placeholder="(99) 99999-9999" required maxlength="15">
                                                        </div>
                                                </div> --}}
                                        <div class="row">
                                                @foreach ($campoCred as $camposCred)
                                                <input type="hidden" name="cracha[]" value="{{$camposCred->cracha}}">
                                                <input type="hidden" name="id_campo_caex[]" value="{{$camposCred->id}}">
                                                <input type="hidden" id="id_evento" name="id_evento[]"
                                                        value="{{$camposCred->id_evento}}">
                                                <input type="hidden" name="campo[]" value="{{$camposCred->nome}}">
                                                <input type="hidden" name="tipo[]" value="{{$camposCred->tipo}}">
                                                <input type="hidden" name="nome[]" class='custo1'>
                                                <input type="hidden" name="email[]" class='custo2'>
                                                <input type="hidden" name="celular[]" class='custo3'>
                                                <input type="hidden" name="cpf[]" class='custo4'>
                                                {{-- <input type="hidden" name="palestras[]" class='custo5'> --}}
                                                <div class="col-md-6 col-12">

                                                        <br>
                                                        <label for="valor_salvo[]">{{$camposCred->nome}}:</label>
                                                        @php
                                                        $strExemple = $camposCred->opcoes;
                                                        $opcoes = explode(';', $strExemple);
                                                        if($camposCred->obrigatorio == 1){
                                                        $camposCred->obrigatorio = 'required' ;
                                                        }elseif ($camposCred->obrigatorio == 0) {
                                                        $camposCred->obrigatorio = ' ' ;
                                                        }
                                                        @endphp
                                                        @switch($camposCred->tipo)
                                                        @case('text')
                                                        @if($camposCred->nome == 'Nome')
                                                        <input type="text" class="form-control valor"
                                                                name="valor_salvo[]" id="valor_salvo[]"
                                                                placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}}
                                                                maxlength="{{$camposCred->tamanho}}" custo='custo1'>
                                                        @else
                                                        <input type="text" class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}}
                                                                maxlength="{{$camposCred->tamanho}}">
                                                        @endif
                                                        <small>@if($camposCred->cracha == 1) Impressão @endif</small>

                                                        @break
                                                        @case('number')
                                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}}>
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('cpf')
                                                        @if($camposCred->nome == 'Cpf')
                                                        <input type="text" class="form-control cpf valor"
                                                                name="valor_salvo[]" id="cpf" onblur=validarcpf2()
                                                                placeholder="EX: 999.999.999-99"
                                                                {{$camposCred->obrigatorio}} custo='custo4'>
                                                        @else
                                                        <input type="text" class="form-control cpf" name="valor_salvo[]"
                                                                id="cpf" onblur=validarcpf2()
                                                                placeholder="EX: 999.999.999-99"
                                                                {{$camposCred->obrigatorio}}>
                                                        @endif
                                                        <div id="invalidocpf" class="invalid-feedback">CPF
                                                                inválido!
                                                        </div>
                                                        <div id="existecpf" class="invalid-feedback">CPF já
                                                                existente!
                                                        </div>
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('cnpj')
                                                        <input type="text" class="form-control cnpj"
                                                                name="valor_salvo[]" id="valor_salvo[]"
                                                                placeholder="EX: 00.000.000/0000-00"
                                                                {{$camposCred->obrigatorio}}>
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('email')
                                                        @if($camposCred->nome == 'Email')
                                                        <input type="email" class="form-control valor"
                                                                name="valor_salvo[]" id="email" onblur=validaremail()
                                                                placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}} custo='custo2'>
                                                        @else
                                                        <input type="email" class="form-control " name="valor_salvo[]"
                                                                id="email" onblur=validaremail()
                                                                placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}}>
                                                        @endif
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('tel')
                                                        @if($camposCred->nome == 'Celular')
                                                        <input type="text" class="form-control phone valor"
                                                                name="valor_salvo[]" id="valor_salvo[]"
                                                                placeholder="EX: (99) 99999-9999"
                                                                {{$camposCred->obrigatorio}} custo='custo3'>
                                                        @else
                                                        <input type="text" class="form-control phone"
                                                                name="valor_salvo[]" id="valor_salvo[]"
                                                                placeholder="EX: (99) 99999-9999"
                                                                {{$camposCred->obrigatorio}}>
                                                        @endif
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('date')
                                                        <input type="text" class="form-control data_ data_calendario" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="__/__/____"
                                                                {{$camposCred->obrigatorio}} maxlength="6">
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('checkbox')
                                                        @foreach ($opcoes as $op)
                                                        <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                        name="checkbox[]" id="checkbox[]"
                                                                        value='{{$op}}' {{$camposCred->obrigatorio}}>
                                                                <label class="form-check-label"
                                                                        for="checkbox[]">{{$op}}</label>
                                                        </div>
                                                        @endforeach
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @case('select')
                                                        <select class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" {{$camposCred->obrigatorio}}>
                                                                @foreach ($opcoes as $op)
                                                                <option value='{{$op}}'>{{$op}}</option>
                                                                @endforeach
                                                        </select>
                                                        <small>@if($camposCred->cracha == 1) Impressão
                                                                @endif</small>
                                                        @break
                                                        @default
                                                        @endswitch
                                                </div>
                                                @endforeach
                                                <?php
                                                $i = 0;
                                                $contadorsala = count($salas);
                                                ?>
                                                @if(count($salas) > 0)

                                                <div class="col-md-6 col-12">
                                                        <br>
                                                        <label for="Palestras">Palestras: </label><br>
                                                        <div class="">
                                                                <input type="hidden" name="contador" id="contador"
                                                                        class="contador" value='{{$contadorsala}}'>
                                                                @foreach($salas as $sal)
                                                                <input type="checkbox" onclick="({{$sal->id}})"
                                                                        name="palestras[]" id="palestras{{$i}}"
                                                                        class="palestras {{str_replace(' ','',$sal->nome)}} valor"
                                                                        value='{{$sal->nome}}' custo='custo5'>
                                                                <span style="font-size: 1.125rem;">{{$sal->nome}}
                                                                </span> - <small id="hora{{$i}}" class="hora{{$i}}"
                                                                        value="{{$i}}"> data
                                                                        {{$sal_inicio = date("d/m/Y", strtotime($sal->data_inicio))}}
                                                                        horário <span id="hora_ini{{$i}}"
                                                                                class="hora_ini{{$i}}"
                                                                                value="{{$i}}">{{$sal->hora_inicio}}</span>
                                                                        às <span id="hora_fim{{$i}}"
                                                                                class="hora_fim{{$i}}"
                                                                                value="{{$i}}">{{$sal->hora_fim}}</span></small><br>
                                                                <?php $i++ ; ?>
                                                                @endforeach
                                                        </div>
                                                </div>

                                                @endif
                                        </div>
                                        <br>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Fechar</button>
                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
</div>

<?php $z = 0; ?>
@foreach ($items as $tabela)
@php
//$idcamp = $id_visitante[$key];

$cod = str_replace(';' ,'',$tabela->id);
// $cod = $tabela->id;
// $cod = base64_encode($cod);

$pegaid = $tabela->id;
$idcampo = explode(';', $pegaid);

$strExemple = $tabela->valor_salvo;
$valor = explode(';', $strExemple);

$j = 0;
@endphp

<!-- EDIAR visitente -->
<div class="modal fade bd-example-modal-xl" id="edit{{$cod}}" tabindex="-1" role="dialog"
        aria-labelledby="TituloModalCentralizado" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="TituloModalCentralizado">Editar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <form class="form" id="formCredencianto" action="/editarcaex/" method="get">
                                        @csrf
                                        <div class="row">
                                                @foreach($campoCred as $key => $camposCred)
                                                @php
                                                $idcamp = $idcampo[$key];
                                                $valor2 = $valor[$key];

                                                if((preg_replace("/[0-9]/" , "" , $valor2) === 'null')){
                                                $valor2 = '';
                                                }
                                                $j++;
                                                @endphp
                                                <input type="hidden" name="id[]" value="{{$idcamp}}">
                                                <input type="hidden" name="id_campo_caex[]" value="{{$camposCred->id}}">
                                                <input type="hidden" name="cracha[]" value="{{$camposCred->cracha}}">
                                                <input type="hidden" name="id_evento[]"
                                                        value="{{$camposCred->id_evento}}">
                                                <input type="hidden" name="campo[]" value="{{$camposCred->nome}}">
                                                <input type="hidden" name="tipo[]" value="{{$camposCred->tipo}}">
                                                <input type="hidden" name="nome[]" class='custo1'
                                                        value="{{$tabela->nome}}">
                                                <input type="hidden" name="email[]" class='custo2'
                                                        value="{{$tabela->email}}">
                                                <input type="hidden" name="celular[]" class='custo3'
                                                        value="{{$tabela->celular}}">
                                                <input type="hidden" name="cpf[]" class='custo4'
                                                        value="{{$tabela->cpf}}">

                                                <div class="col-md-6 col-12">
                                                        <br>
                                                        <label for="valor_salvo[]">{{$camposCred->nome}}:</label>
                                                        @php
                                                        $strExemple = $camposCred->opcoes;
                                                        $opcoes = explode(';', $strExemple);
                                                        if($camposCred->obrigatorio == 1){
                                                        $camposCred->obrigatorio = 'required' ;
                                                        }elseif ($camposCred->obrigatorio == 0) {
                                                        $camposCred->obrigatorio = ' ' ;
                                                        }
                                                        @endphp

                                                        @switch($camposCred->tipo)
                                                        @case('text')
                                                        <input type="text" class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}}
                                                                maxlength="{{$camposCred->tamanho}}"
                                                                value="{{$valor2}}">

                                                        @break
                                                        @case('number')
                                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                                        @break
                                                        @case('cpf')
                                                        @if($camposCred->nome == 'Cpf')
                                                        <input type="text" class="form-control cpf valor"
                                                                name="valor_salvo[]" id="cpf{{$idcamp}}"
                                                                onblur='validarcpf{{$idcamp}}()'
                                                                placeholder="EX: 999.999.999-99"
                                                                {{$camposCred->obrigatorio}} custo='custo4'
                                                                value="{{$valor2}}">
                                                        @else
                                                        <input type="text" class="form-control cpf" name="valor_salvo[]"
                                                                id="cpf{{$idcamp}}" onblur=validarcpf{{$idcamp}}()
                                                                placeholder="EX: 999.999.999-99"
                                                                {{$camposCred->obrigatorio}} value="{{$valor2}}">
                                                        @endif
                                                        <div id="invalidocpf{{$idcamp}}" class="invalid-feedback">CPF
                                                                inválido!
                                                        </div>
                                                        <div id="existecpf{{$idcamp}}" class="invalid-feedback">CPF já
                                                                existente!
                                                        </div>
                                                        @break
                                                        @case('cnpj')
                                                        <input type="text" class="form-control cnpj"
                                                                name="valor_salvo[]" id="valor_salvo[]"
                                                                placeholder="EX: 00.000.000/0000-00"
                                                                {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                                        @break
                                                        @case('email')
                                                        <input type="email" class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                                                                {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                                        @break
                                                        @case('tel')
                                                        <input type="text" class="form-control phone"
                                                                name="valor_salvo[]" id="valor_salvo[]"
                                                                placeholder="EX: (99) 99999-9999"
                                                                {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                                        @break
                                                        @case('date')
                                                        <input type="text" class="form-control data_ data_calendario" name="valor_salvo[]"
                                                                id="valor_salvo[]" placeholder="__/__/____"
                                                                {{$camposCred->obrigatorio}} value="{{$valor2}}"
                                                                maxlength="10">

                                                        @break
                                                        @case('checkbox')
                                                        <br>
                                                        @php
                                                        $str = $camposCred->opcoes;
                                                        $opcoes2 = explode(';', $str);
                                                        $opcoes3 = explode(' / ', $valor2);

                                                        $resultado = array_intersect($opcoes2, $opcoes3);
                                                        $result = array_diff($opcoes2, $opcoes3);
                                                        @endphp

                                                        @foreach ($resultado as $resul)
                                                        <div class="form-check">

                                                                <input class="form-check-input" type="checkbox"
                                                                        name="checkbox[]" id="checkbox[]"
                                                                        aria-checked="true" value='{{$resul}}' checked
                                                                        {{$camposCred->obrigatorio}}>
                                                                <label class="form-check-label"
                                                                        for="checkbox[]">{{$resul}}</label>
                                                        </div>
                                                        @endforeach
                                                        @foreach ($result as $res)
                                                        <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                        name="checkbox[]" id="checkbox[]"
                                                                        value='{{$res}}' {{$camposCred->obrigatorio}}>
                                                                <label class="form-check-label"
                                                                        for="checkbox[]">{{$res}}</label>

                                                        </div>
                                                        @endforeach


                                                        @break
                                                        @case('select')
                                                        <select class="form-control" name="valor_salvo[]"
                                                                id="valor_salvo[]" {{$camposCred->obrigatorio}}>
                                                                @foreach ($opcoes as $op)
                                                                @if($valor2 == $op)
                                                                <option value='{{$op}}' selected>{{$op}}</option>
                                                                @endif
                                                                @if($valor2 != $op)
                                                                <option value='{{$op}}'>{{$op}}</option>
                                                                @endif
                                                                @endforeach
                                                        </select>

                                                        @break

                                                        @default
                                                        @endswitch

                                                </div>
                                                @endforeach

                                                <?php
                                                
                                                $contadorsala = count($salas);


                                                ?>
                                                @if(count($salas) > 0)

                                                <div class="col-md-6 col-12">
                                                        <br>
                                                        <label for="Palestras">Palestras: </label><br>
                                                        <div class="">
                                                                <input type="hidden" name="contador" id="contador"
                                                                        class="contador" value='{{$contadorsala}}'>
                                                                {{-- @foreach($salaresultado as $salaresults)
                                                        <input type="checkbox"  name="palestras[]"  value='{{$salaresults}}'>
                                                                {{$salaresults}}
                                                                @endforeach --}}

                                                                <?php
                                                                $strSalas       = $tabela->palestras;
                                                                $paletra        = explode(' / ', $strSalas);  

                                                                
                                                                $nomeSalas = Sala::selectRaw('GROUP_CONCAT(DISTINCT nome ORDER BY id ASC SEPARATOR ";") as nome')
                                                                                ->selectRaw('GROUP_CONCAT(DISTINCT id ORDER BY id ASC SEPARATOR ";") as id')
                                                                                ->selectRaw('GROUP_CONCAT(DISTINCT data_inicio ORDER BY id ASC SEPARATOR ";") as data_inicio')
                                                                                ->selectRaw('GROUP_CONCAT(DISTINCT hora_inicio ORDER BY id ASC SEPARATOR ";") as hora_inicio')
                                                                                ->selectRaw('GROUP_CONCAT(DISTINCT hora_fim ORDER BY id ASC SEPARATOR ";") as hora_fim')
                                                                                ->whereRaw('id_evento = ? and ativo = true', [$camposCred->id_evento])
                                                                                ->first();
                                                                
                                                                                // (visitantes < quantidade or quantidade = 0) and 

                                                                                
                                                                $strSalas       = explode(';', $nomeSalas->nome);
                                                                $strdata_inicio = explode(';', $nomeSalas->data_inicio);
                                                                $strhora_inicio = explode(';', $nomeSalas->hora_inicio);
                                                                $strhora_fim    = explode(';', $nomeSalas->hora_fim);

                                                                $iguaisSalas      = array_intersect($strSalas, $paletra);
                                                                $difSalas         = array_diff($strSalas, $paletra);
                                                                
                                                        ?>

                                                                @foreach($iguaisSalas as $key => $iguais)
                                                                <input type="checkbox" onclick="()" name="palestras[]"
                                                                        id="palestras{{$z}}"
                                                                        class="palestras{{$z}} valor {{ str_replace(' ','',$iguais).$z }}"
                                                                        value='{{$iguais}}' custo='custo5' checked>
                                                                <span style="font-size: 1.125rem;"> {{$iguais}}</span><br>
                                                                @endforeach
                                                                @foreach($difSalas as $dif)
                                                                <input type="checkbox" onclick="()" name="palestras[]"
                                                                        id="palestras{{$z}}"
                                                                        class="palestras{{$z}} valor {{ str_replace(' ','',$dif).$z }}"
                                                                        value='{{$dif}}' custo='custo5'><span
                                                                        style="font-size: 1.125rem;"> {{$dif}}</span><br>
                                                                @endforeach

                                                        </div>
                                                </div>

                                                @endif
                                        </div>

                                        <br>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Fechar</button>
                                                <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
</div>

<script>
        $(".palestras{{$z}}").click(function() {
            var nome    =   $(this).next().text();
            var id      =   $(this).attr('id');
            var z       =   id.replace(/\D+/g,'');
            var nome_   =   '.'.concat(nome);
            var nome_   =   nome_.replace(/ /g,'');
            var nome_   =  nome_.concat(z);
            var nomes;
            var i;
            console.log(nome);
            console.log(z);
            $.ajax({
                type: 'post',
                url: '/horas',
                data: {
                    nome: nome,
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.sucesso == 0) {
                        no = data.nome;                        
                        var nomes = no.split(",");
                        
                        if($(nome_).prop("checked") == true){
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  ' '.concat(nomes[i]);
                                if(nome != nomes2){
                                    //console.log(nome);
                                    //console.log(nome[i]);
                                    var check2 = nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(nomes[i]);
                                    var check2 =  check2.concat(z);
                                    console.log(check2);
                                    $(check2).attr("disabled", true); 
                                    $(check2).prop("checked", false);
                                    check2 = 0;                                            
                                }                   
                            }
                        }else{
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  ' '.concat(nomes[i]);
                                if(nome != nomes2){
                                    //console.log(nome);
                                    //console.log(nome[i]);
                                    var check2 = nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(nomes[i]);
                                    var check2 =  check2.concat(z);
                                    console.log(check2);
                                    $(check2).attr("disabled", false);                                     
                                    check2 = 0;                                        
                                }
                            }
                        }
                    }
                }
            });
           
        });
</script>


<!-- EXCLUIR visitente -->
<div class="modal fade" id="apagar{{$cod}}" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="TituloModalCentralizado">Excluir</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body" id='excluir'>
                                <div class="row" id="linha">
                                        <div class="col-9">
                                                <h5> Tem certeza que deseja excluir o {{$tabela->nome}} ? </h5>
                                        </div>
                                        <div class="col-3">
                                                <a id="apagar" class="btn btn-md btn-danger"
                                                        href="/apagarcaex/{{ base64_encode($tabela->cpf) }}"> <i
                                                                class="far fa-trash-alt"></i> Apagar </a>
                                        </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                </div>
        </div>
</div>


<script type="text/javascript">
        function validarcpf{{$idcamp}}() {

        if ($('#cpf{{$idcamp}}').val().length > 0) {
        $.ajax({
                type: 'get',
                url: '/cpfcaex',
                data: {
                id_evento: $('#id_evento').val(),
                cpf: $('#cpf{{$idcamp}}').val()
                },
                dataType: 'JSON',
                success: function(data) {
                if (data.sucesso == 1) {
                        //alert(data.mensagem);
                        $('#cpf{{$idcamp}}').addClass("is-invalid");
                        $("#cpf{{$idcamp}}").focus();
                        $('#invalidocpf{{$idcamp}}').hide();
                        $('#existecpf{{$idcamp}}').show();
                        $('#cpf{{$idcamp}}').val('');
                        //document.getElementById('cpf{{$idcamp}}').value = '';
                }
                if (data.sucesso == 2) {
                        //alert(data.mensagem);
                        $('#cpf{{$idcamp}}').addClass("is-invalid");
                        $("#cpf{{$idcamp}}").focus();
                        $('#existecpf{{$idcamp}}').hide();
                        $('#invalidocpf{{$idcamp}}').show();
                        $('#cpf{{$idcamp}}').val('');
                       // document.getElementById('cpf{{$idcamp}}').value = '';
                }
                if (data.sucesso == 0) {
                        $('#cpf{{$idcamp}}').removeClass("is-invalid");
                }
                }
        });
        }
}
</script>
<?php $z++ ?>
@endforeach
<!-- fim foreach items as tabelas -->

@endsection

@section('javascript')
<script type="text/javascript">
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
    });
    
        setTimeout(function() {
        $("#alert").slideUp('slow');
    }, 5000);
    
        $(document).ready(function() {
                $(".valor").on("input", function() {
                        var textoDigitado = $(this).val();
                        var inputCusto = $(this).attr("custo");
                        $("." + inputCusto).val(textoDigitado);
                });
        });
/*
        function validaremail() {
                if ($('#email').val().length > 0) {
                        $.ajax({
                                type: 'get',
                                url:   '/emailcaex',
                                data: {
                                        id_evento:      $('#id_evento').val(),
                                        email:          $('#email').val()
                                },                        
                                dataType: 'JSON',
                                success: function(data) {
                                        if (data.sucesso == 1) {
                                                //alert(data.mensagem);
                                                $('#email').addClass("is-invalid");
                                                $("#email").focus();
                                                document.getElementById('email').value = '';
                                        }else{
                                                $('#email').removeClass("is-invalid");
                                        }
                                }
                        });
                }
        }

*/
$(".palestras").click(function() {
            var nome    =   $(this).val();
            var nome_   =   '.'.concat(nome);
            var nome_   =   nome_.replace(/ /g,'');
            var nomes;
            var i;
            console.log(nome);
            $.ajax({
                type: 'post',
                url: '/horas',
                data: {
                    nome: nome,
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.sucesso == 0) {
                        no = data.nome;                        
                        var nomes = no.split(",");
                        
                        if($(nome_).prop("checked") == true){
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  nomes[i];
                                if(nome != nomes2){
                                    console.log(nome);
                                    console.log(nome[i]);
                                    var check2 = nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(check2);
                                    console.log(check2);
                                    $(check2).attr("disabled", true); 
                                    $(check2).prop("checked", false);                                            
                                }                   
                            }
                        }else{
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  ' '.concat(nomes[i]);
                                if(nome != nomes2){
                                    console.log(nome);
                                    console.log(nome[i]);
                                    var check2 =  nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(check2);
                                    console.log(check2);
                                    $(check2).attr("disabled", false); 
                                    //$(check2).prop("checked", true);                                            
                                }
                            }
                        }
                    }
                }
            });
           
        });
        
function validarcpf2() {
        if ($('#cpf').val().length > 0) {
                $.ajax({
                        type: 'get',
                        url: '/cpfcaex',
                        data: {
                                id_evento: $('#id_evento').val(),
                                cpf: $('#cpf').val()
                        },
                        dataType: 'JSON',
                        success: function(data) {
                                if (data.sucesso == 1) {
                                        //alert(data.mensagem);
                                        $('#cpf').addClass("is-invalid");
                                        $("#cpf").focus();
                                        $('#invalidocpf').hide();
                                        $('#existecpf').show();
                                        document.getElementById('cpf').value = '';
                                }
                                if (data.sucesso == 2) {
                                        //alert(data.mensagem);
                                        $('#cpf').addClass("is-invalid");
                                        $("#cpf").focus();
                                        $('#existecpf').hide();
                                        $('#invalidocpf').show();
                                        document.getElementById('cpf').value = '';
                                }
                                if (data.sucesso == 0) {
                                        $('#cpf').removeClass("is-invalid");
                                }
                        }
                });
        }
}



        //buscar 
        $(document).ready(function() {
                $("#procurar").keyup(function() {
                $('#retornar').html('');
                if ($('#procurar').val().length > 0) {
                        $.ajax({
                                url: '/procurarcaex',
                                method: 'get',
                                data: {
                                        id:        $('#id_evento_procurar').val(),
                                        nome:      $('#procurar').val()
                                }, 
                                success: function(data) {
                                        $('#retornar').html(data);
                                }
                        });
                }
                });
        });
</script>

@endsection