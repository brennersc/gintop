@extends('layout.app', ["current" => "evento"])
@php
use Illuminate\Support\Facades\DB;
@endphp
@section('body')
<style>
    table {
        text-align: center;
    }
</style>
@if(session('mensagem'))
<div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
    style="box-shadow: 0px 0px 20px #A4A4A4;">
    Evento {{ session('mensagem') }} com Sucesso!!!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if(session('cred'))
<div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
    style="box-shadow: 0px 0px 20px #A4A4A4;">
    {{ session('cred') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="card border">
    {{--------------- Busca --------------}}
    <div class="card-header">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <h3>Cadastro de eventos</h3>
            </div>
            <div class="col-md-5 col-sm-12">
                <form class="form-control-sm" id="form-procurarevento" method="POST" role="search"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="input-group input-group-sm">
                        <input type="hidden">
                        <input name="busca" id="procurar" class="form-control" placeholder="Busque pelo Evento...">
                        <div class="input-group-prepend ">
                            <div class="input-group-text"><i class="fas fa-search"></i></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--------------- Busca --------------}}
    <div class="card-body table-responsive">
        <div id="retornar" class="procurar"></div>
        @if(count($evento) > 0)
        <div class="table-responsive">
            <table class="table table-ordered table-bordered">
                <thead class=table-active>
                    <tr style="font-size: 17px;">
                        {{-- <th>Código</th> --}}
                        <th>Nome</th>
                        <th>Ações</th>
                        <th>Campos</th>
                        <th>Salas</th>
                        <th>Ingressos</th>
                        <th>Visitantes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evento as $even)
                    <tr>
                        {{-- <td>{{$even->id}}</td> --}}
                        <td>{{$even->nome}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="/editar/{{$even->id}}" class="btn btn-sm btn-primary" title="Editar"><i
                                        class="far fa-edit" ></i> Editar</a>
                                <button class="btn btn-sm btn-danger" role="button" data-toggle="modal"
                                    data-target="#modalremover{{$even->id}}" title="Apagar"><i class="far fa-trash-alt"></i> Apagar
                                </button>
                                <a href="/info/{{$even->id}}" class="btn btn-sm btn-success" title="Informações"><i
                                        class="fas fa-info-circle" ></i> Informações</a>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-sm btn-outline-secondary botaocred" data-toggle="modal"
                                    data-target="#dlgEmpresa{{$even->id}}" title="Credenciamento"><i class="fas fa-id-badge"></i>
                                    Credenciamento</button>
                                <button class="btn btn-sm btn-outline-secondary botaocaex" data-toggle="modal"
                                    data-target="#dlgCAEX{{$even->id}}caex" title="Caex"><i class="fas fa-address-card"></i>
                                    Caex</button>
                            </div>
                        </td>
                        <td>
                            @if($even->sala == 'sim')
                            <button href="/editar/{{$even->id}}" class="btn btn-sm  btn-outline-primary botaosala"
                                data-toggle="modal" data-target="#SALA{{$even->id}}" title="Salas"><i class="fas fa-door-open"></i>
                                Salas</button>
                            @else
                            <button class="btn btn-sm  btn-outline-primary disabled" title="Salas"><i class="fas fa-door-closed"
                                    aria-disabled="true" disabled></i> Salas</button>
                            @endif
                        </td>
                        <td>
                            @if(($even->ingresso == 'sim') or ($even->mesa == 'sim'))
                                <a href="/ingresso/{{$even->id}}" class="btn btn-sm btn-outline-primary" title="Ingressos"><i class="fas fa-ticket-alt"></i> Ingresso</a>
                            @else
                                <button class="btn btn-sm  btn-outline-primary disabled" title="Ingressos" disabled><i class="fas fa-ticket-alt"></i> Ingresso</button>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="/visitantes/{{$even->id}}" class="btn btn-sm btn-outline-success" title="Credenciamento"><i
                                        class="fas fa-list"></i> Credenciamento</a>
                                <a href="/caexs/{{$even->id}}" class="btn btn-sm btn-outline-success" title="CAEX"><i
                                        class="fas fa-list"></i> CAEX</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        Nenhum evento cadastrado
        @endif
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-5">
                <a href="/evento/novo" class="btn btn-sm btn-primary" role="button">Novo Evento</a>
            </div>
            <div class="col-7">
                {{ $evento->links() }}
            </div>
        </div>
    </div>
</div>


@if(count($evento) > 0)
@foreach($evento as $even)


<!-- *************************** MODAL SALAS ***********************************************-->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="SALA{{$even->id}}"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            @php


            //$creds = DB::table('credenciamentos')->select('id')->where('id_evento', $even->id)->get();

            $salas = DB::table('salas')
            ->select('nome','id')
            ->where('id_evento', $even->id)
            ->where('ativo', true)
            ->get();

            @endphp


            {{-- @if(count($creds) == 0) --}}
            <form class="form-horizontal" id="formSALA{{$even->id}}" method="GET">
                <div class="modal-header">
                    <h4 class="modal-title"><b><i class="fas fa-door-open"></i> SALAS para
                            {{$even->nome}}</b>
                    </h4>
                </div>
                <div class="alert alert-warning" role="alert">
                    <h5>
                        <strong>Salas cadastradas: </strong>
                        <span id="novassalas{{$even->id}}">
                            @foreach($salas as $sal)
                            <a href="sala/{{$sal->id}}">{{$sal->nome}}</a>,
                            @endforeach
                        </span>
                    </h5>
                </div>
                <hr>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                id="error{{$even->id}}" style="display:none">
                                <strong><i class="fas fa-times"></i> Erro!</strong> Preencha todos os campos
                                obrigatórios!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                id="errorexiste{{$even->id}}" style="display:none">
                                <strong><i class="fas fa-times"></i> Erro!</strong> Sala já Existe!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                id="alert{{$even->id}}" style="display:none">
                                <strong><i class="fas fa-check"></i> Sucesso!</strong> Sala <span id="salanova"></span>
                                salva com sucesso!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="id_evento{{$even->id}}" id="id_evento{{$even->id}}"
                        value="{{$even->id}}" name="id_evento" class="form-control">

                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="salanome" class="control-label">Nome: *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{old('nome')}}"
                                    id="nomesala{{$even->id}}" name="salanome{{$even->id}}">
                                <div id="salanome" class="invalid-feedback">Campo
                                    Obigatorio!</div>
                                <div id="salanome" class="invalid-feedback exis">Campo já
                                    existente!</div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="salalocal" class="control-label">Local: *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{old('nome')}}"
                                    id="salalocal{{$even->id}}" name="salalocal{{$even->id}}">
                                <div id="salalocal" class="invalid-feedback">Campo Obigatorio!</div>                                
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="salapalestrante" class="control-label">Palestrante: *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{old('nome')}}"
                                    id="salapalestrante{{$even->id}}" name="salapalestrante{{$even->id}}">
                                <div id="salapalestrante" class="invalid-feedback campo">Campo Obigatorio!
                                </div>                                
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="qntdsala" class="control-label">Quantidade de inscrições: </label>
                            <div class="input-group">
                                <input type="number" class="form-control" value="{{old('nome')}}"
                                    id="qntdsala{{$even->id}}" name="qntdsala{{$even->id}}">                                                             
                            </div>
                            <small class="text-muted">Caso não a limitações deixe-o em branco</small>
                        </div>

                        <div class="col-12 col-md-3 form-group">
                            <label for="data_ini">Data de Início: *</label>
                            <input type="text" class="form-control data_ data_calendario" id="data_inicio{{$even->id}}"
                                name="data_inicio{{$even->id}}" value="{{old('data_inicio')}}" id="data_ini"
                                placeholder="__/__/____">
                        </div>
                        <div class="col-12 col-md-3 form-group">
                            <label for="data_fim">Data de Encerramento: *</label>
                            <input type="text" class="form-control data_ data_calendario" id="data_fim{{$even->id}}"
                                name="data_fim{{$even->id}}" value="{{old('data_fim')}}" id="data_fim"
                                placeholder="__/__/____">
                        </div>

                        <div class="col-12 col-md-3 form-group">
                            <label for="hora_inicio">Horário de Início: *</label>
                            <input type="time" class="form-control " id="hora_inicio{{$even->id}}"
                                name="hora_inicio{{$even->id}}" value="{{old('hora_inicio')}}" id="hora_inicio"
                                placeholder="Horário de Início">
                        </div>
                        <div class="col-12 col-md-3 form-group">
                            <label for="hora_fim">Horário de Encerramento: *</label>
                            <input type="time" class="form-control " id="hora_fim{{$even->id}}"
                                name="hora_fim{{$even->id}}" value="{{old('hora_fim')}}" id="hora_fim"
                                placeholder="Horário de Encerramento">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="buttonempresa" class="btn btn-success">Salvar</button>
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
    </div>
</div>
</div>

<!-- *************************** MODAL CREDENCIAMENTO ***********************************************-->

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="dlgEmpresa{{$even->id}}"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            @php


            //$creds = DB::table('credenciamentos')->select('id')->where('id_evento', $even->id)->get();

            $credscampos = DB::table('campo_creds')
            ->select('nome')
            ->where('id_evento', $even->id)
            ->where('nome','<>', 'nome')
                ->where('nome','<>', 'celular')
                    ->where('nome','<>', 'email')
                        ->where('nome','<>', 'cpf')
                            ->get();

                            @endphp
                            {{-- @if(count($creds) == 0) --}}
                            <form class="form-horizontal" id="formempresa{{$even->id}}" method="GET">
                                <div class="modal-header">
                                    <h4 class="modal-title"><b><i class="fas fa-id-badge"></i> Campos Credenciamento
                                            {{$even->nome}}</b>
                                    </h4>
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <h5>
                                        <strong>Atenção</strong> campos <b>Nome</b>, <b>Email</b>, <b>Celular</b> e
                                        <b>CPF</b>, já são
                                        pré-definido pelo
                                        sistema!<br><br>
                                        <strong>Campos cadastrados: </strong>
                                        <span id="novoscampos{{$even->id}}">
                                            @foreach($credscampos as $campo)
                                            {{$campo->nome}},
                                            @endforeach
                                        </span>
                                    </h5>
                                </div>
                                <hr>
                                <div class="modal-body">
                                    <input type="hidden" class="id_evento{{$even->id}}" id="id_evento{{$even->id}}"
                                        value="{{$even->id}}" name="id_evento" class="form-control">
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="nome" class="control-label">Nome do Campo *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{old('nome')}}"
                                                    id="nome{{$even->id}}" name="nome">
                                                <div id="campo{{$even->id}}" class="invalid-feedback campo">Campo
                                                    Obigatorio!</div>
                                                <div id="exis{{$even->id}}" class="invalid-feedback exis">Campo já
                                                    existente!</div>
                                            </div>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            {{-- <label class="control-label">Opções</label> --}}
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="obrigatorio"
                                                    id="obrigatorio{{$even->id}}" value='1'>
                                                <label for="obrigatorio{{$even->id}}" class="form-check-label">Campo
                                                    obrigatório?</label>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="unico"
                                                    id="unico{{$even->id}}" value='1'>
                                                <label for="unico{{$even->id}}" class="form-check-label">Campo deve ter
                                                    valor
                                                    único?</label>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="cracha"
                                                    id="cracha{{$even->id}}" value='1'>
                                                <label for="cracha{{$even->id}}" class="form-check-label">Campo deve ter
                                                    valor
                                                    impressona etiqueta?</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="tipo">Tipo *</label>
                                            <select class="form-control tipo{{$even->id}}" name="tipo"
                                                id="tipo{{$even->id}}">
                                                <option value="" selected></option>
                                                <option value="cpf">CPF</option>
                                                <option value="cnpj">CNPJ</option>
                                                <option value="email">Email</option>
                                                <option value="number">Número</option>
                                                <option value="tel">Telefone</option>
                                                <option value="date">Data</option>
                                                <option class="text{{$even->id}}" value="text">Texto</option>
                                                <option class="valor{{$even->id}}" value="select">Dropdown</option>
                                                <option class="valor2{{$even->id}}" value="checkbox">Múltiplos Checkbox
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6 valores{{$even->id}}"
                                            style="display: none">
                                            <label for="opcoes" class="control-label">Valores ( separa por ponto e
                                                virgula ;
                                                )</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="opcoes"
                                                    id="opcoes{{$even->id}}" placeholder="EX: 1;2;3;4;5">
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 tamanho{{$even->id}}"
                                            style="display: none">
                                            <label for="tamanho" class="control-label ">Tamanho máximo (200
                                                caracteres)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="tamanho"
                                                    id="tamanho{{$even->id}}" placeholder=" ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" id="buttonempresa" class="btn btn-success">Salvar</button>
                                    <button type="cancel" class="btn btn-secondary"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                            {{-- @else
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fas fa-id-badge"></i> Campos Credenciamento
                                {{$even->nome}}</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning" role="alert">
                Não é possivel cadastrar mais campos ao formulário, pois, já existem cadastros de
                visitantes!
            </div>
        </div>
        <div class="modal-footer">
            <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
        @endif --}}
    </div>
</div>
</div>

<!-- *************************** MODAL CAEX ***********************************************-->

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="dlgCAEX{{$even->id}}caex"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            @php
            $caexs = DB::table('caexes')->select('id')->where('id_evento', $even->id)->get();

            $caexscampos = DB::table('campo_caexes')
            ->select('nome')
            ->where('id_evento', $even->id)
            ->where('nome','<>', 'nome')
                ->where('nome','<>', 'celular')
                    ->where('nome','<>', 'email')
                        ->where('nome','<>', 'cpf')
                            ->get();

                            @endphp
                           
                            <form class="form-horizontal" id="formCAEX{{$even->id}}caex" method="GET">
                                <div class="modal-header">
                                    <h4 class="modal-title"><b><i class="fas fa-address-card"></i> Campos CAEX
                                            {{$even->nome}}</b></h4>
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <h5><strong>Atenção</strong> campos <b>Nome</b>, <b>Email</b>, <b>Celular</b> e
                                        <b>CPF</b>, já são
                                        pré-definido pelo
                                        sistema!<br><br>
                                        <strong>Campos cadastrados: </strong>
                                        <span id="novoscampos{{$even->id}}caex">
                                            @foreach($caexscampos as $campo)
                                            {{$campo->nome}},
                                            @endforeach
                                        </span>
                                    </h5>
                                </div>
                                <hr>
                                <div class="modal-body">
                                    <input type="hidden" id="id_evento{{$even->id}}caex" value="{{$even->id}}"
                                        name="id_evento" class="form-control">
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="nome" class="control-label">Nome do Campo *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control nome" id="nome{{$even->id}}caex"
                                                    name="nome">
                                                <div id="campo{{$even->id}}caex" class="invalid-feedback campo">Campo
                                                    Obigatorio!</div>
                                                <div id="exis{{$even->id}}caex" class="invalid-feedback exis">Campo já
                                                    existente!</div>
                                            </div>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            <label for=" " class="control-label">Opções</label>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="obrigatorio"
                                                    id="obrigatorio{{$even->id}}caex" value="1">
                                                <label class="form-check-label" for="inlineCheckbox1">Campo
                                                    obrigatório?</label>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="unico"
                                                    id="unico{{$even->id}}caex" value="1">
                                                <label class="form-check-label" for="inlineCheckbox2">Campo deve ter
                                                    valor
                                                    único?</label>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="cracha"
                                                    id="cracha{{$even->id}}caex" value="1">
                                                <label class="form-check-label" for="inlineCheckbox3">Campo deve ter
                                                    valor
                                                    impressona etiqueta?</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="tipo">Tipo *</label>
                                            <select class="form-control" name="tipo" id="tipo{{$even->id}}caex">
                                                <option value="" selected></option>
                                                <option value="cpf">CPF</option>
                                                <option value="cnpj">CNPJ</option>
                                                <option value="email">Email</option>
                                                <option value="number">Número</option>
                                                <option value="tel">Telefone</option>
                                                <option value="date">Data</option>
                                                <option class="text{{$even->id}}caex" value="text">Texto</option>
                                                <option class="valor{{$even->id}}caex" value="select">Dropdown</option>
                                                <option class="valor2{{$even->id}}caex" value="checkbox">Múltiplos
                                                    Checkbox
                                                </option>
                                            </select>
                                            <div id="campotipo{{$even->id}}caex" class="invalid-feedback campo">Campo
                                                Obigatorio!</div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 valores{{$even->id}}caex"
                                            style="display: none">
                                            <label for="opcoes" class="control-label">Valores ( separa por ponto e
                                                virgula
                                                ;)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="opcoes"
                                                    id="opcoes{{$even->id}}caex" placeholder="EX: 1;2;3;4;5">
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 tamanho{{$even->id}}caex"
                                            style="display: none">
                                            <label for="tamanho" class="control-label ">Tamanho máximo (200
                                                caracteres)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="tamanho"
                                                    id="tamanho{{$even->id}}caex" placeholder=" ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Salvar</button>
                                    <button type="cancel" class="btn btn-secondary"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                            
        </div>
    </div>
</div>
{{-- ---------------------------- REMOVER ------------------------------------------- --}}
<div class="modal fade" id="modalremover{{$even->id}}" tabindex="-1" role="dialog"
    aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalCentralizado">Excluir </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='excluir'>
                <div class="row" id="linha">
                    <div class="col-9">
                        <strong> Tem certeza que deseja excluir o evento: {{$even->nome}}?</strong>
                    </div>
                    <div class="col-3">
                        <a href="/evento/apagar/{{$even->id}}" class="btn btn-sm btn-danger"><i
                                class="far fa-trash-alt"></i> Apagar</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@endforeach
@endif

@endsection

@if(count($evento) > 0)

@section('javascript')
@foreach($evento as $even)

<script type="text/javascript">
    $(document).ready(function() {

    $("select").change(function() {
        if ($('.text{{$even->id}}').prop("selected") == true) {
            $('.tamanho{{$even->id}}').fadeIn("show");
        } else {
            $('.tamanho{{$even->id}}').hide();
        }
        if (($('.valor{{$even->id}}').prop("selected") == true) || ($('.valor2{{$even->id}}').prop("selected") == true)) {
            $('.valores{{$even->id}}').fadeIn("show");
        } else {
            $('.valores{{$even->id}}').hide();
        }
    });

    $("select").change(function() {
        if ($('.text{{$even->id}}caex').prop("selected") == true) {
            $('.tamanho{{$even->id}}caex').fadeIn("show");
        } else {
            $('.tamanho{{$even->id}}caex').hide();
        }
        if (($('.valor{{$even->id}}caex').prop("selected") == true) || ($('.valor2{{$even->id}}caex').prop("selected") == true)) {
            $('.valores{{$even->id}}caex').fadeIn("show");
        } else {
            $('.valores{{$even->id}}caex').hide();
        }
    });

// GRAVAR SALAS
$("#formSALA{{$even->id}}").on("submit", function() {
        console.clear();
        $.ajax({
            type: 'GET',
            url: 'sala',
            data: {
                id_evento:      $('#id_evento{{$even->id}}').val(),
                nome:           $('#nomesala{{$even->id}}').val(),
                local:          $('#salalocal{{$even->id}}').val(),
                palestrante:    $('#salapalestrante{{$even->id}}').val(),
                quantidade:     $('#qntdsala{{$even->id}}').val(),
                data_inicio:    $('#data_inicio{{$even->id}}').val(),
                data_fim:       $('#data_fim{{$even->id}}').val(),
                hora_inicio:    $('#hora_inicio{{$even->id}}').val(),
                hora_fim:       $('#hora_fim{{$even->id}}').val()                
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.sucesso == 2) {
                    //alert('vazio');
                    $('#errorexiste{{$even->id}}').show();

                    setTimeout(function() {
                        $('#errorexiste{{$even->id}}').hide();
                    }, 4000);
                }
                if (data.sucesso == 0) {
                    //alert('vazio');
                    $('#error{{$even->id}}').show();

                    setTimeout(function() {
                        $('#error{{$even->id}}').hide();
                    }, 4000);
                }
                if (data.sucesso == 1) {

                    var acao = $("#novassalas{{$even->id}}").text();

                    //alert('salvo');
                    $('#alert{{$even->id}}').show();

                    $(".div{{$even->id}}").show();
                    $("#novassalas{{$even->id}}").text(acao + ' ' + data.nome + ', ');
                    $("#novassalas{{$even->id}}").css({
                        'color': '#38c172'
                    });

                    setTimeout(function() {
                        $("#nomesala{{$even->id}}").removeClass("is-valid")
                    }, 2000);

                    setTimeout(function() {
                        $("#novassalas{{$even->id}}").css({
                            'color': '#857b26'
                            });
                    }, 2000);

                    $('#nomesala{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#salalocal{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#salapalestrante{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#qntdsala{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#data_inicio{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#data_fim{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#hora_inicio{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $('#hora_fim{{$even->id}}').addClass("is-valid").removeClass("is-invalid");

                    setTimeout(function() {
                        $("#nomesala{{$even->id}}").removeClass("is-valid");
                        $('#nomesala{{$even->id}}').removeClass("is-valid");
                        $('#salalocal{{$even->id}}').removeClass("is-valid");
                        $('#salapalestrante{{$even->id}}').removeClass("is-valid");
                        $('#qntdsala{{$even->id}}').removeClass("is-valid");
                        $('#data_inicio{{$even->id}}').removeClass("is-valid");
                        $('#data_fim{{$even->id}}').removeClass("is-valid");
                        $('#hora_inicio{{$even->id}}').removeClass("is-valid");
                        $('#hora_fim{{$even->id}}').removeClass("is-valid");
                        $('#alert{{$even->id}}').hide();
                    }, 2000);

                    $('#nomesala{{$even->id}}').val('');
                    $('#salalocal{{$even->id}}').val('');
                    $('#salapalestrante{{$even->id}}').val('');
                    $('#qntdsala{{$even->id}}').val('');
                    $('#data_inicio{{$even->id}}').val('');
                    $('#data_fim{{$even->id}}').val('');
                    $('#hora_inicio{{$even->id}}').val('');
                    $('#hora_fim{{$even->id}}').val('');
                }
            }
        });
        return false;
    });


// GRAVAR CAMPO E VERIFICAR CREDENCIAMENTO
    $("#formempresa{{$even->id}}").on("submit", function() {
        console.clear();
        $.ajax({
            type: 'GET',
            url: 'cred',
            data: {
                id_evento:      $('#id_evento{{$even->id}}').val(),
                nome:           $('#nome{{$even->id}}').val(),
                tipo:           $('#tipo{{$even->id}}').val(),
                obrigatorio:    $('#obrigatorio{{$even->id}}:checked').val(),
                unico:          $('#unico{{$even->id}}:checked').val(),
                cracha:         $('#cracha{{$even->id}}:checked').val(),
                tamanho:        $('#tamanho{{$even->id}}').val(),
                opcoes:         $('#opcoes{{$even->id}}').val()
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.sucesso == 0) {
                    //alert('vazio');
                    $('#campo{{$even->id}}').show();
                    $('#exis{{$even->id}}').hide();
                    $('#nome{{$even->id}}').addClass("is-invalid");
                    $("#nome{{$even->id}}").focus();
                }
                if (data.sucesso == 3) {
                    //alert('vazio');
                    // $('#campo{{$even->id}}').show();
                    // $('#exis{{$even->id}}').hide();
                    $('#tipo{{$even->id}}').addClass("is-invalid");
                    $('#campotipo{{$even->id}}').show();
                    $("#tipo{{$even->id}}").focus();
                    setTimeout(function() {
                        $('#tipo{{$even->id}}').removeClass("is-invalid")
                    }, 3000);
                }
                if (data.sucesso == 1) {
                    //alert('igual');
                    $('#campo{{$even->id}}').hide();
                    $('#exis{{$even->id}}').show();
                    $('#nome{{$even->id}}').addClass("is-invalid");
                    $("#nome{{$even->id}}").focus();
                    $('#campo').hide();
                }
                if (data.sucesso == 2) {

                    var acao = $("#novoscampos{{$even->id}}").text();

                    //alert('salvo');
                    $('#campo{{$even->id}}').hide();
                    $('#exis{{$even->id}}').hide();
                    $('#nome{{$even->id}}').addClass("is-valid").removeClass("is-invalid");
                    $("#nome{{$even->id}}").focus();

                    $(".div{{$even->id}}").show();
                    $("#novoscampos{{$even->id}}").text(acao + ' ' + data.nome + ', ');
                    $("#novoscampos{{$even->id}}").css({
                        'color': '#38c172'
                    });

                    setTimeout(function() {
                        $("#nome{{$even->id}}").removeClass("is-valid")
                    }, 2000);

                    setTimeout(function() {
                        $("#novoscampos{{$even->id}}").css({
                            'color': '#857b26'
                            });
                    }, 2000);

                    $('#nome{{$even->id}}').val('');
                    $('#tipo{{$even->id}}').val('');
                    $('#obrigatorio{{$even->id}}').val('').attr('checked', false).prop('checked', false);
                    $('#unico{{$even->id}}').val('').attr('checked', false).prop('checked', false);
                    $('#cracha{{$even->id}}').val('').attr('checked', false).prop('checked', false);
                    $('#tamanho{{$even->id}}').val('');
                    $('#opcoes{{$even->id}}').val('');
                }
            }
        });
        return false;
    });

    // GRAVAR CAMPO E VERIFICAR CAEX
    $("#formCAEX{{$even->id}}caex").on("submit", function() {
        console.clear();
        $.ajax({
            type: 'GET',
            url: 'caex',
            data: {
                id_evento:      $('#id_evento{{$even->id}}caex').val(),
                nome:           $('#nome{{$even->id}}caex').val(),
                tipo:           $('#tipo{{$even->id}}caex').val(),
                obrigatorio:    $('#obrigatorio{{$even->id}}caex:checked').val(),
                unico:          $('#unico{{$even->id}}caex:checked').val(),
                cracha:         $('#cracha{{$even->id}}caex:checked').val(),
                tamanho:        $('#tamanho{{$even->id}}caex').val(),
                opcoes:         $('#opcoes{{$even->id}}caex').val()
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.sucesso == 0) {
                    //alert('vazio');
                    $('#campo{{$even->id}}caex').show();
                    $('#exis{{$even->id}}caex').hide();
                    $('#nome{{$even->id}}caex').addClass("is-invalid");
                    $("#nome{{$even->id}}caex").focus();
                }
                if (data.sucesso == 3) {
                    //alert('vazio');
                    // $('#campo{{$even->id}}').show();
                    // $('#exis{{$even->id}}').hide();
                    $('#tipo{{$even->id}}caex').addClass("is-invalid");
                    $("#tipo{{$even->id}}caex").focus();
                    setTimeout(function() {
                        $("#tipo{{$even->id}}caex").removeClass("is-invalid")
                    }, 3000);
                }
                if (data.sucesso == 1) {
                    //alert('igual');
                    $('#campo{{$even->id}}caex').hide();
                    $('#exis{{$even->id}}caex').show();
                    $('#nome{{$even->id}}caex').addClass("is-invalid");
                    $("#nome{{$even->id}}caex").focus();
                    $('#campo').hide();
                }
                if (data.sucesso == 2) {
                    var acao = $("#novoscampos{{$even->id}}caex").text();

                    //alert('salvo');
                    $('#campo{{$even->id}}caex').hide();
                    $('#exis{{$even->id}}caex').hide();
                    $('#nome{{$even->id}}caex').addClass("is-valid").removeClass("is-invalid");
                    $("#nome{{$even->id}}caex").focus();

                    $(".div{{$even->id}}caex").show();
                    $("#novoscampos{{$even->id}}caex").text(acao + ' ' + data.nome + ', ');
                    $("#novoscampos{{$even->id}}caex").css({
                        'color': '#38c172'
                    });

                    setTimeout(function() {
                        $("#nome{{$even->id}}caex").removeClass("is-valid")
                    }, 2000);

                    setTimeout(function() {
                        $("#novoscampos{{$even->id}}caex").css({
                            'color': '#857b26'
                            });
                    }, 2000);

                    $('#nome{{$even->id}}caex').val('');
                    $('#tipo{{$even->id}}caex').val('');
                    $('#obrigatorio{{$even->id}}caex').val('').attr('checked', false).prop('checked', false);
                    $('#unico{{$even->id}}caex').val('').attr('checked', false).prop('checked', false);
                    $('#cracha{{$even->id}}caex').val('').attr('checked', false).prop('checked', false);
                    $('#tamanho{{$even->id}}caex').val('');
                    $('#opcoes{{$even->id}}caex').val('');
                }
            }
        });
        return false;
    });

    $('.botaocred').on('click', function() {
        console.clear();
        $('#nome{{$even->id}}').val('');
        $('#tipo{{$even->id}}').val('');
        $('#obrigatorio{{$even->id}}').val('').attr('checked', false).prop('checked', false);
        $('#unico{{$even->id}}').val('').attr('checked', false).prop('checked', false);
        $('#cracha{{$even->id}}').val('').attr('checked', false).prop('checked', false);
        $('#tamanho{{$even->id}}').val('');
        $('#opcoes{{$even->id}}').val('');
    });

    $('.botaocaex').on('click', function() {
        console.clear();
        $('#nome{{$even->id}}caex').val('');
        $('#tipo{{$even->id}}caex').val('');
        $('#obrigatorio{{$even->id}}caex').val('').attr('checked', false).prop('checked', false);
        $('#unico{{$even->id}}caex').val('').attr('checked', false).prop('checked', false);
        $('#cracha{{$even->id}}caex').val('').attr('checked', false).prop('checked', false);
        $('#tamanho{{$even->id}}caex').val('');
        $('#opcoes{{$even->id}}caex').val('');
    });

    $('.botaosala').on('click', function() {
        console.clear();
        $('#nomesala{{$even->id}}').val('');
        $('#salalocal{{$even->id}}').val('');
        $('#salapalestrante{{$even->id}}').val('');
        $('#qntdsala{{$even->id}}').val('');
        $('#data_inicio{{$even->id}}').val('');
        $('#data_fim{{$even->id}}').val('');
        $('#hora_inicio{{$even->id}}').val('');
        $('#hora_fim{{$even->id}}').val('');
    });

    //Buscar
    $(document).ready(function() {
            $("#procurar").keyup(function() {
            $('#retornar').html('');
            if ($('#procurar').val().length > 0) {
                    $.ajax({
                            url: '/procurarevento',
                            method: 'get',
                            data: {
                                    nome:      $('#procurar').val()
                            }, 
                            success: function(data) {
                                    $('#retornar').html(data);
                            }
                    });
            }
            });
    });
    
    setTimeout(function() {
        $("#alert").slideUp('slow');
    }, 5000);

});
</script>
@endforeach
@endsection
@endif