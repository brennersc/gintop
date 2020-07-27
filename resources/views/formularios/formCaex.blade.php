<?php

use App\Campo_caex;
use App\Sala;

//$caex = Campo_caex::all();
$caex = Campo_caex::select('*')
        ->where('id_evento', '=', $exibir->id)
        ->where('ativo', true)
        ->get();

$salas = Sala::select('nome', 'id', 'data_inicio','data_fim', 'hora_inicio', 'hora_fim', 'nome_local')
         ->whereRaw('(visitantes < quantidade or quantidade = 0) and id_evento = ? and ativo = true', [$exibir->id])
         ->get();

//$salas = DB::select('select nome, id, data_inicio,data_fim, hora_inicio, hora_fim from salas where id_evento = ? and ativo = ? and visitantes < quantidade or visitantes = ?', [$exibir->id, true, 0]);

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');


$data_inicio = date("d/m/Y", strtotime($exibir->data_inicio));
$data_fim = date("d/m/Y", strtotime($exibir->data_fim));

?>
<html>

<head>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   <link href="{{ asset('css/all.css') }}" rel="stylesheet">
   <link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
   <link href="{{ asset('css/form.css') }}" rel="stylesheet">
   <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
   <title>Gin Top - {{$exibir->nome}}</title>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,minimal-ui">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <!-- include libraries(jQuery, bootstrap) -->
   <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
@if(isset($exibir->url_imagem))
<style>
   .card {
      margin-top: -100px;
   }
</style>
@endif
<style>
   @media(min-width: 900px) {
      .table-responsive {
         display: table !important;
      }
   }
</style>
<body>
   <nav class="navbar navbar-expand-lg navbar-light bg-defaut shadow">
      <div class="container">
         <a class="navbar-brand" href="{{ url('/home') }}">
            <img alt="Logo" width="150px" height="40px" src="../storage/imagens/logo-toptecnologia.png">
         </a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
         </button>

         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
               <!-- Authentication Links -->
               @guest
               <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="{{ route('visitante.login') }}">{{ __('Visitante') }}</a>
               </li>
               {{-- @if (Route::has('register'))
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
               </li>
               @endif --}}
               @else
               <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false" v-pre>
                     {{ Auth::user()->name }} <span class="caret"></span>
                  </a>

                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                     <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                     </a>

                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                     </form>
                  </div>
               </li>
               @endguest
            </ul>
         </div>
      </div>
   </nav>

   @if(isset($exibir->url_imagem))
   <div class="img-fundo" style="background: url('/storage/{{$exibir->url_imagem}}'); ">
   </div>
   <br>
   <img alt="Banner @if(!empty($exibir->nome)) para {{$exibir->nome}}@endif" src="/storage/{{$exibir->url_imagem}}"
      class="img-banner">

   @else
   <br><br>
   @endif
   <div class="container">
      <main role="main">

         <div class="card">
            {{---------------------- mensagens -------------------------------------}}
            @if(session('mensagem'))
            <div id='alert' class="alert alert-success alert-dismissible fade show text-center" role="alert"
               style="padding: 20px; margin-bottom: 0px !important; background-color: #28a745; color: #fff; border-color: #28a745; border-radius: 0rem;">
               <h3>Olá {{ session('mensagem') }}! Seu cadastro foi realizado com Sucesso!!!</h3>
               <h4>Lhe enviamos um email com informações do evento!</h4>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            @endif

            @if(session('erro'))
            <div id='alert' class="alert alert-danger alert-dismissible fade show text-center" role="alert"
               style="position: relative; padding: 30px; margin-bottom: 0px !important; background-color: #e3342f; color: #fff; border-color: #e3342f; border-radius: 0rem;">
               <h3>{{ session('erro') }} já existe!</h3>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            @endif
            {{-----------------------------------------------------------}}
            <div class="card-body">
               <div>
                  <h1 class="text-center"><b id="nome-evento">{{$exibir->nome}}</b></h1>
                  <div class="col-12">
                     <h5><i class="fas fa-map-marker-alt"></i>
                        {{$exibir->nome_local}} - {{$exibir->endereco_local}}
                     </h5>
                  </div>
                  <div class="col-12">
                     <h5><i class="far fa-calendar-alt"></i> - {{ $data_inicio }},
                        {{$exibir->hora_inicio}} - {{$data_fim}},
                        {{$exibir->hora_fim}}
                     </h5>
                  </div>
               </div>
               <br>
               <div>
                  <h5>Descrição: </h5>
               </div>
               <div>
                  <textarea id="summernote" style="pointer-events: none;">
                                                {{$exibir->descricao}}
                                                </textarea>


               </div>
               <hr>
               <h4>Formulário de cadastro:</h4>
               @if($errors->any()) @foreach ($errors->all() as $erro)
               <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{$erro}}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               @endforeach @endif @if(count($caex) > 0)
               <form class="form" id="formCaex" action="/salvarcaex/{{$exibir->slug}}" method="POST"
                  onsubmit="this.enviar.disabled=true;">
                  @csrf
                  <div class="row">
                     @foreach ($caex as $camposCaex) @if($camposCaex->id_evento ==
                     $exibir->id)
                     <input type="hidden" name="cracha[]" value="{{$camposCaex->cracha}}">
                     <input type="hidden" id="id_campo_caex" name="id_campo_caex[]" value="{{$camposCaex->id}}">
                     <input type="hidden" id="id_evento" name="id_evento[]" value="{{$camposCaex->id_evento}}">
                     <input type="hidden" name="campo[]" value="{{$camposCaex->nome}}">
                     <input type="hidden" name="tipo[]" value="{{$camposCaex->tipo}}">
                     <input type="hidden" name="nome[]" class='custo1'>
                     <input type="hidden" name="email[]" class='custo2'>
                     <input type="hidden" name="celular[]" class='custo3'>
                     <input type="hidden" name="cpf[]" class='custo4'>
                     {{-- <input type="hidden" name="palestra[]" class='custo5'> --}}
                     {{-- <input type="hidden" name="{{$camposCaex->nome}}"
                     class='custo4'> --}}

                     <div class="col-md-6 col-12">
                        <br>
                        @if($camposCaex->ativo == true)
                        <label for="valor_salvo[]">{{$camposCaex->nome}}:</label>
                        @endif
                        @php $strExemple = $camposCaex->opcoes; $opcoes =
                        explode(';', $strExemple); if($camposCaex->obrigatorio
                        == 1){ $camposCaex->obrigatorio = 'required' ; }elseif
                        ($camposCaex->obrigatorio == 0) {
                        $camposCaex->obrigatorio = ' ' ; } @endphp
                        @switch($camposCaex->tipo) @case('text')
                        @if($camposCaex->nome == 'Nome')
                        <input class="form-control valor" name="valor_salvo[]" id="valor_salvo[]"
                           placeholder="{{$camposCaex->nome}}" {{$camposCaex->obrigatorio}} maxlength="30"
                           custo='custo1'> @else
                        <input class="form-control <?php if($camposCaex->unico == true){ echo 'unico'.$camposCaex->id.' '; echo ' unico ';  } ?>" unico="{{$camposCaex->id}}" name="valor_salvo[]" id="valor_salvo[]"
                           placeholder="{{$camposCaex->nome}}" {{$camposCaex->obrigatorio}}
                           maxlength="{{$camposCaex->tamanho}}" > @endif
                           <div id="{{$camposCaex->id}}" class="invalid-feedback">{{ $camposCaex->nome }} já existe! </div>
                        @break @case('number')
                        <input class="form-control valor <?php if($camposCaex->unico == true){ echo 'unico'.$camposCaex->id.' '; echo ' unico ';  } ?>" unico="{{$camposCaex->id}}" name="valor_salvo[]" id="valor_salvo[]" 
                           placeholder="{{$camposCaex->nome}}" value="{{old('valor_salvo.*')}}"
                           {{$camposCaex->obrigatorio}}> 
                           <div id="{{$camposCaex->id}}" class="invalid-feedback">{{ $camposCaex->nome }} já existe! </div>
                           @break

                        @case('cpf')
                        @if($camposCaex->nome == 'Cpf')
                        <input class="form-control cpf valor" name="valor_salvo[]" onblur=validarcpf() id="cpf"
                           placeholder="EX: 999.999.999-99" value="{{old('valor_salvo.*')}}"
                           {{$camposCaex->obrigatorio}} custo='custo4'>@else
                        <input class="form-control cpf" name="valor_salvo[]" onblur=validarcpf() id="cpf"
                           placeholder="EX: 999.999.999-99" value="{{old('valor_salvo.*')}}"
                           {{$camposCaex->obrigatorio}}>
                        @endif
                        <div id="invalidocpf" class="invalid-feedback">CPF
                           inválido!
                        </div>
                        <div id="existecpf" class="invalid-feedback">CPF já
                           existente!
                        </div> @break @case('cnpj')
                        <input class="form-control cnpj valor" onblur=validarcnpj() name="valor_salvo[]" id="cnpj"
                           placeholder="EX: 00.000.000/0000-00" value="{{old('valor_salvo.*')}}"
                           {{$camposCaex->obrigatorio}}>
                        <div id="invalidocnpj" class="invalid-feedback">CNPJ
                           inválido!
                        </div>
                        <div id="existecnpj" class="invalid-feedback">CNPJ já
                           existente!
                        </div>
                        @break @case('email') @if($camposCaex->nome == 'Email')
                        <input class="form-control valor email" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="email" placeholder="{{$camposCaex->nome}}" {{$camposCaex->obrigatorio}} custo='custo2'>
                        @else
                        <input class="form-control email" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="email" placeholder="{{$camposCaex->nome}}" {{$camposCaex->obrigatorio}}> @endif
                        <div id="existeemail" class="invalid-feedback">Email já
                           existente!
                        </div>
                        @break @case('tel') @if($camposCaex->nome == 'Celular')
                        <input class="form-control phone valor <?php if($camposCaex->unico == true){ echo 'unico'.$camposCaex->id.' '; echo ' unico ';  } ?>" unico="{{$camposCaex->id}}" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="valor_salvo[]" placeholder="EX: (99) 99999-9999" {{$camposCaex->obrigatorio}}
                           custo='custo3'>                           
                        @else
                        <input class="form-control phone <?php if($camposCaex->unico == true){ echo 'unico'.$camposCaex->id.' '; echo ' unico ';  } ?>" unico="{{$camposCaex->id}}" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="valor_salvo[]" placeholder="EX: (99) 99999-9999" {{$camposCaex->obrigatorio}}> @endif
                           <div id="{{$camposCaex->id}}" class="invalid-feedback">{{ $camposCaex->nome }} já existe! </div>
                           @break
                        @case('date')
                        <input class="form-control valor data_ data_calendario" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="valor_salvo[]" placeholder="__/__/____" {{$camposCaex->obrigatorio}}> 
                           @break
                        @case('checkbox') @foreach ($opcoes as $op)
                        <div class="form-check">
                           <input class="form-check-input valor <?php if($camposCaex->unico == true){ echo 'unico'.$camposCaex->id.' '; echo ' unico ';  } ?>" unico="{{$camposCaex->id}}" type="checkbox" name="checkbox[]" id="checkbox[]"
                              value='{{$op}}' {{$camposCaex->obrigatorio}}>
                           <label class="form-check-label" for="checkbox[]">{{$op}}</label>
                        </div>
                        
                        @endforeach
                        <div id="{{$camposCaex->id}}" class="invalid-feedback">{{ $camposCaex->nome }} já existe! </div>
                         @break @case('select')
                        <select class="form-control <?php if($camposCaex->unico == true){ echo 'unico'.$camposCaex->id.' '; echo ' unico ';  } ?>" unico="{{$camposCaex->id}}" name="valor_salvo[]" value="{{old('valor_salvo.*')}}"
                           id="valor_salvo[]" {{$camposCaex->obrigatorio}}>
                           @foreach ($opcoes as $op)
                           <option value='{{$op}}'>{{$op}}</option>
                           @endforeach
                        </select> 
                        <div id="{{$camposCaex->id}}" class="invalid-feedback">{{ $camposCaex->nome }} já existe! </div>
                        @break @default @endswitch
                     </div>
                     @endif
                     @endforeach
                     <?php
                     $i = 0;
                     $contadorsala = count($salas);
                     ?>
                     @if(count($salas) > 0)

                     <div class="col-md-12 col-sm-12">

                        <br>
                        <br>

                        <label for="Palestras">
                           <h4>Cadastro Palestras: </h4>
                        </label><br>
                        <div class="col-md-12 col-sm-12">
                           <input type="hidden" name="contador" id="contador" class="contador"
                              value='{{$contadorsala}}'>
                           <div class="table-responsive">
                              <table class="table  table-striped table-ordered">
                                 <thead>
                                    <tr id="tabela-palestra">
                                       <td><i class="fas fa-check-square" style="font-size: 20px;"></i></td>
                                       <td>Palestras</td>
                                       <td>Data</td>
                                       <td>Horário</td>
                                       <td>Auditório</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($salas as $sal)

                                    <tr>
                                       <td><input type="checkbox" onclick="({{$sal->id}})" name="palestras[]"
                                             id="palestras{{$i}}"
                                             class="palestras valor {{ str_replace(' ','',$sal->nome) }}"
                                             value='{{$sal->nome}}' custo='custo5'> </td><td>{{$sal->nome}}</td>
                                       <td>{{$sal_inicio = date("d/m/Y", strtotime($sal->data_inicio))}}</td>
                                       <td>{{$sal->hora_inicio}} às {{$sal->hora_fim}}</td>
                                       <td>{{$sal->nome_local}}</td>
                                    </tr>
                                    <?php $i++ ; ?> @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>

                     @endif
                  </div>
            </div>
            <div id='load' class="alert alert-warnig alert-dismissible fade show" role="alert" style="margin-top: 20px; display: none;">
               <center>
                       <img src="/storage/imagens/load.gif" alt="load" height="40px" width="40px" >
                       <h3 style="color: #ccc"> Aguarde ...<h3>
               </center>
            </div>
            <br>
            <div class="card-footer">
               <button type="submit" id="enviar" name="enviar" value="Enviar" class="btn btn-success btn-md float-right"><i
                     class="fas fa-paper-plane"></i> Salvar </button>
            </div>
            </form>
            @else
            <div class="alert alert-warning" role="alert">
               Formulário indisponivel no momento!!!
            </div>
            @endif

         </div>
         <div class="row" style="padding: 30px 0px;">
            <div class="col-12">
               <h3>Local:</h3>
               <h5><i class="fas fa-map-marker-alt"></i>
                  {{$exibir->nome_local}} - {{$exibir->endereco_local}}
               </h5>
            </div>
         </div>

         <iframe frameborder="0" width="100%" height="500" scrolling="no" marginheight="0" marginwidth="0"
            src="https://maps.google.com/maps?hl=pt&amp;q={{$exibir->endereco_local}}&amp;ie=UTF8&amp;t=roadmap&amp;z=15&amp;iwloc=B&amp;output=embed"></iframe>

      </main>


   </div>

</body>
<script src="{{ asset('js/app.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.mask.js')}}" type="text/javascript"></script>

<script src="{{ asset('js/summernote.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js')}}"  type="text/javascript"></script>
<script src="{{ asset('js/bootstrap-datepicker.pt-BR.min.js')}}"  type="text/javascript"></script>


<script type="text/javascript">
   $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
    });

    $(document).ready(function() {
            $(this).submit("form", function() {
               $('#load').show();
               $("#enviar").text("Enviando...");
               $("#enviar").attr("disabled", true);
            });
         });


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
            document.getElementById("summernote").disabled;
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
            //$('.rg').mask('AA-99.999.999'); // Máscara para RG
            $('.money').mask('000.000.000.000.000,00', {
                reverse: true
            }); // Máscara para dinheiro
            $('.data_').mask('99/99/9999');
            

        });

      $(document).ready(function() {
            $(".unico").blur("input", function() {
               var textoDigitado = $(this).val();
               var valor_campo = $(this).attr("unico");
                //alert(textoDigitado + inputCusto);
               if (textoDigitado.length > 0) {
                     $.ajax({
                        type: 'get',
                        url: '/unicocaex',
                        data: {
                           id_evento:     $('#id_evento').val(),
                           id_campo_caex: valor_campo,
                           campo:         textoDigitado
                        },
                        dataType: 'JSON',
                        success: function(data){
                           if(data.sucesso == 0){
                              //alert(data.unico);
                              $(data.unico).addClass("is-invalid");
                              $(data.unico).focus();
                              $(data.unico).val('');
                           }
                           if (data.sucesso == 1) {
                              //alert(data.sucesso);
                              $(data.unico).removeClass("is-invalid");
                           } 
                        }
                     });
                  }
            });
        });


        setTimeout(function() {
            $("#alert").slideUp('slow');
        }, 7000);

        $(".palestras").click(function() {
            var nome    =   $(this).next().text();
            var nome_   =   '.'.concat(nome);
            var nome_   =   nome_.replace(/ /g,'');
            var nomes;
            var i;
            //console.log(nome);
            $.ajax({
                type: 'post',
                url: '/horas',
                data: {
                    nome: nome,
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.sucesso == 0) {
                        no = data.nome;                        
                        var nomes = no.split(",");
                        
                        if($(nome_).prop("checked") == true){
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  ' '.concat(nomes[i]);
                                if(nome != nomes2){
                                    console.log(nome);
                                    console.log(nome[i]);
                                    var check2 = nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(check2);
                                    console.log(check2);
                                    $(check2).attr("disabled", true); 
                                    $(check2).prop("checked", false);                                            
                                }                   
                            }
                        }else{
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  ' '.concat(nomes[i]);
                                if(nome != nomes2){
                                    console.log(nome);
                                    console.log(nome[i]);
                                    var check2 =  nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(check2);
                                    console.log(check2);
                                    $(check2).attr("disabled", false); 
                                    //$(check2).prop("checked", true);                                            
                                }
                            }
                        }
                    }
                }
            });
           
        });

        function validarcnpj() {

            if ($('#cnpj').val().length > 0) {
                $.ajax({
                    type: 'get',
                    url: '/cnpjcaex',
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
                    url: '/cpfcaex',
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

        $("#cpf").keyup(function() {
                        if ($('#cpf').val().length >= 14) {
                                $.ajax({
                                        type: 'get',
                                        url: '/cpfcaex',
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
        });

        $('.data_calendario ').datepicker({	
				format: "dd/mm/yyyy",	
				language: "pt-BR",
				startDate: '+0d',
         });

</script>

</html>