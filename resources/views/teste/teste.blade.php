<?php

?>
@extends('layout.app', ["current" => "empresa"])

@section('body')

<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-md-10 col-sm-12">
                                <h3><i class="fas fa-code"></i> Pagina teste
                                        <b></b></h3>
                        </div>
                </div>
        </div>
        <div class="card-body">
                <div class="row">
                        <div class="col-md-6 col-12">
                                <h5><b>Pagamento cartão de crédito API GetNet</b> - <a href="{{ route('pagar') }}"> PAGAMENTO </a></h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Pagamento Boleto API GetNet</b> - <a href="{{ route('boleto') }}"> BOLETO </a></h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Pagina de Novo Evento:</b> <a href="{{ route('novoevento') }}"> NOVO EVENTO </a> </h5>
                        </div>
                        {{-- <div class="col-md-6 col-12">
                                <h5><b>Pagina de formulario Credeciamentos:</b> <a href="{{ route('formcred') }}"> NOVO EVENTO </a> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Pagina de Informações:</b> <a href="{{ route('info') }}"> NOVO EVENTO </a> </h5>
                        </div> --}}
                        {{-- <div class="col-md-6 col-12">
                                <h5><b>Nome do Responsável:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Telefone Fixo:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Celular:</b></h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Email:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Site:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>CEP:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Estado:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Cidade:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Bairro :</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Rua, Av.:</b></h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Número:</b> </h5>
                        </div>
                        <div class="col-md-6 col-12">
                                <h5><b>Complemento:</b> </h5>
                        </div> --}}

                </div>

        </div>
        <div class="card-footer">
                <a href="/empresa/" class="btn btn-sm btn-primary" role="button">Voltar</a>
        </div>
</div>

@endsection