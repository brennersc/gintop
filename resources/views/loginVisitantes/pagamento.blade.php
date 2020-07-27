@php

$cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", Auth::guard('visitantes_login')->user()->cpf);

//pegar valores dos visitantes ja cadastrados
//nome email celular e todos valores salvos ja cadastrados juntos
$items = DB::table('credenciamentos')
->select('palestras','nome','email','cpf','celular')
->selectRaw('GROUP_CONCAT(valor_salvo ORDER BY id ASC SEPARATOR ";") as valor_salvo')
->selectRaw('GROUP_CONCAT(id ORDER BY id ASC SEPARATOR ";") as id')
->where('id_evento', [$evento->id])
->where('cpf', Auth::guard('visitantes_login')->user()->cpf)
->where('ativo', true)
->orderByRaw('nome')
->groupBy('palestras','nome','email','cpf','celular')
->paginate(20);

// var_export($items);
// die;

use App\Sala;

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
        Informações {{ session('mensagem') }} com Sucesso!!!
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
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" id='message'>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Aviso!</h4>
            {{Session::get('error')['message']}}. <br>
            Código: {{Session::get('error')['status_code']}}.<br>
            {{Session::get('error')['details'][0]['description']}}.<br>
            {{Session::get('error')['details'][0]['description_detail']}}    
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
                        do visitante, aqui você
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
            <h3>Efeturar Pagamento <b> @if(isset($ingresso)) {{$ingresso->nome}} @else Mesa @endif <b></h3>
            <div class="row">
                {{-- @foreach ($eventos as $ev)
                @php
                $data_inicio = date("d M", strtotime($ev->data_inicio));

                $inicio = date("d/m/Y", strtotime($ev->data_inicio));
                $fim = date("d/m/Y", strtotime($ev->data_fim));
                @endphp --}}

                <div class="col-md-12">
                    <div class="card mb-12">
                        @if(isset($evento->url_imagem))
                        <img class="card-img-top" height="250px" width="190px" src="/storage/{{$evento->url_imagem}}"
                            alt="Banner do evento {{$evento->nome}}">
                        @else
                            <img class="card-img-top" height="250px" width="190px" src="../storage/imagens/bannertop.png"
                            alt="Banner do evento {{$evento->nome}}">
                        @endif

                        <div class="card-body">
                            <!-- ----------------------------------- começo --------------------------------------------------- -->

                            <form class="form" id="formCredencianto"  action="{{ route('pagar') }}" method="post">
                                <input type="hidden" name="cart_id" value="1">                                   
                                @csrf

                                <div class="row">
                                    <div class="modal-body">                               
                                        <br>
                                        <div class="">
                                                <h5 class="modal-title"><i class="fa fa-credit-card"></i> Dados para pagamentos com cartão de crédito: </h5>
                                        </div><br>
                                        <div class="row">                                       
                                                <div class="form-group col-6">
                                                        <label for="" class="control-label">Nome do titular do cartão:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="nome" id="nome" class="form-control " value="" required>
                                                        </div>
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="" class="control-label">CPF do titular do cartão:</label>
                                                    <div class="input-group">
                                                            <input type="text" name="cpf" id="cpf" class="form-control cpf" value="" required>
                                                    </div>
                                                </div>

                                                <div class="form-group col-6">
                                                        <label for="" class="control-label">Número do cartão:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="numero" id="numero" class="form-control cartao" required>
                                                        </div>
                                                </div>

                                                <div class="form-group col-2">
                                                        <label for="" class="control-label">Mês:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="mes" id="mes" class="form-control mes" value="" required>
                                                        </div>
                                                </div>

                                                <div class="form-group col-2">
                                                        <label for="" class="control-label">Ano:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="ano" id="ano" class="form-control ano" value="" required>
                                                        </div>
                                                </div>

                                                <div class="form-group col-4">
                                                    <label for="" class="control-label">Código de segurança:</label>
                                                    <div class="input-group">
                                                            <input type="text" name="cod" id="cod" class="form-control cod" value="" required>
                                                    </div>
                                                </div>

                                                <div class="form-group col-4">
                                                    <label for="cep" class="control-label">CEP:</label>
                                                    <div class="input-group">
                                                            <input type="text" name="cep" id="cep" onblur="pesquisacep(this.value);" class="form-control cep" value="" required>
                                                    </div>

                                                    <input type="hidden" name="amount" id="amount" @if(isset($ingresso)) value="{{$venda->total}}" @else value="{{$venda->valor}}" @endif>
                                                    <input type="hidden" name="id_venda" id="id_venda" value="{{$venda->id}}">
                                                    <input type="hidden" name="rua" id="rua" value="">
                                                    <input type="hidden" name="bairro" id="bairro" value="">
                                                    <input type="hidden" name="cidade" id="cidade" value="">
                                                    <input type="hidden" id="estado" name="estado" value="">
                                                    @if(isset($ingresso)) 
                                                        <input type="hidden" id="identificador" name="identificador" value="ingresso">                                                  
                                                    @else 
                                                        <input type="hidden" id="identificador" name="identificador" value="mesa">
                                                    @endif
                                                </div>
                                                <br><br>
                                                
                                                {{-- <h3 class="text-success">Total: </h3> --}}
                                                <div class="form-group col-12"><br>

                                                    <h3 class="text-success">Total: @if(isset($ingresso)) {{$venda->total}} @else {{$venda->valor}} @endif</h3>
                                                </div>
                                                
                                        </div>                                           

                                </div> 
                                </div>
                                <button type="submit" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#modal-default"><i class="fa fa-credit-card"></i> Efetuar pagamento</button>
                                <br>
                                <div class="modal-footer">
                                    <a href="{{ route('visitante') }}" class="btn btn-secondary" data-dismiss="modal">Voltar</a>
                                </div>
                            </form>
                        </div>


                        <!-- ----------------------------------- fim --------------------------------------------------- -->

                    </div>
                </div>
            </div>            

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