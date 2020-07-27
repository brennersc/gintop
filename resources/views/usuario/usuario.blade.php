<?php

use App\Empresa;
use App\User;

if(Auth::user()->id_empresa == 1){
        $empresa = Empresa::all();
}else{
        $empresa = Empresa::where('id','<>', 1)->where('ativo', true)->get();
}

?>
@extends('layout.app', ["current" => "usuario"])

@section('body')

<div class="accordion" id="accordionExample">
        <div class="card  border-primary">
                <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h5>Informações usuário logado</h5>
                                </button>
                        </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-group">
                                <div class="card">
                                        <h4 class="card-header bg-light">Usuário logado - {{Auth::user()->name}}</h4>
                                        <div class="card-body">
                                                <h5><b>Nome</b> - {{Auth::user()->name}}</h5><br>
                                                <h5><b>Email</b> - {{Auth::user()->email}}</h5><br>
                                                <h5><b>Cargo</b> -
                                                        @switch(Auth::user()->cargo)
                                                        @case(0)
                                                        Administrador
                                                        @break
                                                        @case(1)
                                                        Empresa
                                                        @break
                                                        @endswitch
                                                </h5><br>
                                                <h5><b>Empresa</b> - {{Auth::user()->empresa}}</h5><br>

                                        </div>
                                </div>
                                <div class="card">
                                        <h4 class="card-header bg-light">Alterar senha</h4>
                                        <div class="card-body">
                                                <form class="form-horizontal" id="trocarsenha">
                                                        <input type="hidden" id="user" value="{{Auth::user()->id}}"
                                                                name="id">
                                                        <div class="col-md-12 mb-12">
                                                                <label for="senha">Senha</label>
                                                                <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                                <span class="input-group-text"
                                                                                        id="inputGroupPrepend2"><i
                                                                                                class="fas fa-lock"></i></span>
                                                                        </div>
                                                                        <input type="password" class="form-control"
                                                                                id="senha" placeholder="Senha"
                                                                                aria-describedby="inputGroupPrepend2"
                                                                                required>
                                                                        <div id="error" class="invalid-feedback">Senhas
                                                                                diferentes!</div>
                                                                </div>
                                                        </div>
                                                        <br>
                                                        <div class="col-md-12 mb-12">
                                                                <label for="Confirmasenha">Confirma senha</label>
                                                                <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                                <span class="input-group-text"
                                                                                        id="inputGroupPrepend2"><i
                                                                                                class="fas fa-lock"></i></span>
                                                                        </div>
                                                                        <input type="password" class="form-control"
                                                                                id="confirmasenha"
                                                                                placeholder="Confirma Senha"
                                                                                aria-describedby="inputGroupPrepend2"
                                                                                required>
                                                                </div>
                                                        </div>
                                        </div>
                                        <div class="card-footer">
                                                <button type='submit' class="btn btn-sm btn-success float-right"
                                                        role="button">Trocar</button>
                                        </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
        <div class="card border-primary">

        </div>
</div>

<div id='load' class="alert alert-warnig alert-dismissible fade show" role="alert" style="margin-top: 20px; display: none;">
        <center>
                <img src="/storage/imagens/load.gif" alt="load" height="40px" width="40px" >
                <h3 style="color: #ccc"> Aguarde ...<h3>
        </center>
</div>
<br>
<div class="card border border-dark">
        <div class="card-header">
                <div class="row">
                        <div class="col-md-7 col-sm-12">
                                <h3>Cadastro de usuários</h3>
                        </div>
                        <div class="col-md-5 col-sm-12">
                                <form class="form-control-sm" id="form-procurarusuario" method="POST" role="search"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="input-group input-group-sm">
                                                <input type="hidden">
                                                <input name="busca" id="procurar" class="form-control"
                                                        placeholder="Busque pela Usuario...">
                                                <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-search"></i>
                                                        </div>
                                                </div>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
        <div class="card-body table-responsive">
                <div id="retornar" class="procurar"></div>
                <table class="table table-ordered table-hover" id="tabelaUsuario">
                        <thead>
                                <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Cargo</th>
                                        <th>Empresa</th>
                                        <th>Ações</th>
                                </tr>
                        </thead>
                        <tbody>

                        </tbody>
                </table>

        </div>
        <div class="card-footer">
                {{-- {{ $usuario->links()}} --}}
                <button class="btn btn-sm btn-primary" role="button" onClick="NovoUsuario()">Novo Usuário</a>
        </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="dlgUsuario">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                        <form class="form-horizontal" id="formUsuario">
                                <div class="modal-header">
                                        <h5 class="modal-title">Novo Usuário</h5>
                                </div>
                                <div class="modal-body">
                                        <input type="hidden" id="id" class="form-control">
                                        <div class="form-group">
                                                <label for="nome" class="control-label">Nome *</label>
                                                <div class="input-group">
                                                        <input type="text" class="form-control" id="nome"
                                                                placeholder="Nome do Usuário" required>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="email" class="control-label">Email *</label>
                                                <div class="input-group">
                                                        <input type="email" class="form-control" id="email"
                                                                placeholder="Email" onblur=validaremail() required>
                                                        <div id="existe" class="invalid-feedback">Email já existente!
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="cargo">Cargo</label>
                                                <select class="form-control" id="cargo" required>
                                                        {{-- <option value="0" class="adm">Administrador</option> --}}
                                                        <option value="1" class="empresa" selected>Empresa</option>
                                                        @if(Auth::user()->id_empresa == 1)
                                                                <option value="0" class="empresa2">Administrador</option>
                                                        @endif
                                                </select>
                                        </div>
                                        <div class="form-group empresas">
                                                <label for="empresas" class="control-label ">Empresa</label>
                                                <select class="form-control" id="empresas" required>
                                                        @foreach ($empresa as $empre)
                                                        @if(Auth::user()->id_empresa == 1)
                                                                <option value="{{$empre->id}}" selected>
                                                                        {{$empre->nome_fantasia}}
                                                                </option>
                                                        @elseif($empre->id == Auth::user()->id_empresa)
                                                                <option value="{{$empre->id}}" selected>
                                                                        {{$empre->nome_fantasia}}
                                                                </option>
                                                        @endif
                                                        @endforeach
                                                </select>
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

<div class="modal fade" id="remover" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado"
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

                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                </div>
        </div>
</div>

@endsection

@section('javascript')
<script type="text/javascript">
        // $("select").change(function() {
        //         if (($('.empresa2').prop("selected") == true)) {
        //             $('#empresas').prop("selected").val(1);
        //         }
        //  });


        $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
        });

        function NovoUsuario() {
                $('#id').val('');
                $('#nome').val('');
                $('#email').val('').removeClass("is-invalid").removeClass("is-valid");
                $('#cargo').val('');
                $('#empresas').val('');
                $('#dlgUsuario').modal('show');
        }

        function montarLinhaNovo(usuario) {
                $('#load').hide();
                switch (usuario.cargo) {
                        case '0':
                                usuario.cargo = 'Administrador';
                                break;
                        case '1':
                                usuario.cargo = 'Empresa';
                                break;
                }

                var linha = "<tr>" +
                                "<td>" + usuario.id + "</td>" +
                                "<td>" + usuario.name + "</td>" +
                                "<td>" + usuario.email + "</td>" +
                                "<td>" + usuario.cargo + "</td>" +
                                "<td><a href=/infoempresa/" + usuario.id_empresa + ">"+ usuario.empresa + "</a></td>" +
                                "<td>" +
                                        '<div class="btn-group" role="group" aria-label="Basic example">'+
                                        '<button class="btn btn-sm btn-primary" onclick="editar(' + usuario.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
                                        '<button class="btn btn-sm btn-danger" onclick="modalremover(' + usuario.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
                                        '</div>'+
                                "</td>" +
                        "</tr>";
                return linha;
        }

        function montarLinha(usuario) {
                console.log(usuario);
                switch (usuario.cargo) {
                        case '0':
                                usuario.cargo = 'Administrador';
                                break;
                        case '1':
                                usuario.cargo = 'Empresa';
                                break;
                }

                if({{Auth::user()->id_empresa}} == 1){
                        if(usuario.ativo == 1){                   
                                var linha = "<tr>" +
                                        "<td>" + usuario.id + "</td>" +
                                        "<td>" + usuario.name + "</td>" +
                                        "<td>" + usuario.email + "</td>" +
                                        "<td>" + usuario.cargo + "</td>" +
                                        "<td><a href=/infoempresa/" + usuario.id_empresa + ">"+ usuario.empresa + "</a></td>" +
                                        "<td>" +
                                                '<div class="btn-group" role="group" aria-label="Basic example">'+
                                                '<button class="btn btn-sm btn-primary" onclick="editar(' + usuario.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
                                                '<button class="btn btn-sm btn-danger" onclick="modalremover(' + usuario.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
                                                '</div>'+
                                        "</td>" +
                                "</tr>";
                        }else if(usuario.ativo == 0){ 
                                var linha = "<tr class='table-secondary'>" +
                                        "<td>" + usuario.id + "</td>" +
                                        "<td>" + usuario.name + "</td>" +
                                        "<td>" + usuario.email + "</td>" +
                                        "<td>" + usuario.cargo + "</td>" +
                                        "<td><a href=/infoempresa/" + usuario.id_empresa + ">"+ usuario.empresa + "</a></td>" +
                                        "<td>" +
                                                '<div class="btn-group" role="group" aria-label="Basic example">'+
                                                        '<button class="btn btn-sm btn btn-warning" onclick="ativar(' + usuario.id + ')"><i class="fas fa-trash-restore"></i> Reativar Usuário </button> ' +                                       
                                                '</div>'+
                                        "</td>" +
                                "</tr>";
                        }
                }else if(usuario.id_empresa == {{Auth::user()->id_empresa}}){
                        if(usuario.ativo == 1){                   
                                var linha = "<tr>" +
                                        "<td>" + usuario.id + "</td>" +
                                        "<td>" + usuario.name + "</td>" +
                                        "<td>" + usuario.email + "</td>" +
                                        "<td>" + usuario.cargo + "</td>" +
                                        "<td><a href=/infoempresa/" + usuario.id_empresa + ">"+ usuario.empresa + "</a></td>" +
                                        "<td>" +
                                                '<div class="btn-group" role="group" aria-label="Basic example">'+
                                                '<button class="btn btn-sm btn-primary" onclick="editar(' + usuario.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
                                                '<button class="btn btn-sm btn-danger" onclick="modalremover(' + usuario.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
                                                '</div>'+
                                        "</td>" +
                                "</tr>";
                        }else if(usuario.ativo == 0){ 
                                var linha = "<tr class='table-secondary'>" +
                                        "<td>" + usuario.id + "</td>" +
                                        "<td>" + usuario.name + "</td>" +
                                        "<td>" + usuario.email + "</td>" +
                                        "<td>" + usuario.cargo + "</td>" +
                                        "<td><a href=/infoempresa/" + usuario.id_empresa + ">"+ usuario.empresa + "</a></td>" +
                                        "<td>" +
                                                '<div class="btn-group" role="group" aria-label="Basic example">'+
                                                        '<button class="btn btn-sm btn btn-warning" onclick="ativar(' + usuario.id + ')"><i class="fas fa-trash-restore"></i> Reativar Usuário </button> ' +                                       
                                                '</div>'+
                                        "</td>" +
                                "</tr>";
                                }
                }
                return linha;
        }

        function editar(id) {
                $.getJSON('/api/usuario/' + id, function(data) {
                        console.log(data);
                        $('#id').val(data.id);
                        $('#nome').val(data.name);
                        $('#email').val(data.email);
                        $('#cargo').val(data.cargo);
                        $('#empresas').val(data.id_empresa);
                        $('#dlgUsuario').modal('show');
                        montarLinha();
                });
                
                //montarLinha();
        }
        function modalremover(id) {

        $('#linha').remove();
        $exlinha = 0;
        $.getJSON('/api/usuario/' + id, function(data) {
                console.log(data);
                id = data.id;
                nome = data.name;
                exlinha = 
                '<div class="row" id="linha">'+
                        '<div class="col-9">'+
                                '<strong> Tem certeza que deseja excluir o usuário: ' + nome  + '?</strong>'+
                        '</div>'+
                        '<div class="col-3">'+
                                '<button id="apagar" class="btn btn-md btn-danger" onclick="remover(' + id + ')">  Apagar </button>'+
                        '</div>'+
                '</div>';

                // return exlinha;
                $('#excluir').append(exlinha);
                $('#remover').modal('show');
        });
        }

        function remover(id) {
                $('#load').show();
                $('#linha').remove();
                $('#remover').modal('hide');
                $.ajax({
                        type: "DELETE",
                        url: "/api/usuario/" + id,
                        context: this,
                        success: function() {
                                console.log('Apagou OK');
                                linhas = $("#tabelaUsuario>tbody>tr");
                                e = linhas.filter(function(i, elemento) {
                                        return elemento.cells[0].textContent == id;
                                });
                                if (e)
                                window.location.href = 'usuario';
                        },
                        error: function(error) {
                                console.log(error);
                        }
                });
        }
        function ativar(id) {
                $('#load').show();
                $.ajax({
                        type: "DELETE",
                        url: "/api/usuario/" + id,
                        context: this,
                        success: function() {
                                console.log('Ativou OK');
                                window.location.href = 'usuario';
                        },
                        error: function(error) {
                                console.log(error);
                        }
                });
        }

        function carregarusuario() {
                $.getJSON('/api/usuario', function(usuario) {
                        for (i = 0; i < usuario.length; i++) {
                                linha = montarLinha(usuario[i]);
                                $('#tabelaUsuario>tbody').append(linha);
                        }
                });
        }

        function criarusuario() {
                usuario = {
                        nome: $("#nome").val(),
                        email: $("#email").val(),
                        cargo: $("#cargo").val(),
                        empresas: $("#empresas").val()
                };
                $('#load').show();
                $.post("/api/usuario", usuario, function(data) {
                        usuario = JSON.parse(data);
                        console.log(data);
                        console.log(usuario);
                        linha = montarLinhaNovo(usuario);
                        $('#tabelaUsuario>tbody').append(linha);
                });

                //window.location.href = 'usuario';
        }

        function salvarusuario() {
                usuario = {
                        id: $("#id").val(),
                        nome: $("#nome").val(),
                        email: $("#email").val(),
                        cargo: $("#cargo").val(),
                        empresas: $("#empresas").val()
                };
                $.ajax({
                        type: "PUT",
                        url: "/api/usuario/" + usuario.id,
                        context: this,
                        data: usuario,
                        success: function(data) {
                                usuario = JSON.parse(data);
                                linhas = $("#tabelaUsuario>tbody>tr");
                                e = linhas.filter(function(i, e) {
                                        return (e.cells[0].textContent == usuario.id);
                                });
                                if (e) {
                                        e[0].cells[0].textContent = usuario.id;
                                        e[0].cells[1].textContent = usuario.name;
                                        e[0].cells[2].textContent = usuario.email;
                                        switch (usuario.cargo) {
                                                case '0':
                                                        usuario.cargo = 'Administrador';
                                                break;
                                                case '1':
                                                        usuario.cargo = 'Empresa';
                                                break;
                                        }
                                        e[0].cells[3].textContent = usuario.cargo;

                                        e[0].cells[4].textContent = usuario.empresa;

                                }
                        },
                        error: function(error) {
                                console.log(error);
                        }
                });
        }

        $("#formUsuario").submit(function(event) {
                event.preventDefault();
                if ($("#id").val() != '')
                        salvarusuario();
                        
                else
                        criarusuario();

                $("#dlgUsuario").modal('hide');
        });

        $(function() {
                carregarusuario();
        });

        function validaremail() {
                if ($('#email').val().length > 0) {
                        $.ajax({
                                type: 'POST',
                                url: 'email',
                                data: {
                                email: $('#email').val()
                                },
                                dataType: 'JSON',
                                success: function(data) {
                                        if (data.sucesso == 1) {
                                                //alert(data.mensagem);
                                                $('#email').addClass("is-invalid");
                                                $("#email").focus();
                                                document.getElementById('email').value = '';
                                        } 
                                        if (data.sucesso == 0) {
                                                $('#email').removeClass("is-invalid").addClass("is-valid");
                                                $('#existente').hide();
                                        }
                                }
                        });
                }
        }

    $("#trocarsenha").on("submit", function() {
        console.clear();
        $.ajax({
            type: 'post',
            url: 'senha',
            data: {
                id:             $('#user').val(),
                senha:          $('#senha').val(),
                confirmasenha:  $('#confirmasenha').val(),
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.sucesso == 0) {
                    //alert('sucesso');
                    $('#senha').addClass("is-valid").val('');
                    $('#confirmasenha').addClass("is-valid").val('');

                    setTimeout(function() {
                        $('#senha').removeClass("is-valid").val('');
                        $('#confirmasenha').removeClass("is-valid").val('');
                    }, 3000);
                }
                if (data.sucesso == 1) {
                    //alert('igual');
                    $('#senha').addClass("is-invalid").val('');
                    $('#confirmasenha').addClass("is-invalid").val('');
                    $('#error').show();

                    setTimeout(function() {
                        $('#senha').removeClass("is-invalid").val('');
                        $('#confirmasenha').removeClass("is-invalid").val('');
                        $('#error').hide();
                    }, 3000);
                }
            }
        });
        return false;
    });

        //BUSCAR
        $(document).ready(function() {
                $("#procurar").keyup(function() {
                        $('#retornar').html('');
                        if ($('#procurar').val().length > 0) {
                                $.ajax({
                                        url: '/procurarusuario',
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

</script>
@endsection