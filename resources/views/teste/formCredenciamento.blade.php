<?php

use App\Campo_cred;

//$cred = Campo_cred::all();
$cred = Campo_cred::select('*')
        ->where('id_evento', '=', $exibir->id)
        ->get();

$salas = DB::table('salas')
        ->select('nome','id','hora_inicio','hora_fim')
        ->where('id_evento', $exibir->id)
        ->get();
            
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$dataini = strftime('%d de %B de %Y');
$datafim = strftime('%d de %B de %Y');
?>
<html>

<head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/all.css') }}" rel="stylesheet">
        <link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
        <title>Gin Top - {{$exibir->nome}}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <!-- include libraries(jQuery, bootstrap) -->
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<style>
        /* .card-header {
                background-color: #00000073 !important;
                color: #fff;
        } */

        .card-body {
                background-color: #fff !important;
        }

        .card-footer {}

        body {
                padding-bottom: 50px;
        }

        .note-editor.note-frame {
                border: none !important;
                pointer-events: none !important;
        }

        textarea {
                resize: none;
                cursor: none;
        }

        .img-banner {
                height: 80%;
                width: 100%;
        }
</style>
@if(isset($exibir->url_imagem))
<style>
        /* .card {
                margin-top: -68px;
                background-color: transparent !important;
        } */
</style>
@endif

<body>

        {{-----------------------------------------------------------}}
        @if(session('mensagem'))
        <div id='alert' class="alert alert-success alert-dismissible fade show text-center" role="alert"
                style="padding: 20px; margin-bottom: 0px !important; background-color: #28a745; color: #fff; border-color: #28a745; border-radius: 0rem;">
                <h3>Olá {{ session('mensagem') }}! Seu cadastro foi realizado com Sucesso!!!</h3>
                <h4>Lhe enviamos um email com informações do evento!</h4>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
        </div>
        @endif @if($errors->any()) @foreach ($errors->all() as $erro)
        <div id='alert' class="alert alert-danger alert-dismissible fade show text-center" role="alert"
                style="padding: 20px; margin-bottom: 0px !important; background-color: #e3342f; color: #fff; border-color: #e3342f; border-radius: 0rem;">
                <h3>{{$erro}}</h3>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
        </div>
        @endforeach @endif
        @if(isset($exibir->url_imagem))
        <img alt="Banner @if(!empty($exibir->nome))para {{$exibir->nome}}@endif" class="img-banner"
                src="/storage/{{$exibir->url_imagem}}">@endif
        <div class="container">
                <main role="main">
                        <div class="card">
                                <div class="card-header">
                                        <h2 class="text-center">Cadastro Visitantes - <b
                                                        id="nome-evento">{{$exibir->nome}}</b></h2>
                                </div>
                                <br>
                                <div class="card-body">
                                        <div>
                                                <div class="col-12">
                                                        <h5><i class="fas fa-map-marker-alt"></i>
                                                                {{$exibir->nome_local}} - {{$exibir->endereco_local}}
                                                        </h5>
                                                </div>
                                                <div class="col-12">
                                                        <h5><i class="far fa-clock"></i> {{$dataini}},
                                                                {{$exibir->hora_inicio}} - {{$datafim}},
                                                                {{$exibir->hora_fim}}
                                                        </h5>
                                                </div>
                                        </div>
                                        <br>
                                        <div>
                                                <textarea id="summernote" style="pointer-events: none;">
                                                {{$exibir->descricao}}
                                                </textarea>


                                        </div>
                                        <hr> @if($errors->any()) @foreach ($errors->all() as $erro)
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{$erro}}
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                        @endforeach @endif @if(count($cred) > 0)
                                        <form class="form" id="formCredencianto" action="/salvarcred/{{$exibir->slug}}"
                                                method="POST">
                                                @csrf
                                                <div class="row">
                                                        @foreach ($cred as $camposCred) @if($camposCred->id_evento ==
                                                        $exibir->id)
                                                        <input type="hidden" name="cracha[]"
                                                                value="{{$camposCred->cracha}}">
                                                        <input type="hidden" name="id_campo_cred[]"
                                                                value="{{$camposCred->id}}">
                                                        <input type="hidden" id="id_evento" name="id_evento[]"
                                                                value="{{$camposCred->id_evento}}">
                                                        <input type="hidden" name="campo[]"
                                                                value="{{$camposCred->nome}}">
                                                        <input type="hidden" name="tipo[]"
                                                                value="{{$camposCred->tipo}}">
                                                        <input type="hidden" name="nome[]" class='custo1'>
                                                        <input type="hidden" name="email[]" class='custo2'>
                                                        <input type="hidden" name="celular[]" class='custo3'>
                                                        {{-- <input type="hidden" name="{{$camposCred->nome}}"
                                                        class='custo4'> --}}

                                                        <div class="col-md-6 col-12">
                                                                <br>
                                                                <label
                                                                        for="valor_salvo[]">{{$camposCred->nome}}:</label>
                                                                @php $strExemple = $camposCred->opcoes; $opcoes =
                                                                explode(';', $strExemple); if($camposCred->obrigatorio
                                                                == 1){ $camposCred->obrigatorio = 'required' ; }elseif
                                                                ($camposCred->obrigatorio == 0) {
                                                                $camposCred->obrigatorio = ' ' ; } @endphp
                                                                @switch($camposCred->tipo) @case('text')
                                                                @if($camposCred->nome == 'Nome')
                                                                <input type="text" class="form-control valor"
                                                                        name="valor_salvo[]" id="valor_salvo[]"
                                                                        placeholder="{{$camposCred->nome}}"
                                                                        {{$camposCred->obrigatorio}}
                                                                        maxlength="{{$camposCred->tamanho}}"
                                                                        custo='custo1'> @else
                                                                <input type="text" class="form-control "
                                                                        name="valor_salvo[]" id="valor_salvo[]"
                                                                        placeholder="{{$camposCred->nome}}"
                                                                        {{$camposCred->obrigatorio}}
                                                                        maxlength="{{$camposCred->tamanho}}"> @endif
                                                                @break @case('number')
                                                                <input type="number" class="form-control valor"
                                                                        name="valor_salvo[]" id="valor_salvo[]"
                                                                        placeholder="{{$camposCred->nome}}"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        {{$camposCred->obrigatorio}}> @break
                                                                @case('cpf')
                                                                <input type="text" class="form-control cpf valor"
                                                                        name="valor_salvo[]" id="cpf"
                                                                        placeholder="EX: 999.999.999-99"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        {{$camposCred->obrigatorio}}>
                                                                <div id="invalidocpf" class="invalid-feedback">CPF
                                                                        inválido!
                                                                </div>
                                                                <div id="existecpf" class="invalid-feedback">CPF já
                                                                        existente!
                                                                </div> @break @case('cnpj')
                                                                <input type="text" class="form-control cnpj valor"
                                                                        onblur=validarcnpj() name="valor_salvo[]"
                                                                        id="cnpj" placeholder="EX: 00.000.000/0000-00"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        {{$camposCred->obrigatorio}}>
                                                                <div id="invalidocnpj" class="invalid-feedback">CNPJ
                                                                        inválido!
                                                                </div>
                                                                <div id="existecnpj" class="invalid-feedback">CNPJ já
                                                                        existente!
                                                                </div>
                                                                @break @case('email') @if($camposCred->nome == 'Email')
                                                                <input type="email" class="form-control valor email"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        name="valor_salvo[]" id="email"
                                                                        onblur=validaremail()
                                                                        placeholder="{{$camposCred->nome}}"
                                                                        {{$camposCred->obrigatorio}} custo='custo2'>
                                                                @else
                                                                <input type="email" class="form-control email"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        name="valor_salvo[]" id="email"
                                                                        onblur=validaremail()
                                                                        placeholder="{{$camposCred->nome}}"
                                                                        {{$camposCred->obrigatorio}}> @endif
                                                                <div id="existeemail" class="invalid-feedback">Email já
                                                                        existente!
                                                                </div>
                                                                @break @case('tel') @if($camposCred->nome == 'Celular')
                                                                <input type="text" class="form-control phone valor"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        name="valor_salvo[]" id="valor_salvo[]"
                                                                        placeholder="EX: (99) 99999-9999"
                                                                        {{$camposCred->obrigatorio}} custo='custo3'>
                                                                @else
                                                                <input type="text" class="form-control phone"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        name="valor_salvo[]" id="valor_salvo[]"
                                                                        placeholder="EX: (99) 99999-9999"
                                                                        {{$camposCred->obrigatorio}}> @endif @break
                                                                @case('date')
                                                                <input type="date" class="form-control valor"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        name="valor_salvo[]" id="valor_salvo[]"
                                                                        placeholder="__/__/____"
                                                                        {{$camposCred->obrigatorio}}> @break
                                                                @case('checkbox') @foreach ($opcoes as $op)
                                                                <div class="form-check">
                                                                        <input class="form-check-input valor"
                                                                                type="checkbox" name="valor_salvo[]"
                                                                                id="valor_salvo[]" value='{{$op}}'
                                                                                {{$camposCred->obrigatorio}}>
                                                                        <label class="form-check-label"
                                                                                for="valor_salvo[]">{{$op}}</label>
                                                                </div>
                                                                @endforeach @break @case('select')
                                                                <select class="form-control" name="valor_salvo[]"
                                                                        value="{{old('valor_salvo.*')}}"
                                                                        id="valor_salvo[]" {{$camposCred->obrigatorio}}>
                                                                        @foreach ($opcoes as $op)
                                                                        <option value='{{$op}}'>{{$op}}</option>
                                                                        @endforeach
                                                                </select> @break @default @endswitch
                                                        </div>
                                                        @endif @endforeach

                                                        <div class="col-md-6 col-12">
                                                                <br>
                                                                <label for="Palestras">Palestras:</label><br>
                                                                <div class="">
                                                                        @foreach($salas as $sal)
                                                                        <input type="checkbox" name="palestras"
                                                                                id="Palestras" value='{{$sal->nome}} '>
                                                                        <span style="font-size: 1.125rem;">
                                                                                {{$sal->nome}}</span> - <small> horário
                                                                                {{$sal->hora_inicio}} às
                                                                                {{$sal->hora_fim}} </small> <br>
                                                                        @endforeach

                                                                </div>
                                                        </div>
                                                </div>
                                </div>

                                <div>
                                <h4 class=" col-6 card-header">Ingressos</h4>
                                <div class="table col-12 ">

                                        <table class="col-6 table table-bordered table-tickets" id="ingressos">
                                        <thead>
                                                <tr>
                                                <th>Tipo do ingresso</th>
                                                <th>Quantidade</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                          <td> Lote exemplo </td>   
                                          <td> <select class="col-6 custom-select">
                                                <option selected>0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                </select> </td>                            
                                        </tbody>
                                        </table>

                                </div>
                                </div>

                                <div class="card-footer">
                                        <button type="submit" class="btn btn-success btn-md">Salvar</button>
                                </div>
                                </form>
                                @else
                                <div class="alert alert-warning" role="alert">
                                        Formulário indisponivel no momento!!!
                                </div>
                                @endif

                        </div>

</body>
<script src="{{ asset('js/app.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.mask.js')}}" type="text/javascript"></script>

<script src="{{ asset('js/summernote.js')}}" type="text/javascript"></script>

<script type="text/javascript">
        $(document).ready(function() {
            $(".valor").on("input", function() {
                var textoDigitado = $(this).val();
                var inputCusto = $(this).attr("custo");
                $("." + inputCusto).val(textoDigitado);
            });
        });

        $(document).ready(function() {

            $('.note-statusbar').remove();
            $('.note-resizebar').remove();
            document.getElementById("summernote").disabled = true;
        });

        $('#summernote').summernote({
            toolbar: [],
        });

        $(document).ready(function() {
            $('.phone').mask('(00) 00000-0000'); // Máscara para TELEFONE
            $('.cpf').mask('000.000.000-00', {
                reverse: true
            }); // Máscara para CPF
            $('.cnpj').mask('00.000.000/0000-00', {
                reverse: true
            }); // Máscara para CPF
            $('.cep').mask('00000-000'); // Máscara para CEP
            $('.rg').mask('AA-99.999.999'); // Máscara para RG
            $('.money').mask('000.000.000.000.000,00', {
                reverse: true
            }); // Máscara para dinheiro

        });

        setTimeout(function() {
            $("#alert").slideUp('slow');
        }, 5000);

        function validaremail() {
            if ($('#email').val().length > 0) {
                $.ajax({
                    type: 'get',
                    url: '/emailcred',
                    data: {
                        id_evento: $('#id_evento').val(),
                        email: $('#email').val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.sucesso == 1) {
                            //alert(data.mensagem);
                            $('#email').addClass("is-invalid");
                            $("#email").focus();
                            $('#existeemail').show();
                            document.getElementById('email').value = '';
                        } else {
                                $('#existeemail').hide();
                                $('#email').removeClass("is-invalid");
                        }
                    }
                });
            }
        }

        function validarcnpj() {

            if ($('#cnpj').val().length > 0) {
                $.ajax({
                    type: 'get',
                    url: '/cnpjcred',
                    data: {
                        id_evento: $('#id_evento').val(),
                        cnpj: $('#cnpj').val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.sucesso == 1) {
                            //alert(data.mensagem);
                            $('#cnpj').addClass("is-invalid");
                            $("#cnpj").focus();
                            $('#invalidocnpj').hide();
                            $('#existecnpj').show();
                            document.getElementById('cnpj').value = '';
                        }
                        if (data.sucesso == 2) {
                            //alert(data.mensagem);
                            $('#cnpj').addClass("is-invalid");
                            $("#cnpj").focus();
                            $('#existecnpj').hide();
                            $('#invalidocnpj').show();
                            document.getElementById('cnpj').value = '';
                        }
                        if (data.sucesso == 0) {
                            $('#cnpj').removeClass("is-invalid");
                        }
                    }
                });
            }
        }

        function validarcpf() {

            if ($('#cpf').val().length > 0) {
                $.ajax({
                    type: 'get',
                    url: '/cpfcred',
                    data: {
                        id_evento: $('#id_evento').val(),
                        cpf: $('#cpf').val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.sucesso == 1) {
                            //alert(data.mensagem);
                            $('#cpf').addClass("is-invalid");
                            $("#cpf").focus();
                            $('#invalidocpf').hide();
                            $('#existecpf').show();
                            document.getElementById('cpf').value = '';
                        }
                        if (data.sucesso == 2) {
                            //alert(data.mensagem);
                            $('#cpf').addClass("is-invalid");
                            $("#cpf").focus();
                            $('#existecpf').hide();
                            $('#invalidocpf').show();
                            document.getElementById('cpf').value = '';
                        }
                        if (data.sucesso == 0) {
                            $('#cpf').removeClass("is-invalid");
                        }
                    }
                });
            }
        }
</script>

</html>