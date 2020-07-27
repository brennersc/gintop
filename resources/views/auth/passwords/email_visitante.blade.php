@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('sucesso'))
            <div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
                style="box-shadow: 0px 0px 15px #A4A4A4;">
                <h5> Lhe enviamos um Email com uma nova senha! </h5>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @if(session('erro'))
            <div id='alert' class="alert alert-danger alert-dismissible fade show" role="alert"
                style="box-shadow: 0px 0px 15px #A4A4A4;">
                <h5> CPF não encontrado! </h5>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div id='load' class="alert alert-warnig alert-dismissible fade show" role="alert"
                style="margin-top: 20px; display: none ;">
                <center>
                    <img src="/storage/imagens/load.gif" alt="load" height="40px" width="40px">
                    <h3 style="color: #ccc"> Aguarde ...<h3>
                </center>
            </div>
            <br>
            <div class="card">
                <div class="card-header">{{ __('Recuperção de Senha do Visitante') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('remember') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="cpf" class="col-md-4 col-form-label text-md-right">{{ __('CPF') }}</label>

                            <div class="col-md-6">
                                <input id="cpf" data-mask="000.000.000-00" placeholder="___.___.___-__" type="text"
                                    class="form-control cpf @error('cpf') is-invalid @enderror" name="cpf"
                                    value="{{ old('cpf') }}" required autocomplete="cpf" autofocus>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id="button" class="btn btn-primary">
                                    Enviar link de redefinição de senha
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(this).submit("form", function() {
           $('#load').show();
           $("#button").text("Enviando...");
           $("#button").attr("disabled", true);
        });
     });

     setTimeout(function() {
            $("#alert").slideUp('slow');
    }, 7000);
</script>

@endsection