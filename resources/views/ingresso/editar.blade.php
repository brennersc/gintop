@extends('layout.app', ["current" => "evento"])

@section('body')

<style>

</style>
@php

$data_inicio_evento = date("d/m/Y", strtotime($evento->data_inicio));
$data_fim_evento = date("d/m/Y", strtotime($evento->data_fim));

$data_inicio_ingresso = date("d/m/Y", strtotime($edit->data_inicio));
$data_fim_ingresso    = date("d/m/Y", strtotime($edit->data_fim));

@endphp
<div class="card border">
    <div class="card-header">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                <h3>Edite <b> {{$edit->nome}}</b>  para o Evento <b> {{$evento->nome}} </b></h3>
            </div>
            <div class="col-md-3 col-sm-9">
                <a href="/ingresso/{{$evento->id}}" class="btn btn-md btn-outline-primary float-right" title="Novo Ingressos"><i class="fas fa-ticket-alt"></i> Novo Ingresso</a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-12">
                    <b>Data Inicial:</b> {{ $data_inicio_evento}}
            </div>
            <div class="col-md-6 col-12">
                    <b>Data Final:</b> {{ $data_fim_evento }}
            </div>
            <div class="col-md-6 col-12">
                    <b>Hora Inicial:</b> {{$evento->hora_inicio}}
            </div>
            <div class="col-md-6 col-12">
                    <b>Hora Final:</b> {{$evento->hora_fim}}
            </div>
            <div class="col-md-6 col-12">
                    <b>Local:</b> {{$evento->nome_local}}
            </div>
            <div class="col-md-6 col-12">
                    <b>Endereço:</b> {{$evento->endereco_local}}
            </div>
        </div>
    </div>

    @if(count($ingressos) > 0)
    <div class="card-header">
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
                            <button id="{{ $ingresso->id }}" name="remover" class="btn btn-sm button btn-danger fas fa-ban" onclick="({{ $ingresso->id }})" title="Remover Ingresso">
                            </button>
                        @else
                            <button id="{{ $ingresso->id }}" name="adicionar" class="btn btn-sm button btn-success  fas fa-check-circle" onclick="({{ $ingresso->id }})" title="Adicionar Ingresso">                            
                            </button>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div class="btn-group" role="group" aria-label="Basic example ">
                            <a href="{{ route('editaringresso', ['id' => $ingresso->id, 'id_evento' => $evento->id ]) }}" class="btn btn-sm btn-primary" title="editar ingresso">
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
                                                                <a href="{{route('apagaringresso', ['id' => $ingresso->id, 'id_evento' => $evento->id ])}}" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Apagar</a>
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
    </div>
    @endif

    <div class="card-body">
        <h4>Editar Ingresso {{$edit->nome}}</h4>
        <form action="{{route('atualizaringresso', ['id' => $edit->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_evento" value="{{$id}}">
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="nome">Nome do Ingresso *</label>
                <input type="text" class="form-control valor" name="nome" id="nome" placeholder="Ingresso único, meia-entrada, VIP ..." value="{{$edit->nome}}" custo="custo" required>
                    @if($errors->has('nome')) <div class="invalid-feedback">{{$errors->first('nome')}}</div>@endif
                </div>
                <div class="col-12 col-md-2 form-group">
                    <label for="qntd">Quantidade *</label>
                    <input type="text" class="form-control" name="qntd" id="qntd" placeholder="EX. 100" value="{{$edit->qntd}}" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('qntd')) <div class="invalid-feedback">{{$errors->first('qntd')}}
                    </div>@endif
                </div>
                <div class="col-12 col-md-2 form-group">
                    <label for="preco">Preço *</label>
                    <input type="text" class="form-control money din" name="preco" value="{{$edit->preco}}" id="preco" placeholder="R$" onkeyup="calcular()" meia='meia' required>
                    <small class="text-muted">Somente números</small>                    
                    @if($errors->has('preco')) <div class="invalid-feedback">{{$errors->first('preco')}}</div>@endif                    
                </div>
                <div class="col-12 col-md-2 form-group">
                    <label for="total">Total comprador</label>
                    <input type="text" class="form-control money" name="total" value="{{$edit->total}}" id="total" placeholder="R$ 0,00" readonly>
                    <small class="text-muted">Valor do ingresso mais taxa</small>
                </div>                
            </div>

            <div class="row">
                <div class="col-12 col-md-6 form-group">                   
                    <div class="form-check ">
                        <input class="form-check-input" type="checkbox" name="meia" id="meia" value='1' @if(isset($edit->meia)) checked @endif>
                        <label for="meia" class="form-check-label">&nbsp; Criar meia-entrada para este ingreso?</label>
                    </div>
                </div>
            </div>
            <br>

            <div class="row meia-entrada"  @if(!isset($edit->meia)) style="display:none" @endif >
                <div class="col-12 col-md-6 form-group">
                    <label for="meia_entrada">Nome da Meia-entrada *</label>
                    <input type="text" class="form-control custo" name="meia_entrada" value="{{$edit->meia_entrada}}" id="meia_entrada" placeholder="Ingresso único, meia-entrada, VIP ..." readonly >
                    @if($errors->has('meia_entrada')) <div class="invalid-feedback">{{$errors->first('meia_entrada')}}</div>@endif
                </div>
                <div class="col-12 col-md-2 form-group">
                    <label for="qntd_meia_entrada">Quantidade *</label>
                    <input type="text" class="form-control " name="qntd_meia_entrada" value="{{$edit->qntd_meia_entrada}}" id="qntd_meia_entrada" placeholder="EX. 100">
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('qntd_meia_entrada')) <div class="invalid-feedback">{{$errors->first('qntd_meia_entrada')}}
                    </div>@endif
                </div>
                <div class="col-12 col-md-2 form-group">
                    <label for="preco_meia_entrada">Preço *</label>
                    <input type="text" class="form-control money meia" name="preco_meia_entrada" value="{{ $edit->preco_meia_entrada }}" id="preco_meia_entrada" placeholder="R$"  onkeyup="calcular1()" >
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('preco_meia_entrada')) <div class="invalid-feedback">{{$errors->first('preco_meia_entrada')}}</div>
                    @endif
                </div> 
                <div class="col-12 col-md-2 form-group">
                    <label for="total_meia_entrada">Total comprador</label>
                    <input type="text" class="form-control money" name="total_meia_entrada" value="{{ $edit->total_meia_entrada }}" id="total_meia_entrada" placeholder="R$ 0,00" >
                    <small class="text-muted">Valor do ingresso mais taxa</small>
                </div>                 
            </div>

            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="periodo">Período das vendas deste ingresso:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="periodo" id="periodo" value="data" @if($edit->periodo == 'data') checked @endif>
                        <label class="form-check-label" for="periodo">
                            Por data
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="periodo" id="periodo" value="lote" @if($edit->periodo == 'lote') checked @endif @if(count($ingressos) <= 0) disabled  @endif>
                        <label class="form-check-label" for="periodo">
                            Por lote
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                 
                <div class="col-12 col-md-3 form-group por-data">
                    <label for="data_ini">Data de Início *</label>
                    <input type="text" class="form-control data_ data_calendario" name="data_inicio" value="{{$data_inicio_ingresso}}" id="data_inicio" placeholder="__/__/____" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('data_inicio')) <div class="invalid-feedback">{{$errors->first('data_inicio')}}
                    </div>@endif
                </div>

                <div class="col-12 col-md-3 form-group por-data">
                    <label for="hora_inicio">Horário de Início *</label>
                    <input type="time" class="form-control" name="hora_inicio" value="{{$edit->hora_inicio}}" id="hora_inicio" placeholder="Horário de Início" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('hora_inicio')) <div class="invalid-feedback">{{$errors->first('hora_inicio')}}
                    </div>@endif
                </div>
                                   
                <div class="col-12 col-md-6 form-group por-lote" style="display:none">
                    <label for="lote_anterior" class="control-label">Início das vendas quando este ingresso esgotar *</label>
                    <select class="form-control" id="lote_anterior" name="lote_anterior" >
                        <option value=""></option>                        
                        @foreach ($ingressos as $ingresso)
                        <option value="{{$ingresso->id}}" @if($edit->id_ingresso == $ingresso->id) selected @endif>  {{$ingresso->nome}} </option>
                        @endforeach
                    </select>
                    @if($errors->has('empresa')) <div class="invalid-feedback">{{$errors->first('empresa')}}</div>@endif
                </div>
            

                <div class="col-12 col-md-3 form-group">
                    <label for="data_fim">Data de Encerramento *</label>
                    <input type="text" class="form-control data_ data_calendario" name="data_fim" value="{{$data_fim_ingresso}}" id="data_fim" placeholder="__/__/____" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('data_fim')) <div class="invalid-feedback">{{$errors->first('data_fim')}}</div>
                    @endif
                </div>

                <div class="col-12 col-md-3 form-group">
                    <label for="hora_fim">Horário de Encerramento *</label>
                    <input type="time" class="form-control"name="hora_fim" value="{{$edit->hora_fim }}" id="hora_fim" placeholder="Horário de Encerramento" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('hora_fim')) <div class="invalid-feedback">{{$errors->first('hora_fim')}}</div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="salas">Disponibilidade do ingresso:</label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="dispo" id="dispo" value="publico" @if($edit->dispo == 'publico') checked @endif>
                        <label class="form-check-label" for="dispo">
                            Para todo o público
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input convite" type="radio" name="dispo" id="dispo" value="convidados"  @if($edit->dispo == 'convidados') checked @endif>
                        <label class="form-check-label" for="dispo">
                            Restrito a convidados
                        </label>
                    </div>
                    <div class="ml-4 convidados" style="display:none">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="convidados" id="convidados" value="email"  @if($edit->convidados == 'email') checked @endif>
                            <label class="form-check-label" for="convidados">
                                Convites pessoais por e-mail
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="convidados" id="convidados" value="link" @if($edit->convidados == 'link') checked @endif>
                            <label class="form-check-label" for="convidados">
                                Link único pra compartilhar 
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <label for="salas">Disponibilidade do ingresso:</label><br> 
            <div class="row">    
                           
                <div class="col-12 col-md-3 form-group">                    
                    <label for="qntd_min_compra">Minima *</label>
                <input type="number" class="form-control" name="qntd_min_compra" value="{{$edit->qntd_min_compra}}" id="qntd_min_compra" placeholder="EX. 100" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('qntd_min_compra')) <div class="invalid-feedback">{{$errors->first('qntd_min_compra')}}
                    </div>@endif
                </div>
                <div class="col-12 col-md-3 form-group">                    
                    <label for="qntd_max_compra">Maxima *</label>
                    <input type="number" class="form-control" name="qntd_max_compra" value="{{$edit->qntd_max_compra}}" id="qntd_max_compra" placeholder="EX. 100" required>
                    <small class="text-muted">Somente números</small>
                    @if($errors->has('qntd_max_compra')) <div class="invalid-feedback">{{$errors->first('qntd_max_compra')}}</div>
                    @endif
                </div>  
                <div class="col-12 col-md-6 form-group">                    
                    <label for="descricao">Descrição do ingresso (opcional):</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Informações adicionais ao nome do ingresso. Ex.: Esse ingresso dá direito a um copo">{{$edit->descricao}}</textarea>
                </div>                
            </div>

            <button type="submit" class="btn btn-success btn-sm">Atualizar Ingresso</button>
        <a href="{{route('ingresso', ['id_evento' => $evento->id ])}}" class="btn btn-secondary btn-sm">Voltar</a>
    </div>
    </form>
</div>

@endsection

@section('javascript')

<script type="text/javaScript">

    $(document).ready(function() {
        $(".valor").on("input", function() {
            var textoDigitado = $(this).val();
            var inputCusto = $(this).attr("custo");
            $("." + inputCusto).val(textoDigitado + ' (meia-entrada)');
        });
    });

    $(document).ready(function() {
        $(".din").on("input", function() {
            var textoDigitado   = $(this).val(); 
            textoDigitado       = textoDigitado.replace('.', '').replace(',', '');           
            textoDigitado       = (textoDigitado/2);
            textoDigitado       = formatReal(textoDigitado);            
            var inputCusto      = $(this).attr("meia");
            $("." + inputCusto).val(textoDigitado);

            calcular1(textoDigitado);
        });
    });

    $("input").change(function() {
        if ($('#meia').prop("checked") == true) {
            $('.meia-entrada').fadeIn("show");
        } else {      
            $('.meia-entrada').hide();
        }
    });

    $("input").change(function() {
        if ($('#periodo').prop("checked") == true) {
            $('.por-data').fadeIn("show");
            $('.por-lote').hide();
        } else {      
            $('.por-data').hide();
            $('.por-lote').fadeIn("show");
        }
    });

    $("input").change(function() {
        if ($('#periodo').prop("checked") == true) {
            $('.por-data').fadeIn("show");
            $('.por-lote').hide();
        } else {      
            $('.por-data').hide();
            $('.por-lote').fadeIn("show");
        }
    });

    $("input").change(function() {
        if ($('.convite').prop("checked") == true) {
            $('.convidados').fadeIn("show");
        } else {      
            $('.convidados').hide();
        }
    });


    function calcular() {
        var valor  = parseFloat(document.getElementById('preco').value.replace('.', '').replace(',', ''));
        //console.log(valor1);
        //var total   = valor1 * 1.1;
        var porcentagem = (valor*(10/100));
        var total       = valor + porcentagem;
        document.getElementById('total').value = formatReal(total);
        calcular();
    }
        // function getMoney(str) {
        // return parseInt(str.replace(/[\D]+/g, ''));
        // }
    function formatReal(int) {
        var tmp = int + '';
        tmp = tmp.replace(/([0-9]{2})$/g, ".$1,$2");
        if (tmp.length > 6)
            tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
        return tmp;
        calcular();
    }

    function calcular1() {
        var valor1  = parseFloat(document.getElementById('preco_meia_entrada').value.replace('.', '').replace(',', ''));
        //var total   = valor1 * 1.1;
        var porcentagem = (valor1*(10/100));
        var total       = valor1 + porcentagem;
        document.getElementById('total_meia_entrada').value = formatReal(total);
        calcular1();
        }
        function getMoney(str) {
        return parseInt(str.replace(/[\D]+/g, ''));
        }
        function formatReal(int) {
        var tmp = int + '';
        tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
        if (tmp.length > 6)
            tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
        return tmp;
        calcular1();
    }


    $(".button").click(function() {
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