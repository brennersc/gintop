<?php

use App\Campo_cred;
use App\Ingresso;
use App\Mesa;
use App\Sala;

//$cred = Campo_cred::all();
$cred = Campo_cred::select('*')
         ->where('id_evento', '=', $exibir->id)
         ->where('ativo', true)
         ->get();

$salas = Sala::select('nome', 'id', 'data_inicio','data_fim', 'hora_inicio', 'hora_fim', 'nome_local')
         ->whereRaw('(visitantes < quantidade or quantidade = 0) and id_evento = ? and ativo = true', [$exibir->id])
         ->get();

$ingressos = Ingresso::select('*')
            //->whereRaw('(data_inicio > CURDATE())')
            //->whereRaw('(data_fim < CURDATE())')
            //->whereRaw('(vendidos < qntd or qntd = 0)')
            ->where('id_evento', '=', $exibir->id)            
            ->where('ativo', true)
            ->get();

$mesas = Mesa::select('*')
            ->where('id_evento', '=', $exibir->id)
            ->where('ativo', true)
            ->get();

//->whereRaw('(qntd < vendidos or vendidos = 0) and id_evento = ? and ativo = true', [$exibir->id])

//DB::select('select nome, id, data_inicio, data_fim, hora_inicio, hora_fim, nome_local from salas where (visitantes < quantidade or quantidade = ?) and id_evento = ? and ativo = ? ', [0,$exibir->id,true]);
                           

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');


$data_inicio = date("d/m/Y", strtotime($exibir->data_inicio));
$data_fim = date("d/m/Y", strtotime($exibir->data_fim));

?>
<html>

<head>
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
<style>
/* Style the Image Used to Trigger the Modal */
#img {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#img:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image (Image Text) - Same Width as the Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation - Zoom in the Modal */
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}

</style>
@if(isset($exibir->url_imagem))
<style>
   .card {
      margin-top: -100px !important;
   }
</style>
@endif
<style>
   @media(min-width: 900px) {
      .table-responsive {
         display: table;
      }
   }

   #tabelaingresso > td{
      border-top: none !important;
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
               @endif --}} @else
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
      class="img-banner"> @else
   <br><br>
   @endif


   <div class="container">
      <main role="main">

         <div class="card">
            {{---------------------- mensagens -------------------------------------}} @if(session('mensagem'))
            <div id='alert' class="alert alert-success alert-dismissible fade show text-center" role="alert"
               style="padding: 20px; margin-bottom: 0px !important; background-color: #28a745; color: #fff; border-color: #28a745; border-radius: 0rem;">
               <h3>Olá {{ session('mensagem') }}! Seu cadastro foi realizado com Sucesso!!!</h3>
               <h4>Lhe enviamos um email com informações do evento!</h4>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            @endif @if(session('erro'))
            <div id='alert' class="alert alert-danger alert-dismissible fade show text-center" role="alert"
               style="position: relative; padding: 30px; margin-bottom: 0px !important; background-color: #e3342f; color: #fff; border-color: #e3342f; border-radius: 0rem;">
               <h3>{{ session('erro') }} já existe!</h3>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            @endif {{-----------------------------------------------------------}}


            <div class="card-body">
               <div>
                  <h1 class="text-center"><b id="nome-evento">{{$exibir->nome}}</b></h1>
                  <div class="col-12">
                     <h5><i class="fas fa-map-marker-alt"></i> {{$exibir->nome_local}} - {{$exibir->endereco_local}}
                     </h5>
                  </div>
                  <div class="col-12">
                     <h5><i class="far fa-calendar-alt"></i> - {{ $data_inicio }}, {{$exibir->hora_inicio}} -
                        {{$data_fim}}, {{$exibir->hora_fim}}
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
               @endforeach @endif @if(count($cred) > 0)
               <form class="form" id="formCredencianto" action="/salvarcred/{{$exibir->slug}}" method="POST"
                  onsubmit="this.enviar.disabled=true;">
                  @csrf
                  <div class="row">
                     @foreach ($cred as $camposCred) @if($camposCred->id_evento == $exibir->id)
                     <input type="hidden" name="cracha[]" value="{{$camposCred->cracha}}">
                     <input type="hidden" name="id_campo_cred[]" value="{{$camposCred->id}}">
                     <input type="hidden" id="id_evento" name="id_evento[]" value="{{$camposCred->id_evento}}">
                     <input type="hidden" name="campo[]" value="{{$camposCred->nome}}">
                     <input type="hidden" name="tipo[]" value="{{$camposCred->tipo}}">
                     <input type="hidden" name="nome[]" class='custo1'>
                     <input type="hidden" name="email[]" class='custo2'>
                     <input type="hidden" name="celular[]" class='custo3'>
                     <input type="hidden" name="cpf[]" class='custo4'>
                     {{-- <input type="hidden" name="palestra[]" class='custo5'> --}}
                     {{-- <input type="hidden" name="{{$camposCred->nome}}" class='custo4'> --}}

                     <div class="col-md-6 col-lg-6 col-sm-12">
                        <br> @if($camposCred->ativo == true)
                        <label for="valor_salvo[]">{{$camposCred->nome}}:</label> @endif @php $strExemple =
                        $camposCred->opcoes; $opcoes = explode(';', $strExemple); if($camposCred->obrigatorio == 1){
                        $camposCred->obrigatorio = 'required'
                        ; }elseif ($camposCred->obrigatorio == 0) { $camposCred->obrigatorio = ' ' ; } @endphp
                        @switch($camposCred->tipo)
                        @case('text') @if($camposCred->nome == 'Nome')
                        <input class="form-control valor <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}" name="valor_salvo[]" id="valor_salvo[]"
                           placeholder="{{$camposCred->nome}}" {{$camposCred->obrigatorio}} maxlength="30"
                           custo='custo1'> @else
                        <input
                           class="form-control {{ $camposCred->nome }} <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}"
                           name="valor_salvo[]" id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                           {{$camposCred->obrigatorio}} maxlength="{{$camposCred->tamanho}}"> @endif
                           <div id="{{$camposCred->id}}" class="invalid-feedback">{{ $camposCred->nome }} já existe! </div>
                            @break
                        @case('number')
                        <input class="form-control valor <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}" name="valor_salvo[]" id="valor_salvo[]"
                           placeholder="{{$camposCred->nome}}" value="{{old('valor_salvo.*')}}"
                           {{$camposCred->obrigatorio}}> 
                           <div id="{{$camposCred->id}}" class="invalid-feedback">{{ $camposCred->nome }} já existe! </div>
                           @break
                        @case('cpf') @if($camposCred->nome
                        == 'Cpf')
                        <input class="form-control cpf valor" name="valor_salvo[]" onblur=validarcpf() id="cpf"
                           placeholder="EX: 999.999.999-99" value="{{old('valor_salvo.*')}}"
                           {{$camposCred->obrigatorio}} custo='custo4'>@else
                        <input class="form-control cpf" name="valor_salvo[]" onblur=validarcpf() id="cpf"
                           placeholder="EX: 999.999.999-99" value="{{old('valor_salvo.*')}}"
                           {{$camposCred->obrigatorio}}> @endif
                        <div id="invalidocpf" class="invalid-feedback">CPF inválido!
                        </div>
                        <div id="existecpf" class="invalid-feedback">CPF já existente!
                        </div> @break @case('cnpj')
                        <input class="form-control cnpj valor" onblur=validarcnpj() name="valor_salvo[]" id="cnpj"
                           placeholder="EX: 00.000.000/0000-00" value="{{old('valor_salvo.*')}}"
                           {{$camposCred->obrigatorio}}>
                        <div id="invalidocnpj" class="invalid-feedback">CNPJ inválido!
                        </div>
                        <div id="existecnpj" class="invalid-feedback">CNPJ já existente!
                        </div>
                        @break @case('email') @if($camposCred->nome == 'Email')
                        <input class="form-control valor email" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="email" placeholder="{{$camposCred->nome}}" {{$camposCred->obrigatorio}} custo='custo2'>
                        @else
                        <input class="form-control email" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="email" placeholder="{{$camposCred->nome}}" {{$camposCred->obrigatorio}}> @endif
                        <div id="existeemail" class="invalid-feedback">Email já existente!
                        </div>
                        @break @case('tel') @if($camposCred->nome == 'Celular')
                        <input class="form-control phone valor <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="valor_salvo[]" placeholder="EX: (99) 99999-9999" {{$camposCred->obrigatorio}}
                           custo='custo3'> @else
                        <input class="form-control phone <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}" value="{{old('valor_salvo.*')}}" name="valor_salvo[]"
                           id="valor_salvo[]" placeholder="EX: (99) 99999-9999" {{$camposCred->obrigatorio}}> @endif
                        @break 
                        <div id="{{$camposCred->id}}" class="invalid-feedback">{{ $camposCred->nome }} já existe! </div>
                        @case('date')
                        <input type="text" class="form-control valor data_ data_calendario"
                           value="{{old('valor_salvo.*')}}" name="valor_salvo[]" id="valor_salvo[]"
                           placeholder="__/__/____" {{$camposCred->obrigatorio}}> @break
                        @case('checkbox') @foreach ($opcoes as $op)
                        <div class="form-check">
                           <input class="form-check-input valor <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}" type="checkbox" name="checkbox[]" id="checkbox[]"
                              value='{{$op}}' {{$camposCred->obrigatorio}}>
                           <label class="form-check-label" for="checkbox[]">{{$op}}</label>
                        </div>
                        @endforeach 
                        <div id="{{$camposCred->id}}" class="invalid-feedback">{{ $camposCred->nome }} já existe! </div>
                        @break @case('select')
                        <select class="form-control <?php if($camposCred->unico == true){ echo 'unico'.$camposCred->id.' '; echo ' unico ';  } ?>" unico="{{$camposCred->id}}" name="valor_salvo[]" value="{{old('valor_salvo.*')}}"
                           id="valor_salvo[]" {{$camposCred->obrigatorio}}>
                           @foreach ($opcoes as $op)
                           <option value='{{$op}}'>{{$op}}</option>
                           @endforeach
                        </select> 
                        <div id="{{$camposCred->id}}" class="invalid-feedback">{{ $camposCred->nome }} já existe! </div>
                        @break @default @endswitch
                     </div>
                     @endif @endforeach

                     @if(count($ingressos) > 0)
                     <div class="col-md-6 col-sm-6 mt-3">
                     <div class="table-responsive" style="border: 1px #ccc solid">
                        <table id="tabelaingresso" class="table table-sm">
                           <thead class="thead-dark">
                              <tr class="ml-2">
                                 <th>Ingressos</th>
                                 <th> <i class="fas fa-shopping-cart"></i> R$ <span id="carrinho" value=0> 0,00 </span></th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($ingressos as $ingresso)
                                 <?php
                                 
                                 $data_fim_ingresso = date("d/m/Y", strtotime($ingresso->data_fim));

                                 ?>
                                 <tr>
                                    <td style="border-top:nome !important;">
                                       <b>{{$ingresso->nome}} <b><br>
                                       <b><span class="text-success preco" value="{{$ingresso->total}}">R$ {{$ingresso->total}} </span> </b><br>  
                                       <span class="text-muted small">{{$ingresso->descricao}}</span>  <br>                                       
                                       <span class="text-muted small">Até {{$data_fim_ingresso}} às {{$ingresso->hora_fim}}</span>
                                    </td>
                                    <td class="mt-4">
                                       <br>
                                       <button type="button" class="btn-sm btn btn-ligth fas fa-minus-circle  menos" id="menos" style="color: #38c172"></button>
                                       <span id="valor" value="0" valor="{{$ingresso->total}}"> 0 </span>
                                       <button type="button" class="btn-sm btn btn-ligth fas fa-plus-circle mais" id="mais" id_ingresso='{{$ingresso->id}}' style="color: #38c172"></button>
                                       <input type="hidden" class="id_ingresso" name="id_ingresso[]" value="">
                                       <input type="hidden" class="qntd" name="qntd[]" value="">
                                       <input type="hidden" class="preco" name="preco[]" value="">                                          
                                    </td>
                                 </tr>                                 
                              @endforeach
                              <input type="hidden" name="total" value="" id="total">
                           </tbody>
                        </table>
                     </div>
                  </div>                  
                  @endif

                  @if(count($mesas) > 0)
                  <div class="col-md-12 col-sm-12 mt-3">
                  <div class="table-responsive" style="border: 1px #ccc solid">
                     <table id="tabelaingresso" class="table table-sm">
                        <thead class="thead-dark">
                           <tr class="">
                              <th>Mesas</th>
                              <th>  </th>
                              <th> <i class="fas fa-shopping-cart"></i> R$ <span id="carrinhomesa" value=0> 0,00 </span></th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($mesas as $mesa)
                              <tr>
                                 <td style="border-top:nome !important;">
                                    @for($m = 1 ; $m <= $mesa->qntd ; $m++)
                                       <button type="button" class="btn-sm btn btn-success mb-1 mesa" id="mesas" value="{{$m}}" onclick="({{$m}})" mesa="selecionar"><b>{{$m}}</b></button>                                       
                                    @endfor
                                 </td>
                                 <td class="mt-4"> </td>  
                                 <td>
                                    <img id="img" alt="Mapa Mesas" height="200" width="350" @if(isset($mesa->url_imagem)) src="/storage/{{$mesa->url_imagem}}" @endif/>
                                </td>
                              </tr>     
                              <tr>
                                 <td class=" "> 
                                    Assentos por mesa: {{$mesa->assentos}}
                                 </td>
                                 <td class=" "> </td>
                                 <td class=" "> 
                                    <span id="valormesa" value='{{$mesa->valor}}' valor='{{$mesa->valor}}' >Valor por mesa: {{$mesa->valor}}</span>
                                 </td>
                              </tr> 
                              <tr>
                                 <td class=" "> 
                                    Descrição: {{$mesa->descricao}}
                                 </td>
                                 <td class=" "> </td>
                                 <td class=" "> </td>
                              </tr>                         
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>    
               <input type="hidden" id="id_mesa" class="id_mesa" name="id_mesa" value="{{$mesa->id}}">
               <input type="hidden" id="qualmesa" class="qualmesa" name="qualmesa" value="">
               <input type="hidden" id="valormesainput" class="valormesainput" name="valormesainput" value="" >

               <!-- The Modal -->
               <div id="myModal" class="modal">

                  <!-- The Close Button -->
                  <button type="button" class="close" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>

                  <!-- Modal Content (The Image) -->
                  <img class="modal-content" id="img01">

                  <!-- Modal Caption (Image Text) -->
                  <div id="caption"></div>
               </div>

               @endif



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
                              <table id="tabelaingresso" class="table table-striped table-ordered">
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
            <div id='load' class="alert alert-warnig alert-dismissible fade show" role="alert"
               style="margin-top: 20px; display: none;">
               <center>
                  <img src="/storage/imagens/load.gif" alt="load" height="40px" width="40px">
                  <h3 style="color: #ccc"> Aguarde ...<h3>
               </center>
            </div>
            <br>
            <div class="card-footer card-footer-form">
               <button type="submit" id="enviar" name="enviar" value="Enviar"
                  class="btn btn-success btn-md float-right"><i class="fas fa-paper-plane"></i> Salvar </button>
            </div>
            </form>
            @else
            <div class="alert alert-warning" role="alert">
               Formulário indisponivel no momento!!!
            </div>
            @endif

         </div>
         <div class="row caixa-map">
            <div class="col-12 col-sm-12">
               <h3>Local:</h3>
               <h5><i class="fas fa-map-marker-alt"></i> {{$exibir->nome_local}} - {{$exibir->endereco_local}}
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
<script src="{{ asset('js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap-datepicker.pt-BR.min.js')}}" type="text/javascript"></script>

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
            //$('.rg').mask('AA-99.999.999'); // Máscara para RG
            $('.money').mask('000.000.000.000.000,00', {
                reverse: true
            }); // Máscara para dinheiro
            $('.data_').mask('99/99/9999'); 
        });

        setTimeout(function() {
            $("#alert").slideUp('slow');
        }, 7000);

        $(".palestras").click(function() {
            var nome = $(this).val();
            var nome_ = '.'.concat(nome);
            var nome_ = nome_.replace(/ /g, '');
            var nomes;
            var i;
            console.log(nome);
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

                        if ($(nome_).prop("checked") == true) {
                            for (var i = 0; i < nomes.length; i++) {
                                var nomes2 = nomes[i];
                                if (nome != nomes2) {
                                    console.log(nome);
                                    console.log(nome[i]);
                                    var check2 = nomes[i];
                                    var check2 = check2.replace(/ /g, '');
                                    var check2 = '.'.concat(check2);
                                    console.log(check2);
                                    $(check2).attr("disabled", true);
                                    $(check2).prop("checked", false);
                                }
                            }
                        } else {
                            for (var i = 0; i < nomes.length; i++) {
                                var nomes2 = ' '.concat(nomes[i]);
                                if (nome != nomes2) {
                                    console.log(nome);
                                    console.log(nome[i]);
                                    var check2 = nomes[i];
                                    var check2 = check2.replace(/ /g, '');
                                    var check2 = '.'.concat(check2);
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

        $(document).ready(function() {
            $(".unico").blur("input", function() {
               var textoDigitado = $(this).val();
               var valor_campo = $(this).attr("unico");
                //alert(textoDigitado + inputCusto);
               if (textoDigitado.length > 0) {
                     $.ajax({
                        type: 'get',
                        url: '/unicocred',
                        data: {
                           id_evento:     $('#id_evento').val(),
                           id_campo_cred: valor_campo,
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

        $("#cpf").keyup(function() {
            if ($('#cpf').val().length >= 14) {
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
        });

        $('.data_calendario ').datepicker({	
				format: "dd/mm/yyyy",	
				language: "pt-BR",
				startDate: '+0d',
         });
         
         $(".mais").click(function() {
            
            var carrinho   = Number($('#carrinho').val());
            var numero     = Number($(this).prev().text());
            var preco      = Number($(this).prev().attr('valor'));
            var id         = $(this).attr('id_ingresso');

            // PREÇO VALOR DO INGRESSO
            // NUMERO VALOR DA QUANTIDADE DE INGRESSO SELECIONADO 
            if(numero == 1){
               exit();
            }
            numero   = numero + 1;  
            total    = preco * numero;
            total    = preco + carrinho;
            
            $(this).next().val(id);
            $(this).next().next().val(numero);
            $(this).next().next().next().val(preco);

            //$('#total').val(total);

            $('#carrinho').text(total);
            $('#carrinho').val(total);

            $('#total').attr("value", total);
            $(this).prev().text(numero);
            $(this).prev().attr("value", numero);

         });

         $(".menos").click(function() {
            var carrinho   = Number($('#carrinho').val());
            var numero     = Number($(this).next().text());
            var preco      = Number($(this).next().attr('valor'));
            var id         = $(this).next().attr('id'); 

            if(numero == 1){
               $(this).next().next().next().val('');
               $(this).next().next().next().next().val('');
               $(this).next().next().next().next().next().val('');              
            }
            if(numero == 0){
               $(this).next().next().next().next().val('');
               exit();
            }

            numero   = numero - 1;
            total    = preco * numero;
            total    = carrinho - preco;

            $('#carrinho').text(total);
            $('#carrinho').val(total);
            $('#total').attr("value", total);

            $(this).next().text(numero);
            $(this).next().attr("value", numero);

         });

         function formatReal(int) {
            var tmp = int + '';
            tmp = tmp.replace(/([0-9]{2})$/g, ".$1,$2");
            if (tmp.length > 6)
                  tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
            return tmp;
         }

         // Get the modal
         var modal = document.getElementById("myModal");

         // Get the image and insert it inside the modal - use its "alt" text as a caption
         var img = document.getElementById("img");
         var modalImg = document.getElementById("img01");
         var captionText = document.getElementById("caption");
         img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
         }

         $(".close").click(function() {
            $('#myModal').hide();
         });

         $(".mesa").click(function() {
            var id         = $(this).attr("id");
            var name       = $(this).attr("name");
            var mesa       = $(this).attr("mesa");
            var valormesa  = $('#valormesa').attr("valor");
            var qualmesa   = $(this).val();

            console.log(id);
            console.log(name);
            console.log(mesa);
            //console.log(valor);
            console.log(valormesa);
            console.log(qualmesa);


            //$id = id;
            // se o usuario estiver ativo, desative ele, você precisa adicionar um ajax para enviar a acao para o php, pode ser um update where cliente = nomeUsuario.
            if (mesa == "selecionar") {

               $('.mesa').removeClass("btn-danger").addClass("btn-success");
               $('.mesa').attr("mesa", 'selecionar');
               $('.mesa').attr("title", 'Selecionar Mesa');            


               $(this).removeClass("btn-success").addClass("btn-danger");
               $(this).attr("mesa", 'selecionado');
               $(this).attr("title", 'Mesa Selecionada');

               $('#qualmesa').val(qualmesa);
               $('#valormesainput').val(valormesa);
               $('#carrinhomesa').val(valormesa);
               $('#carrinhomesa').text(valormesa);

            } else {
               $(this).removeClass("btn-danger").addClass("btn-success");
               $(this).attr("mesa", 'selecionar');
               $(this).attr("title", 'Selecionar Mesa');

               $('#qualmesa').val();
               $('#valormesainput').val();
               $('#carrinhomesa').val();
               $('#carrinhomesa').text('0,00');
            }
        });

</script>

</html>