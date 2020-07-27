@extends('layout.app', ["current" => "home"])

@section('body')

<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary col-md-12">
                <div class="card-body">
                    <h5 class="card-title">Gin Top</h5>
                    <p class="card=text">
                        Uma plataforma de Credenciamento Online e Local, onde o usuário possa customizar um 
                        formulário com os campos exigidos para o credenciamento de Eventos!
                    </P>
                    <P>
                        Desenvolvido pela <a href="http://toptecnologia.com/" target="_blank">TopTecnologia</a>
                    </p>
                    <!-- <a href="/empresa" class="btn btn-primary">Cadastrar Empresa</a> -->
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5 class="card-title">Cadastro Empresas</h5>
                    <p class="card=text">
                        Cadastre empresas no sistema GinTop.
                    </p>
                    <a href="/empresa" class="btn btn-primary">Cadastrar Empresa</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5 class="card-title">Cadastre Usuários</h5>
                    <p class="card=text">
                        Cadastre os usuarios ao sistema!
                    </p>
                    <a href="/usuario" class="btn btn-primary">Cadastre Usuários</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection