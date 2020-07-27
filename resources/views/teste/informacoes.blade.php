<?php

?>
@extends('layout.app', ["current" => "empresa"])

@section('body')

<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-10">
                                <h3><i class="fas fa-info-circle"></i> Informações cadastradas Empresa -
                                        <b>{{$empresa->nome_fantasia}}</b></h3>
                        </div>
                        <div class="col-2">
                                <a href="{{route('excelempresa', ['id' => $empresa->id])}}"
                                        class="btn btn-md btn-outline-success"><i class="fas fa-file-export"></i>
                                        Exportar</a>
                        </div>
                </div>
        </div>
        <div class="card-body">
                <div class="row">
                        <div class="col-3">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link active" id="v-pills-dados-tab" data-toggle="pill"
                                                href="#v-pills-dados" role="tab" aria-controls="v-pills-dados"
                                                aria-selected="true">Dados da Empresa</a>
                                        <a class="nav-link" id="v-pills-endereço-tab" data-toggle="pill"
                                                href="#v-pills-endereço" role="tab" aria-controls="v-pills-endereço"
                                                aria-selected="false">Eventos cadastrados</a>
                                        <a class="nav-link" id="v-pills-usuarios-tab" data-toggle="pill"
                                                href="#v-pills-usuarios" role="tab" aria-controls="v-pills-usuarios"
                                                aria-selected="false">Usuários</a>
                                </div>
                        </div>
                        <div class="col-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="v-pills-dados" role="tabpanel"
                                                aria-labelledby="v-pills-dados-tab">
                                                <div class="row">
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Razão Social:</b> {{$empresa->razao_social}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>CNPJ:</b> {{$empresa->cnpj}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Nome do Responsável:</b>
                                                                        {{$empresa->responsavel}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Email:</b> {{$empresa->email}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Celular:</b> {{$empresa->celular}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Telefone Fixo:</b> {{$empresa->telefone}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Site:</b> <a href="https://{{$empresa->site}}/"
                                                                                target="_blank">{{$empresa->site}}</a>
                                                                </h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>CEP:</b> {{$empresa->cep}}</h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                                <h5><b>Endereço:</b> {{$empresa->rua}},
                                                                        {{$empresa->numero}}, {{$empresa->complemento}}
                                                                        - {{$empresa->bairro}} -
                                                                        {{$empresa->cidade}}/{{$empresa->estado}}</h5>
                                                        </div>
                                                </div>

                                        </div>

                                        <div class="tab-pane fade" id="v-pills-endereço" role="tabpanel"
                                                aria-labelledby="v-pills-endereço-tab">
                                                <table class="table table-hover table-bordered">
                                                        <thead>
                                                                <tr>
                                                                        <th>Evento</th>
                                                                        <th>Data</th>
                                                                        <th>Horário do evento</th>
                                                                </tr>
                                                        </thead>
                                                        <tbody>
                                                                @foreach ($evento as $env)
                                                                @if ($env->ativo == 1)
                                                                <div class="row">
                                                                        <tr>
                                                                                <td><b><a
                                                                                                        href="/info/{{$env->id}}">{{$env->nome}}</a></b>
                                                                                </td>
                                                                                <td>{{$env->data_inicio}}</td>
                                                                                <td>{{$env->hora_inicio}}</td>
                                                                        </tr>
                                                                </div>
                                                                @endif
                                                                @endforeach
                                                        </tbody>
                                                </table>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-usuarios" role="tabpanel"
                                                aria-labelledby="v-pills-usuarios-tab">
                                                <table class="table table-hover table-bordered">
                                                        <thead>
                                                                <tr>
                                                                        <th>Nome</th>
                                                                        <th>Email</th>
                                                                        <th>Cargo</th>
                                                                </tr>
                                                        </thead>
                                                        <tbody>
                                                                @foreach ($usuario as $user)
                                                                <div class="row">

                                                                        @php
                                                                        if(($user->cargo) == 1){
                                                                        $user->cargo = 'Empresa';
                                                                        }
                                                                        if(($user->cargo) == 2){
                                                                        $user->cargo = 'Usuário';
                                                                        }
                                                                        @endphp
                                                                        <tr>
                                                                                <td>{{$user->nome}}</td>
                                                                                <td>{{$user->email}}</td>
                                                                                <td>{{$user->cargo}}</td>
                                                                        </tr>
                                                                </div>
                                                                @endforeach
                                                        </tbody>
                                                </table>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
        <div class="card-footer">
                <a href="/empresa/" class="btn btn-sm btn-primary" role="button">Voltar</a>
        </div>
</div>

@endsection