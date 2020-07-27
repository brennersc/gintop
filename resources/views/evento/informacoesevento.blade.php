<?php

use App\Campo_cred;
use App\Campo_caex;
use App\Empresa;
use App\Sala;
use App\Ingresso;

//$cred = Campo_cred::all();
//$caex = Campo_caex::all();

$cred = Campo_cred::select('*')
        ->where('id_evento', '=', $exibir->id)
        ->get();

$caex = Campo_caex::select('*')
        ->where('id_evento', '=', $exibir->id)
        ->get();

$empresa = DB::table('empresas')
        ->select('nome_fantasia as nome')
        ->where('id', '=', $exibir->id_empresa)
        ->groupBy('nome')
        ->first();

$sala = Sala::select('*')
        ->where('id_evento', '=', $exibir->id)
        ->where('ativo', true)
        ->get();

$ingressos  = Ingresso::where('id_evento', $exibir->id)->get();

$data_inicio = date("d/m/Y", strtotime($exibir->data_inicio));
$data_fim = date("d/m/Y", strtotime($exibir->data_fim));

?>
@extends('layout.app', ["current" => "evento"])
<link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
@section('body')
<style>
        .note-editor.note-frame {
                border: none !important;
                pointer-events: none !important;
        }

        textarea {
                resize: none;
                cursor: none;
        }

        @media(max-width: 900px) {
                #banner {
                        height: 170px;
                        width: 300px;
                }
        }
</style>
@if(session('erro'))
<div id='alert' class="alert alert-danger alert-dismissible fade show" role="alert"
        style="box-shadow: 0px 0px 20px #A4A4A4;">
        Nome do campo já existe, coloque outro nome!!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
</div>
@endif
<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-md-9 col-sm-12">
                                <h3><i class="fas fa-info-circle"></i> Informações cadastradas Evento -
                                        <b>{{$exibir->nome}}</b></h3>
                        </div>
                        <div class="col-md-3 col-sm-12">
                                <a href="/credenciamento/{{$exibir->slug}}" class="btn btn-md btn-outline-primary "><i
                                                class="fas fa-link"></i> Credenciamento</a>
                                <a href="/caex/{{$exibir->slug}}" class="btn btn-md btn-outline-success"><i
                                                class="fas fa-link"></i> CAEX</a>
                        </div>
                </div>
        </div>
        <div class="card-body">
                <div class="row">
                        <div class="col-md-6 col-12">
                                <h5><b>Empresa:</b> {{$empresa->nome}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>link:</b> {{$exibir->slug}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Data Inicial:</b> {{ $data_inicio }}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Data Final:</b> {{$data_fim}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Hora Inicial:</b> {{$exibir->hora_inicio}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Hora Final:</b> {{$exibir->hora_fim}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Tamanho Impressão:</b> {{$exibir->tamanho_impressao}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Codigo:</b> {{$exibir->codigo}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Local:</b> {{$exibir->nome_local}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Endereço:</b> {{$exibir->endereco_local}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Descrição:</b></h5>
                                <textarea id="summernote" disabled>
                                                        {{$exibir->descricao}}
                                        </textarea>
                        </div>

                        <div class="col-md-6 col-12">
                                <h5><b>Banner:</b> </h5>
                                @if(isset($exibir->url_imagem))
                                <img id="banner" width="500px" height="200px" src="/storage/{{$exibir->url_imagem}}"
                                        alt="">
                                @endif
                        </div>

                </div>
                <hr>
                <div class="row">
                        <div class="col-md-12 col-12">
                                <h3><b>Salas</b></h3>
                        </div>
                </div><br>
                @foreach ($sala as $sal)
                <div class="row">
                        <div class="col-md-6 col-12">
                                <h5><b>Sala:</b> <a href="../sala/{{$sal->id}}">{{$sal->nome}}</a></h5>

                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Local:</b> {{$sal->nome_local}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Palestrante:</b> {{$sal->palestrante}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Quantidade:</b> {{$sal->quantidade}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Data Inicio:</b> {{ $sal_inicio = date("d/m/Y", strtotime($sal->data_inicio)) }}
                                </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Data Fim:</b> {{  $sal_fim = date("d/m/Y", strtotime($sal->data_fim)) }}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Hora Inicio:</b> {{$sal->hora_inicio}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Hora Fim:</b> {{$sal->hora_fim}}</h5>
                        </div>
                        <div class="col-12">
                                <div class="btn-group float-right" role="group" aria-label="Basic example">
                                        <button href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                                data-target="#SALA{{$sal->id}}"><i
                                                        class="far fa-edit"></i>Editar</button>
                                        <button href="#" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                data-target="#remover{{$sal->id}}"><i class="far fa-trash-alt"></i>
                                                Apagar</button>
                                        <a  href="{{route('excelsala', ['id' => $sal->id])}}" class="btn btn-sm btn-outline-success float-right">
                                                <i class="fas fa-file-export"></i> Exportar</a>
                                </div>
                        </div>
                </div>

                <hr>

                <!-- *************************** MODAL SALAS ***********************************************-->
                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="SALA{{$sal->id}}"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">

                                        {{-- @if(count($creds) == 0) --}}
                                        <form class="form-horizontal" id="formSALA{{$sal->id}}"
                                                action="editarsala/{{$sal->id}}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                        <h4 class="modal-title"><b><i class="fas fa-door-open"></i>
                                                                        Editar sala
                                                                        {{$sal->nome}}</b>
                                                        </h4>
                                                </div>

                                                <div class="modal-body">
                                                        <div class="row">
                                                                <div class="col-12">
                                                                        <div class="alert alert-danger alert-dismissible fade show"
                                                                                role="alert" id="error{{$sal->id}}"
                                                                                style="display:none">
                                                                                <strong><i class="fas fa-times"></i>
                                                                                        Erro!</strong> Preencha todos os
                                                                                campos
                                                                                obrigatórios!
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="alert"
                                                                                        aria-label="Close">
                                                                                        <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                </button>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                        <div class="row">
                                                                <div class="col-12">
                                                                        <div class="alert alert-success alert-dismissible fade show"
                                                                                role="alert" id="alert{{$sal->id}}"
                                                                                style="display:none">
                                                                                <strong><i class="fas fa-check"></i>
                                                                                        Sucesso!</strong> Sala <span
                                                                                        id="salanova"></span>
                                                                                salva com sucesso!
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="alert"
                                                                                        aria-label="Close">
                                                                                        <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                </button>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                        <input type="hidden" class="id_evento{{$sal->id}}"
                                                                id="id_evento{{$sal->id}}" value="{{$sal->id}}"
                                                                name="id_evento" class="form-control">

                                                        <div class="row">
                                                                <div class="form-group col-12 col-md-6">
                                                                        <label for="salanome"
                                                                                class="control-label">Nome: *</label>
                                                                        <div class="input-group">
                                                                                <input type="text" class="form-control"
                                                                                        value="{{$sal->nome}}"
                                                                                        id="nomesala{{$sal->id}}"
                                                                                        name="nome">
                                                                                <div id="salanome"
                                                                                        class="invalid-feedback">Campo
                                                                                        Obigatorio!</div>
                                                                                <div id="salanome"
                                                                                        class="invalid-feedback exis">
                                                                                        Campo já
                                                                                        existente!</div>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-12 col-md-6">
                                                                        <label for="salalocal"
                                                                                class="control-label">Local: *</label>
                                                                        <div class="input-group">
                                                                                <input type="text" class="form-control"
                                                                                        value="{{$sal->nome_local}}"
                                                                                        id="salalocal{{$sal->id}}"
                                                                                        name="local">
                                                                                <div id="salalocal"
                                                                                        class="invalid-feedback">Campo
                                                                                        Obigatorio!</div>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-12 col-md-6">
                                                                        <label for="salapalestrante"
                                                                                class="control-label">Palestrante:
                                                                                *</label>
                                                                        <div class="input-group">
                                                                                <input type="text" class="form-control"
                                                                                        value="{{$sal->palestrante}}"
                                                                                        id="salapalestrante{{$sal->id}}"
                                                                                        name="palestrante">
                                                                                <div id="salapalestrante"
                                                                                        class="invalid-feedback campo">
                                                                                        Campo Obigatorio!
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-12 col-md-6">
                                                                        <label for="qntdsala"
                                                                                class="control-label">Quantidade de
                                                                                inscrições: </label>
                                                                        <div class="input-group">
                                                                                <input type="number"
                                                                                        class="form-control"
                                                                                        value="{{$sal->quantidade}}"
                                                                                        id="qntdsala{{$sal->id}}"
                                                                                        name="quantidade">
                                                                        </div>
                                                                        <small class="text-muted">Caso não a limitações
                                                                                deixe-o em branco</small>
                                                                </div>

                                                                <div class="col-12 col-md-3 form-group">
                                                                        <label for="data_ini">Data de Início: *</label>
                                                                        <input type="text"
                                                                                class="form-control data_ data_calendario"
                                                                                id="data_inicio{{$sal->id}}"
                                                                                name="data_inicio"
                                                                                value=" {{ $sal_inicio = date("d/m/Y", strtotime($sal->data_inicio)) }}"
                                                                                id="data_ini" placeholder="__/__/____">
                                                                </div>
                                                                <div class="col-12 col-md-3 form-group">
                                                                        <label for="data_fim">Data de Encerramento:
                                                                                *</label>
                                                                        <input type="text"
                                                                                class="form-control data_ data_calendario"
                                                                                id="data_fim{{$sal->id}}"
                                                                                name="data_fim"
                                                                                value="{{  $sal_fim = date("d/m/Y", strtotime($sal->data_fim)) }}"
                                                                                id="data_fim" placeholder="__/__/____">
                                                                </div>

                                                                <div class="col-12 col-md-3 form-group">
                                                                        <label for="hora_inicio">Horário de Início:
                                                                                *</label>
                                                                        <input type="time" class="form-control "
                                                                                id="hora_inicio{{$sal->id}}"
                                                                                name="hora_inicio"
                                                                                value="{{$sal->hora_inicio}}"
                                                                                id="hora_inicio"
                                                                                placeholder="Horário de Início">
                                                                </div>
                                                                <div class="col-12 col-md-3 form-group">
                                                                        <label for="hora_fim">Horário de Encerramento:
                                                                                *</label>
                                                                        <input type="time" class="form-control "
                                                                                id="hora_fim{{$sal->id}}"
                                                                                name="hora_fim"
                                                                                value="{{$sal->hora_fim}}" id="hora_fim"
                                                                                placeholder="Horário de Encerramento">
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="submit" id="buttonempresa{{$sal->id}}"
                                                                class="btn btn-success">Salvar</button>
                                                        <button type="cancel" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancelar</button>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>

                {{-- ---------------------------- REMOVER ------------------------------------------- --}}
                <div class="modal fade" id="remover{{$sal->id}}" tabindex="-1" role="dialog"
                        aria-labelledby="TituloModalCentralizado" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <h5 class="modal-title" id="TituloModalCentralizado">Excluir! </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                </button>

                                        </div>
                                        <div class="modal-body" id='excluir'>
                                                <div class="row" id="linha">
                                                        <div class="col-9">
                                                                <h5> Tem certeza que deseja excluir a sala:
                                                                        {{$sal->nome}}?</h5>
                                                        </div>
                                                        <div class="col-3">
                                                                <a href="/sala/apagar/{{$sal->id}}"
                                                                        class="btn btn-sm btn-danger"><i
                                                                                class="far fa-trash-alt"></i> Apagar</a>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Fechar</button>
                                        </div>
                                </div>
                        </div>
                </div>




                @endforeach
                <hr>
                <div class="row">
                        <div class="col-md-12 col-12">
                                <h3><b>Ingressos</b></h3>
                        </div>
                </div>

                @if(count($ingressos) > 0)


                <br>
                    <table class="table table-sm" id="tabelaempresa">
                        <thead>
                            <tr>                    
                                <th>Nome</th>
                                <th style="text-align: center;">Vendidos / Total</th>
                                <th>Taxa</th>
                                <th>Preço final</th>
                                <th style="text-align: center;">Visibilidade</th>
                                <th style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
            
                            @foreach ($ingressos as $ingresso)
                            <tr>
                                <td>{{$ingresso->nome}}</td>
                                <td style="text-align: center;">{{$ingresso->vendidos}} / {{$ingresso->qntd}} </td>
                                <?php 
                                    $portcentagem = ($ingresso->total * (10/100));
                                    // $portcentagem = $portcentagem*1;
                                    //$ingresso->total = str_replace('.','',number_format($ingresso->total, 2, '.', ''));
                                ?>
                                <td>R$ {{($portcentagem)}}</td>
                                <td>R$ {{($ingresso->total) }}</td>
                                <td style="text-align: center;">
                                    @if($ingresso->ativo == false)
                                        <button id="{{ $ingresso->id }}" name="remover" class="btn btn-sm button-ingresso btn-danger fas fa-ban" onclick="({{ $ingresso->id }})" title="Remover Ingresso">
                                        </button>
                                    @else
                                        <button id="{{ $ingresso->id }}" name="adicionar" class="btn btn-sm button-ingresso btn-success  fas fa-check-circle" onclick="({{ $ingresso->id }})" title="Adicionar Ingresso">                            
                                        </button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group" role="group" aria-label="Basic example ">
                                        <a href="{{ route('editaringresso', ['id' => $ingresso->id, 'id_evento' => $exibir->id ]) }}" class="btn btn-sm btn-primary" title="editar ingresso">
                                            <i class="far fa-edit" ></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" role="button" data-toggle="modal" data-target="#modalremover{{$ingresso->id}}" title="apagar ingresso">
                                            <i class="far fa-trash-alt" ></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
            
                                {{-- ---------------------------- REMOVER ------------------------------------------- --}}
                                <div class="modal fade" id="modalremover{{$ingresso->id}}" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                    <div class="modal-header">
                                                            <h5 class="modal-title" id="TituloModalCentralizado">Excluir! </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                                    <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body" id='excluir'>
                                                            <div class="row" id="linha">
                                                                    <div class="col-9">
                                                                            <h5> Tem certeza que deseja o ingresso: {{$ingresso->nome}}?</h5>
                                                                    </div>
                                                                    <div class="col-3">
                                                                            <a href="{{route('apagaringresso', ['id' => $ingresso->id, 'id_evento' => $exibir->id ])}}" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Apagar</a>
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
                        </tbody>
                    </table>
                
                @endif
                <br>


                @if(count($sala) <= 0) <hr> @endif

                        <div class="row">
                                <div class="col-md-12 col-12">
                                        <h3><b>Formulario Credenciamento</b></h3>
                                </div>
                        </div>
                        <div class="row">
                                @if(count($cred) > 0)
                                @foreach ($cred as $creds)

                                <div class="col-md-6 col-12">
                                        {{-- @if($creds->id_evento == $exibir->id) --}}

                                        <br>
                                        <label for="valor_salvo[]">{{$creds->nome}}:</label>
                                        <div class="btn-group float-right" role="group" aria-label="Basic example">
                                                @if(($creds->nome != 'Nome') && ($creds->nome != 'Email') &&
                                                ($creds->nome !=
                                                'Celular') && ($creds->nome != 'Cpf'))
                                                @if($creds->ativo == true)
                                                <button id="button" name="{{ $creds->id }}"
                                                        class="btn btn-sm btn-danger button"
                                                        onclick="({{ $creds->id }})">Remover</button>
                                                @else
                                                <button id="button" name="{{ $creds->id }}"
                                                        class="btn btn-sm btn-success button"
                                                        onclick="({{ $creds->id }})">Adicionar</button>
                                                @endif
                                                <button href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                                        data-target="#dlgEmpresa{{$creds->id}}"><i
                                                                class="far fa-edit"></i>Editar</button>
                                                @endif
                                        </div>
                                        @php
                                        $strExemple = $creds->opcoes;
                                        $opcoes = explode(';', $strExemple);
                                        if($creds->obrigatorio == 1){
                                        $creds->obrigatorio = 'required' ;
                                        }
                                        @endphp
                                        @switch($creds->tipo)
                                        @case('text')
                                        <input type="text" class="form-control" name="valor_salvo[]"
                                                placeholder="{{$creds->nome}}" {{$creds->obrigatorio}} readonly
                                                Maxlange="{{$creds->tamanho}}">
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('number')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="{{$creds->nome}}" {{$creds->obrigatorio}} readonly>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('cpf')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="EX: 999.999.999-99" {{$creds->obrigatorio}} readonly>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('cnpj')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="EX: 00.000.000/0000-00" {{$creds->obrigatorio}} readonly>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('email')
                                        <input type="email" class="form-control" name="valor_salvo[]"
                                                placeholder="{{$creds->nome}}" {{$creds->obrigatorio}} readonly>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('tel')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="EX: (99) 99999-9999" {{$creds->obrigatorio}} readonly>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('date')
                                        <input type="text" class="form-control data_ data_calendario"
                                                name="valor_salvo[]" placeholder="__/__/____" {{$creds->obrigatorio}}
                                                readonly>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('checkbox')
                                        @foreach ($opcoes as $op)
                                        <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="valor_salvo[]"
                                                        value='{{$op}}' {{$creds->obrigatorio}} readonly>
                                                <label class="form-check-label" for="valor_salvo[]">{{$op}}</label>
                                        </div>
                                        @endforeach
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('select')
                                        <select class="form-control" name="valor_salvo[]" {{$creds->obrigatorio}}
                                                readonly>
                                                @foreach ($opcoes as $op)
                                                <option value='{{$op}}'>{{$op}}</option>
                                                @endforeach
                                        </select>
                                        <small>@if($creds->cracha == 1) Impressão @endif</small>
                                        @break
                                        @default
                                        @endswitch

                                        {{-- @endif --}}
                                </div>

                                <!-- *************************** MODAL CREDENCIAMENTO ***********************************************-->

                                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                        id="dlgEmpresa{{$creds->id}}" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">

                                                        {{-- @if(count($creds) == 0) --}}

                                                        <form class="form-horizontal" id="formempresa{{$creds->id}}"
                                                                action="editarcred/{{$creds->id}}" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="modal-header">
                                                                        @csrf
                                                                        <h4 class="modal-title"><b><i
                                                                                                class="fas fa-id-badge"></i>
                                                                                        Editar campo:
                                                                                        {{$creds->nome}}</b>
                                                                        </h4>
                                                                </div>

                                                                {{-- <hr> --}}
                                                                <div class="modal-body">
                                                                        <input type="hidden"
                                                                                class="id_evento{{$creds->id}}"
                                                                                id="id_evento{{$creds->id}}"
                                                                                value="{{$creds->id}}" name="id_evento"
                                                                                class="form-control">
                                                                        <div class="row">
                                                                                <div class="form-group col-12 col-md-6">
                                                                                        <label for="nome"
                                                                                                class="control-label">Nome
                                                                                                do Campo *</label>
                                                                                        <div class="input-group">
                                                                                                <input type="text"
                                                                                                        class="form-control"
                                                                                                        value="{{$creds->nome}}"
                                                                                                        id="nome{{$creds->id}}"
                                                                                                        name="nome"
                                                                                                        required>
                                                                                                <div id="campo{{$creds->id}}"
                                                                                                        class="invalid-feedback campo">
                                                                                                        Campo
                                                                                                        Obigatorio!
                                                                                                </div>
                                                                                                <div id="exis{{$creds->id}}"
                                                                                                        class="invalid-feedback exis">
                                                                                                        Campo já
                                                                                                        existente!</div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="form-group col-12 col-md-6">
                                                                                        {{-- <label class="control-label">Opções</label> --}}
                                                                                        <div class="form-check ">
                                                                                                <input class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        name="obrigatorio"
                                                                                                        id="obrigatorio{{$creds->id}}"
                                                                                                        value='1'
                                                                                                        @if($creds->obrigatorio
                                                                                                == true) checked='true'
                                                                                                @endif/>
                                                                                                <label for="obrigatorio{{$creds->id}}"
                                                                                                        class="form-check-label">Campo
                                                                                                        obrigatório?</label>
                                                                                        </div>
                                                                                        <div class="form-check ">
                                                                                                <input class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        name="unico"
                                                                                                        id="unico{{$creds->id}}"
                                                                                                        value='1'
                                                                                                        @if($creds->unico
                                                                                                == true) checked
                                                                                                @endif/>
                                                                                                <label for="unico{{$creds->id}}"
                                                                                                        class="form-check-label">Campo
                                                                                                        deve ter
                                                                                                        valor
                                                                                                        único?</label>
                                                                                        </div>
                                                                                        <div class="form-check ">
                                                                                                <input class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        name="cracha"
                                                                                                        id="cracha{{$creds->id}}"
                                                                                                        value='1'
                                                                                                        @if($creds->cracha
                                                                                                == true) checked @endif
                                                                                                />
                                                                                                <label for="cracha{{$creds->id}}"
                                                                                                        class="form-check-label">Campo
                                                                                                        deve ter
                                                                                                        valor impresso
                                                                                                        na
                                                                                                        etiqueta?</label>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="form-group col-12 col-md-6">
                                                                                        <label for="tipo"
                                                                                                class="control-label">Tipo
                                                                                                *</label>
                                                                                        <select class="form-control tipo{{$creds->id}}"
                                                                                                name="tipo"
                                                                                                value="{{$creds->tipo}}"
                                                                                                id="tipo{{$creds->id}}">

                                                                                                <option value="cpf"
                                                                                                        @if($creds->tipo
                                                                                                        == 'cpf')
                                                                                                        selected
                                                                                                        @endif>CPF
                                                                                                </option>
                                                                                                <option value="cnpj"
                                                                                                        @if($creds->tipo
                                                                                                        == 'cnpj')
                                                                                                        selected @endif>
                                                                                                        CNPJ</option>
                                                                                                <option value="email"
                                                                                                        @if($creds->tipo
                                                                                                        == 'email')
                                                                                                        selected @endif>
                                                                                                        Email</option>
                                                                                                <option value="number"
                                                                                                        @if($creds->tipo
                                                                                                        == 'number')
                                                                                                        selected @endif>
                                                                                                        Número</option>
                                                                                                <option value="tel"
                                                                                                        @if($creds->tipo
                                                                                                        == 'tel')
                                                                                                        selected @endif>
                                                                                                        Telefone
                                                                                                </option>
                                                                                                <option value="date"
                                                                                                        @if($creds->tipo
                                                                                                        == 'date')
                                                                                                        selected @endif>
                                                                                                        Data</option>
                                                                                                <option class="text{{$creds->id}}"
                                                                                                        value="text"
                                                                                                        @if($creds->tipo
                                                                                                        == 'text')
                                                                                                        selected @endif>
                                                                                                        Texto</option>
                                                                                                <option class="valor{{$creds->id}}"
                                                                                                        value="select"
                                                                                                        @if($creds->tipo
                                                                                                        == 'select')
                                                                                                        selected @endif>
                                                                                                        Dropdown
                                                                                                </option>
                                                                                                <option class="valor2{{$creds->id}}"
                                                                                                        value="checkbox"
                                                                                                        @if($creds->tipo
                                                                                                        == 'checkbox')
                                                                                                        selected @endif>
                                                                                                        Múltiplos
                                                                                                        Checkbox
                                                                                                </option>
                                                                                        </select>
                                                                                </div>
                                                                                <div class="form-group col-12 col-md-6 valores{{$creds->id}}"
                                                                                        @if(($creds->tipo != 'select')
                                                                                        && ($creds->tipo != 'checkbox'))
                                                                                        style="display: none" @endif>

                                                                                        <label for="opcoes"
                                                                                                class="control-label">Valores
                                                                                                ( separa por ponto e
                                                                                                virgula ;
                                                                                                )</label>
                                                                                        <div class="input-group">
                                                                                                <input type="text"
                                                                                                        class="form-control"
                                                                                                        name="opcoes"
                                                                                                        value="{{$creds->opcoes}}"
                                                                                                        id="opcoes{{$creds->id}}"
                                                                                                        placeholder="EX: 1;2;3;4;5">
                                                                                        </div>
                                                                                </div>
                                                                                <div class="form-group col-12 col-md-6 tamanho{{$creds->id}}"
                                                                                        @if($creds->tipo != 'text')
                                                                                        style="display: none" @endif>
                                                                                        <label for="tamanho"
                                                                                                class="control-label ">Tamanho
                                                                                                máximo (200
                                                                                                caracteres)</label>
                                                                                        <div class="input-group">
                                                                                                <input type="number"
                                                                                                        class="form-control"
                                                                                                        name="tamanho"
                                                                                                        value="{{$creds->tamanho}}"
                                                                                                        id="tamanho{{$creds->id}}"
                                                                                                        placeholder=" ">
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-success">Salvar</button>
                                                                        <button type="cancel" class="btn btn-secondary"
                                                                                data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                        </form>

                                                </div>
                                        </div>
                                </div>
                                <!-- ****************************************************************************************************************** -->
                                <script type="text/javascript">
                                        $(document).ready(function() {
        
                                                $("select").change(function() {
                                                    if ($('.text{{$creds->id}}').prop("selected") == true) {
                                                        $('.tamanho{{$creds->id}}').fadeIn("show");
                                                    } else {
                                                        $('.tamanho{{$creds->id}}').hide();
                                                    }
                                                    if (($('.valor{{$creds->id}}').prop("selected") == true) || ($('.valor2{{$creds->id}}').prop("selected") == true)) {
                                                        $('.valores{{$creds->id}}').fadeIn("show");
                                                    } else {
                                                        $('.valores{{$creds->id}}').hide();
                                                    }
                                                });
                                        });
                                </script>

                                @endforeach
                                @else
                                <div class="col-12">
                                        Nenhum campo cadastrado até o momento, é preciso de pelo menos um campo para o
                                        formulario!
                                </div>
                                @endif
                        </div>
                        <hr>
                        <!-- -------------------------------------------------------------------------------------------------------------------- -->
                        <div class="row">
                                <div class="col-md-12 col-12">
                                        <h3><b>Formulario CAEX</b></h3>
                                </div>
                        </div>
                        <div class="row">
                                @if(count($caex) > 0)
                                @foreach ($caex as $caexs)
                                <div class="col-md-6 col-12">
                                        {{-- @if($caexs->id_evento == $exibir->id) --}}
                                        <br>
                                        <label for="valor_salvo[]">{{$caexs->nome}}:</label>
                                        <div class="btn-group float-right" role="group" aria-label="Basic example">
                                                @if(($caexs->nome != 'Nome') && ($caexs->nome != 'Email') &&
                                                ($caexs->nome !=
                                                'Celular') && ($caexs->nome != 'Cpf'))
                                                @if($caexs->ativo == true)
                                                <button id="button" name="{{ $caexs->id }}"
                                                        class="btn btn-sm btn-danger button"
                                                        onclick="({{ $caexs->id }})">Remover</button>
                                                @else
                                                <button id="button" name="{{ $caexs->id }}"
                                                        class="btn btn-sm btn-success button"
                                                        onclick="({{ $caexs->id }})">Adicionar</button>
                                                @endif
                                                <button href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                                        data-target="#dlgEmpresa{{$caexs->id}}"><i
                                                                class="far fa-edit"></i>Editar</button>
                                                @endif
                                        </div>
                                        @php
                                        $strExemple = $caexs->opcoes;
                                        $opcoes = explode(';', $strExemple);
                                        if($caexs->obrigatorio == 1){
                                        $caexs->obrigatorio = 'required' ;
                                        }
                                        @endphp
                                        @switch($caexs->tipo)
                                        @case('text')
                                        <input type="text" class="form-control" name="valor_salvo[]"
                                                placeholder="{{$caexs->nome}}" {{$caexs->obrigatorio}} readonly
                                                Maxlange="{{$caexs->tamanho}}">
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('number')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="{{$caexs->nome}}" {{$caexs->obrigatorio}} readonly>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('cpf')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="EX: 999.999.999-99" {{$caexs->obrigatorio}} readonly>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('cnpj')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="EX: 00.000.000/0000-00" {{$caexs->obrigatorio}} readonly>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('email')
                                        <input type="email" class="form-control" name="valor_salvo[]"
                                                placeholder="{{$caexs->nome}}" {{$caexs->obrigatorio}} readonly>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('tel')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                                placeholder="EX: (99) 99999-9999" {{$caexs->obrigatorio}} readonly>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('date')
                                        <input type="text" class="form-control data_ data_calendario"
                                                name="valor_salvo[]" placeholder="__/__/____" {{$caexs->obrigatorio}}
                                                readonly>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('checkbox')
                                        @foreach ($opcoes as $op)
                                        <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="valor_salvo[]"
                                                        value='{{$op}}' {{$caexs->obrigatorio}} readonly>
                                                <label class="form-check-label" for="valor_salvo[]">{{$op}}</label>
                                        </div>
                                        @endforeach
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @case('select')
                                        <select class="form-control" name="valor_salvo[]" {{$caexs->obrigatorio}}
                                                readonly>
                                                @foreach ($opcoes as $op)
                                                <option value='{{$op}}'>{{$op}}</option>
                                                @endforeach
                                        </select>
                                        <small>@if($caexs->cracha == 1) Impressão @endif</small>
                                        @break
                                        @default
                                        @endswitch
                                        {{-- @endif --}}
                                </div>
                                <!-- *************************** MODAL CAEX ***********************************************-->

                                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                        id="dlgEmpresa{{$caexs->id}}" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">

                                                        {{-- @if(count($caexs) == 0) --}}

                                                        <form class="form-horizontal" id="formempresa{{$caexs->id}}"
                                                                action="editarcaex/{{$caexs->id}}" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="modal-header">
                                                                        @csrf
                                                                        <h4 class="modal-title"><b><i
                                                                                                class="fas fa-id-badge"></i>
                                                                                        Editar campo:
                                                                                        {{$caexs->nome}}</b>
                                                                        </h4>
                                                                </div>

                                                                {{-- <hr> --}}
                                                                <div class="modal-body">
                                                                        <input type="hidden"
                                                                                class="id_evento{{$caexs->id}}"
                                                                                id="id_evento{{$caexs->id}}"
                                                                                value="{{$caexs->id}}" name="id_evento"
                                                                                class="form-control">
                                                                        <div class="row">
                                                                                <div class="form-group col-12 col-md-6">
                                                                                        <label for="nome"
                                                                                                class="control-label">Nome
                                                                                                do Campo *</label>
                                                                                        <div class="input-group">
                                                                                                <input type="text"
                                                                                                        class="form-control"
                                                                                                        value="{{$caexs->nome}}"
                                                                                                        id="nome{{$caexs->id}}"
                                                                                                        name="nome">
                                                                                                <div id="campo{{$caexs->id}}"
                                                                                                        class="invalid-feedback campo">
                                                                                                        Campo
                                                                                                        Obigatorio!
                                                                                                </div>
                                                                                                <div id="exis{{$caexs->id}}"
                                                                                                        class="invalid-feedback exis">
                                                                                                        Campo já
                                                                                                        existente!</div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="form-group col-12 col-md-6">
                                                                                        {{-- <label class="control-label">Opções</label> --}}
                                                                                        <div class="form-check ">
                                                                                                <input class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        name="obrigatorio"
                                                                                                        id="obrigatorio{{$caexs->id}}"
                                                                                                        value='1'
                                                                                                        @if($caexs->obrigatorio
                                                                                                == true) checked='true'
                                                                                                @endif/>
                                                                                                <label for="obrigatorio{{$caexs->id}}"
                                                                                                        class="form-check-label">Campo
                                                                                                        obrigatório?</label>
                                                                                        </div>
                                                                                        <div class="form-check ">
                                                                                                <input class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        name="unico"
                                                                                                        id="unico{{$caexs->id}}"
                                                                                                        value='1'
                                                                                                        @if($caexs->unico
                                                                                                == true) checked
                                                                                                @endif/>
                                                                                                <label for="unico{{$caexs->id}}"
                                                                                                        class="form-check-label">Campo
                                                                                                        deve ter
                                                                                                        valor
                                                                                                        único?</label>
                                                                                        </div>
                                                                                        <div class="form-check ">
                                                                                                <input class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        name="cracha"
                                                                                                        id="cracha{{$caexs->id}}"
                                                                                                        value='1'
                                                                                                        @if($caexs->cracha
                                                                                                == true) checked @endif
                                                                                                />
                                                                                                <label for="cracha{{$caexs->id}}"
                                                                                                        class="form-check-label">Campo
                                                                                                        deve ter
                                                                                                        valor impresso
                                                                                                        na
                                                                                                        etiqueta?</label>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="form-group col-12 col-md-6">
                                                                                        <label for="tipo"
                                                                                                class="control-label">Tipo
                                                                                                *</label>
                                                                                        <select class="form-control tipo{{$caexs->id}}"
                                                                                                name="tipo"
                                                                                                value="{{$caexs->tipo}}"
                                                                                                id="tipo{{$caexs->id}}">
                                                                                                {{-- <option value="{{$caexs->tipo}}"
                                                                                                selected>
                                                                                                {{ ucfirst($creds->tipo) }}
                                                                                                </option> --}}
                                                                                                <option value="cpf"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'cpf')
                                                                                                        selected @endif>
                                                                                                        CPF</option>
                                                                                                <option value="cnpj"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'cnpj')
                                                                                                        selected @endif>
                                                                                                        CNPJ</option>
                                                                                                <option value="email"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'email')
                                                                                                        selected @endif>
                                                                                                        Email</option>
                                                                                                <option value="number"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'number')
                                                                                                        selected @endif>
                                                                                                        Número</option>
                                                                                                <option value="tel"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'tel')
                                                                                                        selected @endif>
                                                                                                        Telefone
                                                                                                </option>
                                                                                                <option value="date"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'date')
                                                                                                        selected @endif>
                                                                                                        Data</option>
                                                                                                <option class="text{{$caexs->id}}"
                                                                                                        value="text"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'text')
                                                                                                        selected @endif>
                                                                                                        Texto</option>
                                                                                                <option class="valor{{$caexs->id}}"
                                                                                                        value="select"
                                                                                                        @if($caexs->tipo
                                                                                                        == 'select')
                                                                                                        selected @endif>
                                                                                                        Dropdown
                                                                                                </option>
                                                                                                <option class="valor2{{$caexs->id}}"
                                                                                                        value="checkbox">
                                                                                                        Múltiplos
                                                                                                        Checkbox
                                                                                                </option>
                                                                                        </select>
                                                                                </div>
                                                                                <div class="form-group col-12 col-md-6 valores{{$caexs->id}}"
                                                                                        @if(($caexs->tipo != 'select')
                                                                                        && ($caexs->tipo != 'checkbox'))
                                                                                        style="display: none" @endif>

                                                                                        <label for="opcoes"
                                                                                                class="control-label">Valores
                                                                                                ( separa por ponto e
                                                                                                virgula ;
                                                                                                )</label>
                                                                                        <div class="input-group">
                                                                                                <input type="text"
                                                                                                        class="form-control"
                                                                                                        name="opcoes"
                                                                                                        value="{{$caexs->opcoes}}"
                                                                                                        id="opcoes{{$caexs->id}}"
                                                                                                        placeholder="EX: 1;2;3;4;5">
                                                                                        </div>
                                                                                </div>
                                                                                <div class="form-group col-12 col-md-6 tamanho{{$caexs->id}}"
                                                                                        @if($caexs->tipo != 'text')
                                                                                        style="display: none" @endif>
                                                                                        <label for="tamanho"
                                                                                                class="control-label ">Tamanho
                                                                                                máximo (200
                                                                                                caracteres)</label>
                                                                                        <div class="input-group">
                                                                                                <input type="number"
                                                                                                        class="form-control"
                                                                                                        name="tamanho"
                                                                                                        value="{{$caexs->tamanho}}"
                                                                                                        id="tamanho{{$caexs->id}}"
                                                                                                        placeholder=" ">
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-success">Salvar</button>
                                                                        <button type="cancel" class="btn btn-secondary"
                                                                                data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                        </form>

                                                </div>
                                        </div>
                                </div>



                                <!-- ****************************************************************************************************************** -->
                                <script type="text/javascript">
                                        $(document).ready(function() {

        $("select").change(function() {
            if ($('.text{{$caexs->id}}').prop("selected") == true) {
                $('.tamanho{{$caexs->id}}').fadeIn("show");
            } else {
                $('.tamanho{{$caexs->id}}').hide();
            }
            if (($('.valor{{$caexs->id}}').prop("selected") == true) || ($('.valor2{{$caexs->id}}').prop("selected") == true)) {
                $('.valores{{$caexs->id}}').fadeIn("show");
            } else {
                $('.valores{{$caexs->id}}').hide();
            }
        });
});
                                </script>

                                @endforeach
                                @else
                                <div class="col-md-6 col-12">
                                        Nenhum campo cadastrado até o momento, é preciso de pelo menos um campo para o
                                        formulario!
                                </div>
                                @endif
                        </div>
        </div>
        <div class="card-footer">
                <a href="/evento/" class="btn btn-sm btn-primary" role="button">Voltar</a>
        </div>
</div>
<script src="{{ asset('js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap-datepicker.pt-BR.min.js')}}" type="text/javascript"></script>
<script>
        $('.data_calendario').datepicker({	
				format: "dd/mm/yyyy",	
				language: "pt-BR",
				startDate: '+0d',
                        });
                        
                        
        $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
        });
        
        $(document).ready(function() {
                $('#summernote').summernote({
                        toolbar: [],
                });
                $('.note-statusbar').remove();
                $('.note-resizebar').remove();
                document.getElementById("summernote").disabled;

                $('.data_').mask('00/00/0000', {
                    reverse: true
                    }); // Máscara para data
        });

        $(".button").click(function() {
            var id = $(this).attr("name");
            var acao = $(this).text();
            console.log(acao);
            console.log(id);
            //$id = id;
            // se o usuario estiver ativo, desative ele, você precisa adicionar um ajax para enviar a acao para o php, pode ser um update where cliente = nomeUsuario.
            if (acao === "Adicionar") {
                $(this).text("Remover").removeClass("btn-success").addClass("btn-danger");
                $.ajax({
                    type: "get",
                    url: "/apagarcred",
                    dataType: "json",
                    data: {
                        id: id,
                        status: 1
                    },
                    success: function(data) {
                        if (data.sucesso == 1) {
                            alert(data.id);
                            console.log(data.id);
                        }
                    },
                });
            } else {
                $(this).text("Adicionar").removeClass("btn-danger").addClass("btn-success");
                $.ajax({
                    type: "get",
                    url: "/apagarcred",
                    dataType: "json",
                    data: {
                        id: id,
                        status: 0
                    },
                    success: function(data) {
                        if (data.sucesso == 1) {
                            alert(data.id);
                            console.log(data.id);
                        }
                    },
                });
            }
        });

        $(".buttoncaex").click(function() {
            var id = $(this).attr("name");
            var acao = $(this).text();
            console.log(acao);
            console.log(id);
            //$id = id;
            // se o usuario estiver ativo, desative ele, você precisa adicionar um ajax para enviar a acao para o php, pode ser um update where cliente = nomeUsuario.
            if (acao === "Adicionar") {
                $(this).text("Remover").removeClass("btn-success").addClass("btn-danger");
                $.ajax({
                    type: "get",
                    url: "/apagarcaex",
                    dataType: "json",
                    data: {
                        id: id,
                        status: 1
                    },
                    success: function(data) {
                        if (data.sucesso == 1) {
                            alert(data.id);
                            console.log(data.id);
                        }
                    },
                });
            } else {
                $(this).text("Adicionar").removeClass("btn-danger").addClass("btn-success");
                $.ajax({
                    type: "get",
                    url: "/apagarcaex",
                    dataType: "json",
                    data: {
                        id: id,
                        status: 0
                    },
                    success: function(data) {
                        if (data.sucesso == 1) {
                            alert(data.id);
                            console.log(data.id);
                        }
                    },
                });
            }
        });

        $(".button-ingresso").click(function() {
            var id      = $(this).attr("id");
            var name    = $(this).attr("name");
            console.log(id);
            console.log(name);

            //$id = id;
            // se o usuario estiver ativo, desative ele, você precisa adicionar um ajax para enviar a acao para o php, pode ser um update where cliente = nomeUsuario.
            if (name == "remover") {
                $(this).removeClass("fa-ban").removeClass("btn-danger").addClass("btn-success").addClass("fa-check-circle");
                $(this).attr("name", 'adicionar');
                $(this).attr("title", 'Remover Ingresso');
                $.ajax({
                    type: "get",
                    url: "/desativarIngresso",
                    dataType: "json",
                    data: {
                        id: id,
                        status: 0
                    },
                    success: function(data) {
                        if (data.sucesso == 1) {
                            //alert(data.mensagem);
                            //console.log(data.id);
                        }
                    },
                });
            } else {
                $(this).removeClass("fa-check-circle").removeClass("btn-success").addClass("btn-danger").addClass("fa-ban");
                $(this).attr("name", 'remover');
                $(this).attr("title", 'Adicionar Ingresso');
                $.ajax({
                    type: "get",
                    url: "/desativarIngresso",
                    dataType: "json",
                    data: {
                        id: id,
                        status: 1
                    },
                    success: function(data) {
                        if (data.sucesso == 1) {
                            //alert(data.mensagem);
                            //console.log(data.id);
                        }
                    },
                });
            }
        });

</script>

@endsection