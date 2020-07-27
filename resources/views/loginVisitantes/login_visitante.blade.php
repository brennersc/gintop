@php

$cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", Auth::guard('visitantes_login')->user()->cpf);

@endphp
@extends('layouts.app')

@section('content')

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
        .order-md-0 {
            -webkit-box-ordinal-group: 1;
            -ms-flex-order: 0;
            order: 0
        }

        .order-md-1 {
            -webkit-box-ordinal-group: 2;
            -ms-flex-order: 1;
            order: 1
        }

        .order-md-2 {
            -webkit-box-ordinal-group: 3;
            -ms-flex-order: 2;
            order: 2
        }

        .order-md-3 {
            -webkit-box-ordinal-group: 4;
            -ms-flex-order: 3;
            order: 3
        }

        .order-md-4 {
            -webkit-box-ordinal-group: 5;
            -ms-flex-order: 4;
            order: 4
        }

        .order-md-5 {
            -webkit-box-ordinal-group: 6;
            -ms-flex-order: 5;
            order: 5
        }

        .order-md-6 {
            -webkit-box-ordinal-group: 7;
            -ms-flex-order: 6;
            order: 6
        }

        .order-md-7 {
            -webkit-box-ordinal-group: 8;
            -ms-flex-order: 7;
            order: 7
        }

        .order-md-8 {
            -webkit-box-ordinal-group: 9;
            -ms-flex-order: 8;
            order: 8
        }

        .order-md-9 {
            -webkit-box-ordinal-group: 10;
            -ms-flex-order: 9;
            order: 9
        }

        .order-md-10 {
            -webkit-box-ordinal-group: 11;
            -ms-flex-order: 10;
            order: 10
        }

        .order-md-11 {
            -webkit-box-ordinal-group: 12;
            -ms-flex-order: 11;
            order: 11
        }

        .order-md-12 {
            -webkit-box-ordinal-group: 13;
            -ms-flex-order: 12;
            order: 12
        }
    }
</style>

<div class="container">

    @if(session('mensagem'))
    <div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
        style="box-shadow: 0px 0px 20px #A4A4A4;">
        Conta {{ session('mensagem') }} com Sucesso!!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if(session('apagado'))
    <div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
        style="box-shadow: 0px 0px 20px #A4A4A4;">
        Evento APAGADO com Sucesso!!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('message'))        
        <div id='alert' class="alert alert-success alert-dismissible fade show" role="alert" style="box-shadow: 0px 0px 20px #A4A4A4;">
            <h4><i class="icon fa fa-check"></i> Aviso!</h4>
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-rigth">
        <div class="col-md-5 order-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Área do Visitante</h4>
                </div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <p class="text-justify">Bem Vindo <b>{{ Auth::guard('visitantes_login')->user()->name }}</b> a área
                        do visitante, aqui você pode
                        ver os proximos eventos que se cadastrou, e podendo editar as informações cadastradas de cada
                        evento!</p>
                    <p class="text-justify">E também pode editar sua conta e alterar sua senha.</p>
                </div>
                <hr>
                <div class="card-body">
                    <h4>Dados:</h4>
                    <form action="{{ route('alterar' , ['id' => Auth::guard('visitantes_login')->user()->id ]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="nome" class="col-sm-3 col-form-label">Nome:</label>
                            <div class="col-sm-9">
                                <input type="text" id="nome" name="nome" class="form-control form-control-sm"
                                    value="{{ Auth::guard('visitantes_login')->user()->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email:</label>
                            <div class="col-sm-9">
                                <input type="email" id="email" name="email" class="form-control form-control-sm"
                                    id="email" value="{{ Auth::guard('visitantes_login')->user()->email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cpf" class="col-sm-3 col-form-label">Cpf:</label>
                            <div class="col-sm-9">
                                <input type="text" id="cpf" name="cpf" class="form-control form-control-sm" id="email"
                                    value={{$cpf}} disabled>
                            </div>
                        </div>
                        <button type='submit' class="btn btn-sm btn-success float-right" role="button">
                            <i class="fas fa-pencil-alt"></i> Alterar</button>
                    </form>
                </div>
                <hr>
                <div class="card-body">
                    <h4>Alterar Senha:</h4>
                    <form id="trocarsenha" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="user" value="{{ Auth::guard('visitantes_login')->user()->id }}"
                            name="id">
                        <div class="form-group row">
                            <label for="senha" class="col-sm-4 col-form-label">Senha:</label>
                            <div class="col-sm-8">
                                <input type="password" id="senha" name="senha" class="form-control form-control-sm"
                                    placeholder="*******">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirmasenha" class="col-sm-4 col-form-label">Confirme:</label>
                            <div class="col-sm-8">
                                <input type="password" id="confirmasenha" name="confirmasenha"
                                    class="form-control form-control-sm" placeholder="*******">
                            </div>
                        </div>
                        <button type='submit' class="btn btn-sm btn-success float-right" role="button"><i
                                class="fas fa-key"></i> Trocar</button>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-md-7 order-md-1">
            <br>
            <h3>Meus Eventos</h3>
            <div class="row">
                @foreach ($eventos as $ev)
                @php
                $data_inicio = date("d M", strtotime($ev->data_inicio));

                $inicio = date("d/m/Y", strtotime($ev->data_inicio));
                $fim = date("d/m/Y", strtotime($ev->data_fim));
                @endphp

                <div class="col-md-6">
                    <div class="card mb-3">
                        @if(isset($ev->url_imagem))
                        <img class="card-img-top" height="180px" width="190px" src="/storage/{{$ev->url_imagem}}"
                            alt="Banner do evento {{$ev->nome}}">
                        @else
                        <img class="card-img-top" height="180px" width="190px" src="../storage/imagens/bannertop.png"
                            alt="Banner do evento {{$ev->nome}}">
                        @endif
                        <div class="card-body">
                            <span style="font-size:20px; color:dodgerblue;">{{$data_inicio}}</span></h5>
                            {{$ev->hora_inicio}}
                            <h5 class="card-title"><b>{{$ev->nome}}</b></h5>
                            <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{$ev->nome_local}}</p>
                            <a href="http://gintop.com.br/credenciamento/{{$ev->slug}}"
                                target="_blank">{{$ev->slug}}</a>
                            <p>
                                <div class="btn-group btn-group-lg float-center" role="group" aria-label="Basic example">
                                    <button role="button" data-toggle="modal" data-target="#ingresso{{$ev->id}}"
                                        class="btn btn-outline-secondary" title="Ingresso"> <i class="fas fa-ticket-alt"></i> </button>                                      
                                    <a href="{{ route('editar' , ['id_evento' => $ev->id]) }}"
                                        class="btn btn-outline-primary" title="Editar"><i class="far fa-edit"></i> </a>
                                    <button class="btn btn-outline-success" role="button" data-toggle="modal"
                                        data-target="#edit{{$ev->id}}" title="Informações"><i class="fas fa-info-circle"></i> </button>
                                    <button role="button" data-toggle="modal" data-target="#apagar{{$ev->id}}"
                                        class="btn btn-outline-danger" title="Apagar"> <i class="fas fa-trash-alt"></i> </button>
                                </div>
                        </div>
                    </div>
                </div>

                <!-- ----------------------------------- INFORMAÇÕES --------------------------------------------------- -->
                <div class="modal fade bd-example-modal-lg" id="edit{{$ev->id}}" tabindex="-1"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><b>{{$ev->nome}}</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @if(isset($ev->url_imagem))
                            <img class="card-img-top" height="300px" width="190px" src="/storage/{{$ev->url_imagem}}"
                                alt="Banner do evento {{$ev->nome}}">
                            @else
                            <img class="card-img-top" height="300px" width="190px"
                                src="../storage/imagens/bannertop.png" alt="Banner do evento {{$ev->nome}}">
                            @endif
                            <div class="modal-body">
                                <div class="row">
                                    <div class=" col-12">
                                        <h5><b>Descrição:</b></h5>
                                        <textarea class="summernote" disabled>
                                                    {{$ev->descricao}}
                                        </textarea>
                                    </div>
                                    <div class="col-12">
                                        <h5><i class="fas fa-map-marker-alt"></i>
                                            {{$ev->nome_local}} - {{$ev->endereco_local}}
                                        </h5>
                                    </div>
                                    <div class="col-12">
                                        <h5><i class="far fa-calendar-alt"></i> - {{ $inicio }},
                                            {{$ev->hora_inicio}} - {{$fim}},
                                            {{$ev->hora_fim}}
                                        </h5>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <h5><b>link:</b> <a href="http://gintop.com.br/credenciamento/{{$ev->slug}}"
                                                target="_blank">{{$ev->nome}}</a></h5>
                                    </div>
                                    <br><br>
                                    <iframe frameborder="0" width="100%" height="300" scrolling="no" marginheight="0" marginwidth="0"
                                    src="https://maps.google.com/maps?hl=pt&amp;q={{$ev->endereco_local}}&amp;ie=UTF8&amp;t=roadmap&amp;z=15&amp;iwloc=B&amp;output=embed"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ----------------------------------- MODAL APAGAR EVENTO --------------------------------------------------- -->


                <div class="modal fade" id="apagar{{ $ev->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="TituloModalCentralizado" aria-hidden="true">
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
                                        <h5> Tem certeza que deseja apagar o {{$ev->nome}} ? </h5>
                                    </div>
                                    <div class="col-3">
                                        <a href="{{ route('apagar' , ['cpf' => base64_encode($cpf), 'id_evento' => $ev->id ]) }}"
                                            class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Apagar</a>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ----------------------------------- MODAL INGRESSOS EVENTO --------------------------------------------------- -->

                <div class="modal fade" id="ingresso{{ $ev->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="TituloModalCentralizado" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="TituloModalCentralizado">Ingresso</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id='ingresso'>
                                <div class="row" id="linha">
                                    <table class="table">
                                        <thead>
                                            <tr>                                               
                                                <th scope="col">Ingresso</th>
                                                <th scope="col">Descrição</th>
                                                <th scope="col">Data Compra</th>
                                                <th scope="col">Preço</th>
                                                <th scope="col">Status Pagamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ingressos as $ingresso)
                                                <tr>
                                                    <th scope="row">{{$ingresso->nome}}</th>
                                                    <td>{{$ingresso->descricao}}</td>
                                                    <td>{{ date("d/m/Y", strtotime($ingresso->data_compra)) }}</td>
                                                    <td>{{ $ingresso->total }}</td>
                                                    <td>
                                                        @if ($ingresso->pago == 'nao')
                                                            <a href="{{ route('pagamento' , ['cpf' => base64_encode($cpf), 'id_evento' =>  $ingresso->id_evento, 'id_ingresso' => $ingresso->id_ingresso, 'id_venda_ingresso' => $ingresso->id ]) }}"
                                                                class="btn btn-sm btn-warning"> Pendente</a>
                                                        @else
                                                            <button type="" class="btn btn-primary btn-sm">Pago</button>                                                            
                                                        @endif
                                                    </td>                                                
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <br><br>
                                        <thead>
                                            <tr>                                               
                                                <th scope="col">Mesa</th>
                                                <th scope="col">Assentos</th>
                                                <th scope="col">Descrição</th>
                                                <th scope="col">Preço</th>
                                                <th scope="col">Status Pagamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($mesas as $mesa)
                                            <tr>
                                                    <th scope="row">{{$mesa->qual}} </th>
                                                    <th scope="row">{{$mesa->assentos}} </th>
                                                    <td>{{$mesa->descricao}}</td>                                                    
                                                    <td>{{ $mesa->valor }}</td>
                                                    <td>
                                                        @if ($mesa->pago == 'nao')
                                                            <a href="{{ route('pagamentomesa' , ['cpf' => base64_encode($cpf), 'id_evento' =>  $mesa->id_evento, 'id_mesa' => $mesa->id_mesa, 'id_venda_mesa' => $mesa->id ]) }}"
                                                                class="btn btn-sm btn-warning"> Pendente</a>
                                                        @else
                                                            <button type="" class="btn btn-primary btn-sm">Pago</button>                                                            
                                                        @endif
                                                    </td>                                                
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>

            
        </div>

    </div>
    
</div>

<br>

</div>



@endsection
@section('javascript')
<script type="text/javascript">
    $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
        });
        $("#trocarsenha").on("submit", function() {
        console.clear();
        var campo_vazio = false;
        if ($('#senha').val() == '') {
            $('#senha').css({
                'border-color': '#ff0000'
            });
            return false;
            campo_vazio = true;
            } else {
                $('#senha').css({
                'border-color': '#CCC'
            }); 
        }
        if ($('#confirmasenha').val() == '') {
            $('#confirmasenha').css({
                'border-color': '#ff0000'
            });
            var campo_vazio = false;
            campo_vazio = true;
            } else {
                $('#confirmasenha').css({
                'border-color': '#CCC'
            }); 
        }
        $.ajax({
            type: 'post',
            url: '/visitante/senha',
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

    setTimeout(function() {
        $("#alert").slideUp('slow');
    }, 5000);

    $(document).ready(function() {
                $('.summernote').summernote({
                        toolbar: [],
                });
                $('.note-statusbar').remove();
                $('.note-resizebar').remove();
                document.getElementById("summernote").disabled;

                $('.data_').mask('00/00/0000', {
                    reverse: true
                    }); // Máscara para data
        });

</script>
@endsection