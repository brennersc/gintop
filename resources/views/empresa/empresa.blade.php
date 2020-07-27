<?php
use App\User;
?>
@extends ('layout.app', ["current" => "empresa"]) @section('body')

<div id='load' class="alert alert-warnig alert-dismissible fade show" role="alert" style="margin-top: 20px; display: none;">
    <center>
            <img src="/storage/imagens/load.gif" alt="load" height="40px" width="40px" >
            <h3 style="color: #ccc"> Aguarde ...<h3>
    </center>
</div>

<div class="card border">
    <div class="card-header">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <h3>Cadastro de empresas</h3>
            </div>
            <div class="col-md-5 col-sm-12">
                <form class="form-control-sm" id="form-procurarempresa" method="POST" role="search"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="input-group input-group-sm">
                        <input type="hidden">
                        <input name="busca" id="procurar" class="form-control" placeholder="Busque pela Empresa...">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-search"></i></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body table-responsive">

        <div id="retornar" class="procurar"></div>

        <table class="table table-ordered table-hover" id="tabelaempresa">
            <thead>
                <tr>                    
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Razão Social</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
    <div class="card-footer">
        <button class="btn btn-sm btn-primary" role="button" onClick="NovaEmpresa()">Nova empresa</a>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="dlgEmpresa">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formempresa">
                <div class="modal-header">
                    <h5 class="modal-title">Nova empresa</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" class="form-control">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="razao_social" class="control-label">Razão Social *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="razao_social" placeholder="Razão Social"
                                    required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="nome_fantasia" class="control-label">Nome de Fantasia *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nome_fantasia" placeholder="Nome da Empresa"
                                    required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="cnpj" class="control-label">CNPJ *</label>
                            <div class="input-group">
                                <input type="cnpj" id='cnpj' class="form-control cnpj" id="cnpj" placeholder="CNPJ"
                                    onblur=validarcnpj() required>
                                <div id="invalido" class="invalid-feedback">CNPJ inválido!</div>
                                <div id="existe" class="invalid-feedback">CNPJ já existente!</div>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="responsavel" class="control-label">Nome do Responsável *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="responsavel"
                                    placeholder="Nome da Responsável" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="telefone" class="control-label ">Telefone Fixo * </label>
                            <div class="input-group">
                                <input type="text" class="form-control phone" id="telefone"
                                    placeholder="(xx) xxxxx-xxxx" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="celular" class="control-label ">Celular *</label>
                            <div class="input-group">
                                <input type="text" class="form-control phone" id="celular" placeholder="(xx) xxxxx-xxxx"
                                    required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="email" class="control-label">Email *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="email" placeholder="Email" required>
                                <div id="existe" class="invalid-feedback">Email já existente!</div>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="site" class="control-label">Site *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="site" placeholder="Site" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="cep" class="control-label ">CEP *</label>
                            <div class="input-group">
                                <input type="text" class="form-control cep" onblur="pesquisacep(this.value);" id="cep"
                                    placeholder="xxxxx-xxx" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="rua" class="control-label">Rua,Av *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="rua" placeholder="Rua" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="numero" class="control-label">Nº *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="numero" placeholder="Nº" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="complemento" class="control-label">Complemento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="complemento" placeholder="Complemento">
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="bairro" class="control-label">Bairro *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="bairro" placeholder="Bairro" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="cidade" class="control-label">Cidade *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="cidade" placeholder="Cidade" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="estado" class="control-label">Estado *</label>
                            <div class="input-group">
                                <select class="form-control" id="estado" name="estado" placeholder="Estado" required>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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

@endsection @section('javascript')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    function NovaEmpresa() {
        $('#id').val('');
        $('#nome_fantasia').val('');
        $('#cnpj').val('').removeClass("is-invalid").removeClass("is-valid");
        $('#razao_social').val('');  
        $('#responsavel').val('');
        $('#telefone').val('');
        $('#celular').val('');
        $('#email').val('').removeClass("is-invalid").removeClass("is-valid");
        $('#site').val('');
        $('#cep').val('');
        $('#estado').val('');
        $('#cidade').val('');
        $('#bairro').val('');
        $('#rua').val('');
        $('#numero').val('');
        $('#complemento').val('');           
        $('#dlgEmpresa').modal('show');
    }

    function montarLinha(empresa) {

    //var cnpj = empresa.cnpj;
        //var cnpj = (cnpj).mask("99.999.999/9999-99");;

        // var linha = "<tr>" +                
        //                 "<td>" + empresa.nome_fantasia + "</td>" +
        //                 "<td class='cnpj'>" + empresa.cnpj + "</td>" +
        //                 "<td>" + empresa.razao_social + "</td>" +
        //                 "<td>" +
        //                     '<div class="btn-group" role="group" aria-label="Basic example">'+
        //                         '<button class="btn btn-sm btn-primary" onclick="editar(' + empresa.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
        //                         '<button class="btn btn-sm btn-danger" onclick="modalremover(' + empresa.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
        //                         '<a class="btn btn-sm btn-success"  href="/infoempresa/'+ empresa.id +'"><i class="fas fa-info-circle"></i> Info </a> ' +            
        //                     '</div>' +
        //                 "</td>" +
        //         "</tr>";

        if({{ Auth::user()->id_empresa }} == 1){

            if (empresa.ativo == 1){  //  --------------- empresas ativadas ---------------
                var linha = "<tr>" +                
                "<td>" + empresa.nome_fantasia + "</td>" +
                "<td class='cnpj'>" +  empresa.cnpj + "</td>" +
                "<td>" + empresa.razao_social + "</td>" +
                "<td>" +
                '<div class="btn-group" role="group" aria-label="Basic example">'+
                '<button class="btn btn-sm btn-primary" onclick="editar(' + empresa.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
                '<button class="btn btn-sm btn-danger" onclick="modalremover(' + empresa.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
                '<a class="btn btn-sm btn-success"  href="/infoempresa/'+ empresa.id +'"><i class="fas fa-info-circle"></i> Info </a> ' +            
                '</div>' +
                "</td>" +
                "</tr>";
            }else if(empresa.ativo == 0){
                //  --------------- empresas desativadas ---------------
                var linha = "<tr class='table-secondary'>" +                   
                    "<td >" + empresa.nome_fantasia + "</td>" +
                    "<td class='cnpj'>" +  empresa.cnpj + "</td>" +
                    "<td >" + empresa.razao_social + "</td>" +
                    "<td>" +
                    '<div class="btn-group" style="width: 195.8px" role="group" aria-label="Basic example">'+
                    '<button class="btn btn-sm btn btn-warning" onclick="ativar(' + empresa.id + ')"><i class="fas fa-trash-restore"></i> Reativar Empresa </button> ' +
                    '<a class="btn btn-sm btn-success"  href="/infoempresa/'+ empresa.id +'"><i class="fas fa-info-circle"></i> Info </a> ' +            
                    '</div>' +
                    "</td>" +
                    "</tr>";
            }       
        }else if(empresa.email == '{{Auth::user()->email}}'){
            if (empresa.ativo == 1){  //  --------------- empresas ativadas ---------------
                var linha = "<tr>" +                
                "<td>" + empresa.nome_fantasia + "</td>" +
                "<td class='cnpj'>" +  empresa.cnpj + "</td>" +
                "<td>" + empresa.razao_social + "</td>" +
                "<td>" +
                '<div class="btn-group" role="group" aria-label="Basic example">'+
                '<button class="btn btn-sm btn-primary" onclick="editar(' + empresa.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
                '<button class="btn btn-sm btn-danger" onclick="modalremover(' + empresa.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
                '<a class="btn btn-sm btn-success"  href="/infoempresa/'+ empresa.id +'"><i class="fas fa-info-circle"></i> Info </a> ' +            
                '</div>' +
                "</td>" +
                "</tr>";
            }else if(empresa.ativo == 0){
                //  --------------- empresas desativadas ---------------
                var linha = "<tr class='table-secondary'>" +                    
                    "<td >" + empresa.nome_fantasia + "</td>" +
                    "<td class='cnpj'>" +  empresa.cnpj + "</td>" +
                    "<td >" + empresa.razao_social + "</td>" +
                    "<td>" +
                    '<div class="btn-group" style="width: 195.8px" role="group" aria-label="Basic example">'+
                    '<button class="btn btn-sm btn btn-warning" onclick="ativar(' + empresa.id + ')"><i class="fas fa-trash-restore"></i> Reativar Empresa </button> ' +
                    '<a class="btn btn-sm btn-success"  href="/infoempresa/'+ empresa.id +'"><i class="fas fa-info-circle"></i> Info </a> ' +            
                    '</div>' +
                    "</td>" +
                    "</tr>";
            }
         }       
        return linha;
    }
    function montarLinhaNova(empresa) {
            $('#load').hide();
            var linha = "<tr>" +                
                        "<td>" + empresa.nome_fantasia + "</td>" +
                        "<td class='cnpj'>" + empresa.cnpj + "</td>" +
                        "<td>" + empresa.razao_social + "</td>" +
                        "<td>" +
                            '<div class="btn-group" role="group" aria-label="Basic example">'+
                                '<button class="btn btn-sm btn-primary" onclick="editar(' + empresa.id + ')"><i class="far fa-edit"></i> Editar </button> ' +
                                '<button class="btn btn-sm btn-danger" onclick="modalremover(' + empresa.id + ')"><i class="far fa-trash-alt"></i> Apagar </button> ' +
                                '<a class="btn btn-sm btn-success"  href="/infoempresa/'+ empresa.id +'"><i class="fas fa-info-circle"></i> Info </a> ' +            
                            '</div>' +
                        "</td>" +
                "</tr>";
        return linha;

    }
    function editar(id) {
        $.getJSON('/api/empresa/' + id, function(data) {
            console.log(data);
            $('#id').val(data.id);
            $('#nome_fantasia').val(data.nome_fantasia);
            $('#cnpj').val(data.cnpj).mask("99.999.999/9999-99");
            $('#razao_social').val(data.razao_social);         
            $('#responsavel').val(data.responsavel);
            $('#telefone').val(data.telefone);
            $('#celular').val(data.celular);
            $('#email').val(data.email);
            $('#site').val(data.site);
            $('#cep').val(data.cep);
            $('#estado').val(data.estado).prop("selected", true);
            $('#cidade').val(data.cidade);
            $('#bairro').val(data.bairro);
            $('#rua').val(data.rua);
            $('#numero').val(data.numero);
            $('#complemento').val(data.complemento);
            $('#dlgEmpresa').modal('show');
        });
    }

    function modalremover(id) {

        $('#linha').remove();
        $exlinha = 0;
        $.getJSON('/api/empresa/' + id, function(data) {
            console.log(data);
            id = data.id;
            nome = data.nome_fantasia;

  
            exlinha = 
                '<div class="row" id="linha">'+
                    '<div class="col-9">'+
                        '<h5> Tem certeza que deseja excluir a empresa ' + nome  + '?</h5>'+
                    '</div>'+
                    '<div class="col-3">'+
                        '<button id="apagar" class="btn btn-md btn-danger" onclick="remover(' + id + ')"> <i class="far fa-trash-alt"></i> Apagar </button>'+
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
            url: "/api/empresa/" + id,
            context: this,
            success: function() {
                console.log('Apagou OK');
                linhas = $("#tabelaempresa>tbody>tr");
                e = linhas.filter(function(i, elemento) {
                    return elemento.cells[0].textContent == id;
                });
                if (e)
                    //e.remove();
                    window.location.href = 'empresa';

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
            url: "/api/empresa/" + id,
            context: this,
            success: function() {
                console.log('ativou OK');
                //e.remove();
                window.location.href = 'empresa';
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function carregarempresa() {
        $.getJSON('/api/empresa', function(empresa) {
            for (i = 0; i < empresa.length; i++) {
                linha = montarLinha(empresa[i]);
                $('#tabelaempresa>tbody').append(linha);
            }
        });
    }

    function criarempresa() {
        empresa = {
            nome_fantasia:  $("#nome_fantasia").val(),
            cnpj:           $("#cnpj").val(),
            razao_social:   $("#razao_social").val(),
            responsavel:    $("#responsavel").val(),
            telefone:       $("#telefone").val(),
            celular:        $("#celular").val(),
            email:          $("#email").val(),
            site:           $("#site").val(),
            cep:            $("#cep").val(),
            estado:         $("#estado").val(),
            cidade:         $("#cidade").val(),
            bairro:         $("#bairro").val(),
            rua:            $("#rua").val(),
            numero:         $("#numero").val(),
            complemento:    $("#complemento").val()
        };
        $('#load').show();
        $.post("/api/empresa", empresa, function(data) {
            console.log(data);
            empresa = JSON.parse(data);
            linha = montarLinhaNova(empresa);
            console.log(empresa);
            console.log(linha);
            $('#tabelaempresa>tbody').append(linha);
        });
        //window.location.href = 'empresa';
    }

    function salvarempresa() {
        empresa = {
            id:             $("#id").val(),
            nome_fantasia:  $("#nome_fantasia").val(),
            cnpj:           $("#cnpj").val(),
            razao_social:   $("#razao_social").val(),
            responsavel:    $("#responsavel").val(),
            telefone:       $("#telefone").val(),
            celular:        $("#celular").val(),
            email:          $("#email").val(),
            site:           $("#site").val(),
            cep:            $("#cep").val(),
            estado:         $("#estado").val(),
            cidade:         $("#cidade").val(),
            bairro:         $("#bairro").val(),
            rua:            $("#rua").val(),
            numero:         $("#numero").val(),
            complemento:    $("#complemento").val()
        };
        $.ajax({
            type: "PUT",
            url: "/api/empresa/" + empresa.id,
            context: this,
            data: empresa,
            success: function(data) {
                empresa = JSON.parse(data);
                linhas = $("#tabelaempresa>tbody>tr");
                e = linhas.filter(function(i, e) {
                    return (e.cells[0].textContent == empresa.id);
                });
                if (e) {
                    e[0].cells[0].textContent = empresa.id;
                    e[0].cells[1].textContent = empresa.nome_fantasia;
                    e[0].cells[2].textContent = empresa.cnpj;
                    e[0].cells[3].textContent = empresa.razao_social;

                }
            },
            error: function(error) {
                console.log(error);
                erros();
            }
        });
    }

    $("#formempresa").submit(function(event) {
        event.preventDefault();
        if ($("#id").val() != '')
            salvarempresa();
        else
            criarempresa();
        $("#dlgEmpresa").modal('hide');
    });

    $(function() {
        carregarempresa();
    })

    function validarcnpj() {

        if ($('#cnpj').val().length > 0) {
            $.ajax({
                type: 'POST',
                url: 'cnpj',
                data: {
                    cnpj: $('#cnpj').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.sucesso == 1) {
                        //alert(data.mensagem);
                        $('#cnpj').addClass("is-invalid");
                        $("#cnpj").focus();
                        $('#invalido').hide();
                        $('#existe').show();                        
                        document.getElementById('cnpj').value = '';
                    } 
                    if (data.sucesso == 2) {
                        //alert(data.mensagem);
                        $('#cnpj').addClass("is-invalid");
                        $("#cnpj").focus();
                        $('#existe').hide();
                        $('#invalido').show();
                        document.getElementById('cnpj').value = '';
                    } 
                    if (data.sucesso == 0) {
                        $('#cnpj').removeClass("is-invalid").addClass("is-valid");                        
                        $('#existe').hide();
                        $('#invalido').hide();
                    }
                }
            });
        }
    }

    // function validaremail() {
    //             if ($('#email').val().length > 0) {
    //                     $.ajax({
    //                             type: 'POST',
    //                             url: 'emailempresa',
    //                             data: {
    //                             email: $('#email').val()
    //                             },
    //                             dataType: 'JSON',
    //                             success: function(data) {
    //                                     if (data.sucesso == 1) {
    //                                             //alert(data.mensagem);
    //                                             $('#email').addClass("is-invalid");
    //                                             $("#email").focus();
    //                                             document.getElementById('email').value = '';
    //                                     } 
    //                                     if (data.sucesso == 0) {
    //                                             $('#email').removeClass("is-invalid").addClass("is-valid");
    //                                             $('#existente').hide();
    //                                     }
    //                             }
    //                     });
    //             }
    //     }
    
    //Buscar
    $(document).ready(function() {
        $("#procurar").keyup(function() {
            $('#retornar').html('');
            if ($('#procurar').val().length > 0) {
                $.ajax({
                    url: '/procurarempresa',
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

    function erros() {
        $("#erro").show();
        setTimeout(function() {
            $("#erro").show();
        }, 3000);
    }
</script>
@endsection