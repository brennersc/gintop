@php

$cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", Auth::guard('visitantes_login')->user()->cpf);

//pegar valores dos visitantes ja cadastrados
//nome email celular e todos valores salvos ja cadastrados juntos
$items = DB::table('credenciamentos')
->select('palestras','nome','email','cpf','celular')
->selectRaw('GROUP_CONCAT(valor_salvo ORDER BY id ASC SEPARATOR ";") as valor_salvo')
->selectRaw('GROUP_CONCAT(id ORDER BY id ASC SEPARATOR ";") as id')
->where('id_evento', [$exibir->id])
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
            <h3>Editar informações cadstradas no evento <b>{{$exibir->nome}}<b></h3>
            <div class="row">
                {{-- @foreach ($eventos as $ev)
                @php
                $data_inicio = date("d M", strtotime($ev->data_inicio));

                $inicio = date("d/m/Y", strtotime($ev->data_inicio));
                $fim = date("d/m/Y", strtotime($ev->data_fim));
                @endphp --}}

                <div class="col-md-12">
                    <div class="card mb-12">
                        @if(isset($exibir->url_imagem))
                        <img class="card-img-top" height="250px" width="190px" src="/storage/{{$exibir->url_imagem}}"
                            alt="Banner do evento {{$exibir->nome}}">
                        @else
                            <img class="card-img-top" height="250px" width="190px" src="../storage/imagens/bannertop.png"
                            alt="Banner do evento {{$exibir->nome}}">
                        @endif

                        <div class="card-body">
                            <!-- ----------------------------------- começo --------------------------------------------------- -->

                            @foreach ($items as $tabela)
                            @php
                            //$idcamp = $id_visitante[$key];

                            $cod = str_replace(';' ,'',$tabela->id);
                            // $cod = $tabela->id;
                            // $cod = base64_encode($cod);

                            $pegaid = $tabela->id;
                            $idcampo = explode(';', $pegaid);

                            $strExemple = $tabela->valor_salvo;
                            $valor = explode(';', $strExemple);

                            $j = 0;
                            @endphp

                            <!-- EDIAR visitente -->


                            <form class="form" id="formCredencianto" action="/editarvisitantes/" method="get">
                                @csrf
                                <div class="row">
                                    @foreach($campoCred as $key => $camposCred)
                                    @php
                                    $idcamp = $idcampo[$key];
                                    $valor2 = $valor[$key];

                                    if((preg_replace("/[0-9]/" , "" , $valor2) === 'null')){
                                    $valor2 = '';
                                    }
                                    $j++;
                                    @endphp
                                    <input type="hidden" name="id[]" value="{{$idcamp}}">
                                    <input type="hidden" name="id_campo_cred[]" value="{{$camposCred->id}}">
                                    <input type="hidden" name="cracha[]" value="{{$camposCred->cracha}}">
                                    <input type="hidden" name="id_evento[]" value="{{$camposCred->id_evento}}">
                                    <input type="hidden" name="campo[]" value="{{$camposCred->nome}}">
                                    <input type="hidden" name="tipo[]" value="{{$camposCred->tipo}}">
                                    <input type="hidden" name="nome[]" class='custo1' value="{{$tabela->nome}}">
                                    <input type="hidden" name="email[]" class='custo2' value="{{$tabela->email}}">
                                    <input type="hidden" name="celular[]" class='custo3' value="{{$tabela->celular}}">
                                    <input type="hidden" name="cpf[]" class='custo4' value="{{$tabela->cpf}}" readonly>

                                    <div class="col-md-6 col-12">
                                        <br>
                                        <label for="valor_salvo[]">{{$camposCred->nome}}:</label>
                                        @php
                                        $strExemple = $camposCred->opcoes;
                                        $opcoes = explode(';', $strExemple);
                                        if($camposCred->obrigatorio == 1){
                                        $camposCred->obrigatorio = 'required' ;
                                        }elseif ($camposCred->obrigatorio == 0) {
                                        $camposCred->obrigatorio = ' ' ;
                                        }
                                        @endphp

                                        @switch($camposCred->tipo)
                                        @case('text')
                                        <input type="text" class="form-control" name="valor_salvo[]" id="valor_salvo[]"
                                            placeholder="{{$camposCred->nome}}" {{$camposCred->obrigatorio}}
                                            maxlength="{{$camposCred->tamanho}}" value="{{$valor2}}">

                                        @break
                                        @case('number')
                                        <input type="number" class="form-control" name="valor_salvo[]"
                                            id="valor_salvo[]" placeholder="{{$camposCred->nome}}"
                                            {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                        @break
                                        @case('cpf')
                                        @if($camposCred->nome == 'Cpf')
                                        <input type="text" class="form-control cpf valor" name="valor_salvo[]"
                                            id="cpf{{$idcamp}}" onblur='validarcpf{{$idcamp}}()'
                                            placeholder="EX: 999.999.999-99" {{$camposCred->obrigatorio}} custo='custo4'
                                            value="{{$valor2}}" readonly>
                                        @else
                                        <input type="text" class="form-control cpf" name="valor_salvo[]"
                                            id="cpf{{$idcamp}}" onblur=validarcpf{{$idcamp}}()
                                            placeholder="EX: 999.999.999-99" {{$camposCred->obrigatorio}}
                                            value="{{$valor2}}" readonly>
                                        @endif
                                        <div id="invalidocpf{{$idcamp}}" class="invalid-feedback">CPF
                                            inválido!
                                        </div>
                                        <div id="existecpf{{$idcamp}}" class="invalid-feedback">CPF já
                                            existente!
                                        </div>
                                        @break
                                        @case('cnpj')
                                        <input type="text" class="form-control cnpj" name="valor_salvo[]"
                                            id="valor_salvo[]" placeholder="EX: 00.000.000/0000-00"
                                            {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                        @break
                                        @case('email')
                                        <input type="email" class="form-control" name="valor_salvo[]" id="valor_salvo[]"
                                            placeholder="{{$camposCred->nome}}" {{$camposCred->obrigatorio}}
                                            value="{{$valor2}}">

                                        @break
                                        @case('tel')
                                        <input type="text" class="form-control phone" name="valor_salvo[]"
                                            id="valor_salvo[]" placeholder="EX: (99) 99999-9999"
                                            {{$camposCred->obrigatorio}} value="{{$valor2}}">

                                        @break
                                        @case('date')
                                        <input type="taext" class="form-control data_ data_calendario" name="valor_salvo[]" id="valor_salvo[]"
                                            placeholder="__/__/____" {{$camposCred->obrigatorio}} value="{{$valor2}}"
                                            maxlength="10">

                                        @break
                                        @case('checkbox')
                                        <br>
                                        @php
                                        $str = $camposCred->opcoes;
                                        $opcoes2 = explode(';', $str);
                                        $opcoes3 = explode(' / ', $valor2);

                                        $resultado = array_intersect($opcoes2, $opcoes3);
                                        $result = array_diff($opcoes2, $opcoes3);
                                        @endphp

                                        @foreach ($resultado as $resul)
                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="checkbox[]"
                                                id="checkbox[]" aria-checked="true" value='{{$resul}}' checked
                                                {{$camposCred->obrigatorio}}>
                                            <label class="form-check-label" for="checkbox[]">{{$resul}}</label>
                                        </div>
                                        @endforeach
                                        @foreach ($result as $res)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="checkbox[]"
                                                id="checkbox[]" value='{{$res}}' {{$camposCred->obrigatorio}}>
                                            <label class="form-check-label" for="checkbox[]">{{$res}}</label>

                                        </div>
                                        @endforeach


                                        @break
                                        @case('select')
                                        <select class="form-control" name="valor_salvo[]" id="valor_salvo[]"
                                            {{$camposCred->obrigatorio}}>
                                            @foreach ($opcoes as $op)
                                            @if($valor2 == $op)
                                            <option value='{{$op}}' selected>{{$op}}</option>
                                            @endif
                                            @if($valor2 != $op)
                                            <option value='{{$op}}'>{{$op}}</option>
                                            @endif
                                            @endforeach
                                        </select>

                                        @break

                                        @default
                                        @endswitch

                                    </div>
                                    @endforeach

                                    <?php
                                                $i = 0;
                                                $contadorsala = count($salas);


                                                ?>
                                    @if(count($salas) > 0)

                                    <div class="col-md-6 col-12">
                                        <br>
                                        <label for="Palestras">Palestras: </label><br>
                                        <div class="">
                                            <input type="hidden" name="contador" id="contador" class="contador"
                                                value='{{$contadorsala}}'>
                                            {{-- 
                                            
                                            @foreach($salaresultado as $salaresults)
                                                <input type="checkbox"  name="palestras[]"  value='{{$salaresults}}'>
                                            {{$salaresults}}
                                            @endforeach
                                            --}}

                                            <?php
                                                $strSalas       = $tabela->palestras;
                                                $paletra        = explode(' / ', $strSalas);                                                                                                                            
                                                
                                                $nomeSalas = Sala::selectRaw('GROUP_CONCAT(nome ORDER BY id ASC SEPARATOR ";") as nome')
                                                                ->selectRaw('GROUP_CONCAT(id ORDER BY id ASC SEPARATOR ";") as id')
                                                                ->selectRaw('GROUP_CONCAT(data_inicio ORDER BY id ASC SEPARATOR ";") as data_inicio')
                                                                ->selectRaw('GROUP_CONCAT(hora_inicio ORDER BY id ASC SEPARATOR ";") as hora_inicio')
                                                                ->selectRaw('GROUP_CONCAT(hora_fim ORDER BY id ASC SEPARATOR ";") as hora_fim')
                                                                ->where('id_evento', $camposCred->id_evento)->first();
                                                
                                                $strSalas       = explode(';', $nomeSalas->nome);
                                                $strdata_inicio = explode(';', $nomeSalas->data_inicio);
                                                $strhora_inicio = explode(';', $nomeSalas->hora_inicio);
                                                $strhora_fim    = explode(';', $nomeSalas->hora_fim);
                                                $strid          = explode(';', $nomeSalas->id);

                                                $iguaisSalas      = array_intersect($strSalas, $paletra);
                                                $difSalas         = array_diff($strSalas, $paletra);

                                            ?>

                                            @foreach($iguaisSalas as $key => $iguais)

                                            <input type="checkbox" onclick="()" name="palestras[]" id="palestras"
                                                class="palestras valor {{ str_replace(' ','',$iguais) }}"
                                                value='{{$iguais}}' custo='custo5' checked>
                                            <span style="font-size: 1.125rem;"> {{$iguais}}</span>
                                            <br>                                            
                                            @endforeach
                                            @foreach($difSalas as $dif)

                                            <input type="checkbox" onclick="()" name="palestras[]" id="palestras"
                                                class="palestras valor {{ str_replace(' ','',$dif) }}" value='{{$dif}}'
                                                custo='custo5'>
                                            <span style="font-size: 1.125rem;"> {{$dif}}</span>

                                            <br>
                                            @endforeach

                                        </div>
                                    </div>

                                    @endif
                                </div>

                                <br>
                                <div class="modal-footer">
                                    <a href="{{ route('visitante') }}" class="btn btn-secondary"
                                        data-dismiss="modal">Voltar</a>
                                    <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                                </div>
                            </form>
                        </div>


                        <!-- ----------------------------------- fim --------------------------------------------------- -->

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


        $("input").click(function() {
            var nome    =   $(this).next().text();
            var nome_   =   '.'.concat(nome);
            var nome_   =   nome_.replace(/ /g,'');
            var nomes;
            var i;
            //console.log(nome);
            $.ajax({
                type: 'post',
                url: '/visitante/horas',
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
                                    //console.log(nome);
                                    //console.log(nome[i]);
                                    var check2 = nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(check2);
                                    //console.log(check2);
                                    $(check2).attr("disabled", true); 
                                    $(check2).prop("checked", false);                                            
                                }                   
                            }
                        }else{
                            for(var i = 0; i < nomes.length; i++){ 
                                var nomes2 =  ' '.concat(nomes[i]);
                                if(nome != nomes2){
                                    //console.log(nome);
                                    //console.log(nome[i]);
                                    var check2 =  nomes[i];                                   
                                    var check2 = check2.replace(/ /g,'');
                                    var check2 =  '.'.concat(check2);
                                    //console.log(check2);
                                    $(check2).attr("disabled", false); 
                                    //$(check2).prop("checked", true);                                            
                                }
                            }
                        }
                    }
                }
            });
           
        });

</script>
@endsection