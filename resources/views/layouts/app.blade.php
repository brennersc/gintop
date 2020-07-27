<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>Gin Top</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,minimal-ui">


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- include libraries(jQuery, bootstrap) -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/core.js"></script>

    <style>
        
        body {
            padding-bottom: 50px;
        }

    </style>
</head>
<body>
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-primary shadow-sm"> --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-defaut shadow">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img alt="Logo"  width="150px" height="40px" src="../storage/imagens/logo-toptecnologia.png">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>

<script src="{{ asset('js/app.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.mask.js')}}"  type="text/javascript"></script>
<script src="{{ asset('js/summernote.js')}}"  type="text/javascript"></script>
<script src="{{ asset('js/procuraCEP.js')}}"  type="text/javascript"></script>

@hasSection('javascript')
    @yield('javascript')
        <script type="text/javascript">

            $(document).ready(function() {
                    $('.phone').mask('(00) 00000-0000'); // Máscara para TELEFONE
                    $('.cpf').mask('000.000.000-00', {
                    reverse: true
                    }); // Máscara para CPF
                    $('.cnpj').mask('00.000.000/0000-00', {
                    reverse: true
                    }); // Máscara para CPF
                    $('.cep').mask('00000-000'); // Máscara para CEP
                    //$('.card').mask('0000 0000 0000 0000'); // Máscara para card
                    $('.rg').mask('AA-99.999.999'); // Máscara para RG
                    $('.money').mask('000.000.000.000.000,00', {
                    reverse: true
                    }); // Máscara para dinheiro
                    $('.data_').mask('00/00/0000', {
                    reverse: true
                    }); // Máscara para data
                    $('.dimensoes').mask('00;00', {
                    reverse: true
                    }); // Máscara para data

                    $('.cartao').mask('0000 0000 0000 0000', {
                    reverse: true
                    }); // Máscara para data
                    $('.ano').mask('00', {
                    reverse: true
                    });
                    $('.mes').mask('00', {
                    reverse: true
                    });
                    $('.cod').mask('000', {
                    reverse: true
                    });
            });
            $(document).ready(function() {
                $('.note-statusbar').remove();
                $('.note-resizebar').remove();
                document.getElementById("summernote").disabled;

                $('#summernote').summernote({
                        toolbar: [],
                });
        });
        </script>
    @endif

</body>
</html>