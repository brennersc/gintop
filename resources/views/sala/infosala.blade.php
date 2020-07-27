@php @endphp @extends('layout.app', ["current" => "evento"]) @section('body') @if(session('mensagem'))
<div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
    style="box-shadow: 0px 0px 20px #A4A4A4;">
    {{ session('mensagem') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<style>
    .box {
        padding: 50px;
    }
</style>

<div class="card border">
    <div class="card-header">
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <h3>Sala - <b>{{$exibir->nome}}</b></h3>
            </div>
            <div class="col-md-3 col-sm-12">
                <a href="{{route('excelsala', ['id' => $exibir->id])}}" class="btn btn-md btn-outline-success float-right">
                    <i class="fas fa-file-export"></i> Exportar</a>
        </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5><b>Local:</b> {{$exibir->nome_local}}</h5>
            </div>
            <div class="col-md-6 col-12">
                <h5><b>Palestrante:</b> {{$exibir->palestrante}}</h5>
            </div>
            <div class="col-md-6 col-12">
                <h5><b>Quantidade:</b> {{$exibir->quantidade}}</h5>
            </div>
            <div class="col-md-6 col-12">
                <h5><b>Data Inicio:</b> {{$sal_inicio = date("d/m/Y", strtotime($exibir->data_inicio))}}</h5>
            </div>
            <div class="col-md-6 col-12">
                <h5><b>Data Fim:</b> {{$sal_fim = date("d/m/Y", strtotime($exibir->data_fim))}}</h5>
            </div>
            <div class="col-md-6 col-12">
                <h5><b>Hora Inicio:</b> {{$exibir->hora_inicio}}</h5>
            </div>
            <div class="col-md-6 col-12">
                <h5><b>Hora Fim:</b> {{$exibir->hora_fim}}</h5>
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="box">
            <form id="form_procurar_codigo" action="">
                <label for="leitor">
                    <h3>Código</h3>
                </label>
                <input type="hidden" name="id_sala" value="{{$exibir->id}}" id="id_sala">
                <input type="text" class="form-control" name="leitor" value="" id="leitor" placeholder=""
                    autofocus="autofocus">
            </form>
            <br>
            <div id="div">

            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-9">
                <a class="btn btn-sm btn-outline-primary" role="button"
                    href="JavaScript: window.history.back();">Voltar</a>
            </div>
        </div>
    </div>
</div>


@endsection @section('javascript')
<script type="text/javascript">
    $('#leitor').on('keypress', function(event) {
        //Tecla 13 = Enter
        if (event.which == 13) {
            //cancela a ação padrão
            event.preventDefault();
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    $(document).ready(function() {
        $("#leitor").focus();
    });

    //Buscar codigo 
    
        $("#leitor").keyup(function() {
            $('#div').html('');
            if ($('#leitor').val().length >= 8) {
                $.ajax({
                    type: 'POST',
                    url: '/codigo',
                    data: $('#form_procurar_codigo').serialize(),
                    success: function(data) {
                            $('#div').html(data);                                                 
                    }
                });
                setTimeout(function() {
                        $("#alert").slideUp('slow'),
                        $('#div').html(''),
                        $('#leitor').val('')
                        $("#leitor").focus();
                }, 7000);
            }
        });
    

</script>

@endsection