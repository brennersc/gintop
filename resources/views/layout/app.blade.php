<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/all.css') }}" rel="stylesheet">
        <link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
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
        @component('navbar.componente_navbar', [ "current" => $current ])
        @endcomponent
        <br>
        
    <div class="container">
        <main role="main">
            @hasSection('body')
                @yield('body')
            @endif
        </main>
    </div>
    

    <script src="{{ asset('js/app.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.mask.js')}}"  type="text/javascript"></script>
    <script src="{{ asset('js/summernote.js')}}"  type="text/javascript"></script>
    <script src="{{ asset('js/procuraCEP.js')}}"  type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js')}}"  type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap-datepicker.pt-BR.min.js')}}"  type="text/javascript"></script>

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
                   // 
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

                    $('.cartao').mask('0000 0000 0000 0000'); // Máscara para card
            });
            $(document).ready(function() {
                $('.note-statusbar').remove();
                $('.note-resizebar').remove();
                document.getElementById("summernote").disabled;

                $('#summernote').summernote({
                        toolbar: [],
                });
        });

        $('.data_calendario ').datepicker({	
				format: "dd/mm/yyyy",	
				language: "pt-BR",
				startDate: '+0d',
			});


    </script>
    @endif
</body>
</html>