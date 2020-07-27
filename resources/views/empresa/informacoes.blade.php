<?php

?>
@extends('layout.app', ["current" => "empresa"])

@section('body')

<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-md-10 col-sm-12">
                                <h3><i class="fas fa-info-circle"></i> Informações cadastradas Empresa -
                                        <b>{{$empresa->nome_fantasia}}</b></h3>
                        </div>
                        <div class="col-md-2 col-sm-12">
                                <a href="{{route('excelempresa', ['id' => $empresa->id])}}"
                                        class="btn btn-md btn-outline-success float-right"><i class="fas fa-file-export"></i>
                                        Exportar</a>
                        </div>
                </div>
        </div>
        <div class="card-body">
                <div class="row">
                        <div class="col-md-6 col-12">
                                <h5><b>Razão Social:</b> {{$empresa->razao_social}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>CNPJ:</b> {{$empresa->cnpj}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Nome do Responsável:</b> {{$empresa->responsavel}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Telefone Fixo:</b> {{$empresa->telefone}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Celular:</b> {{$empresa->celular}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Email:</b> {{$empresa->email}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Site:</b> <a href="https://{{$empresa->site}}/" target="_blank">{{$empresa->site}}</a></h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>CEP:</b> {{$empresa->cep}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Estado:</b> {{$empresa->estado}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Cidade:</b> {{$empresa->cidade}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Bairro :</b> {{$empresa->bairro}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Rua, Av.:</b> {{$empresa->rua}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Número:</b> {{$empresa->numero}}</h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Complemento:</b> {{$empresa->complemento}}</h5>
                        </div>

                </div>
                <hr>
                <div class="row">
                        <div class="col-md-12 col-12">
                                <h3><b>Eventos</b></h3>
                        </div>
                </div><br>
                @foreach ($evento as $env)
                        <div class="row">
                                <div class="col-md-6 col-12">
                                        <i class="fas fa-tag"></i>&nbsp;<b style="font-size:20px"><a href="/info/{{$env->id}}">{{$env->nome}}</a></b>
                                </div>
                        </div>
                @endforeach
                <hr>
                <div class="row">
                        <div class="col-md-12 col-12">
                                <h3><b>Usúarios</b></h3>
                        </div>
                </div><br>
                @foreach ($usuario as $user)
                        <div class="row">
                                <div class="col-md-6 col-12">
                                        <i class="fas fa-user"></i>&nbsp;<span style="font-size:20px">{{$user->name}}</span>
                                </div>
                        </div>
                @endforeach
        </div>
        <div class="card-footer">
                <a href="/empresa/" class="btn btn-sm btn-primary" role="button">Voltar</a>
        </div>
</div>

@endsection