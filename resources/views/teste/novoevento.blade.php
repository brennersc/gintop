<?php

use App\Empresa;

$empresa = Empresa::all();

?>
@extends('layout.app', ["current" => "evento"])

@section('body')

<style>

</style>


{{-- @if(isset($errors))
    {{var_dump($errors)}}
@endif --}}

<div class="card border">
    <div class="card-body">
        <form action="/salvarevento" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="nome">Nome do evento *</label>
                    <input type="text" class="form-control {{$errors->has('nome') ? 'is-invalid' : ''}}" name="nome"
                        value="{{old('nome')}}" id="nome" placeholder="Nome">
                    @if($errors->has('nome')) <div class="invalid-feedback">{{$errors->first('nome')}}</div>@endif
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="empresa" class="control-label">Empresa *</label>
                    <select class="form-control {{$errors->has('empresa') ? 'is-invalid' : ''}}" id="empresa"
                        name="empresa">
                        <option value=""></option>
                        @foreach ($empresa as $empre)
                        <option @if(old('empresa')==$empre->id) selected @endif
                            value="{{$empre->id}}">{{$empre->nome_fantasia}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('empresa')) <div class="invalid-feedback">{{$errors->first('empresa')}}</div>@endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="data_ini">Data de Início *</label>
                    <input type="date" class="form-control {{$errors->has('data_inicio') ? 'is-invalid' : ''}}"
                        name="data_inicio" value="{{old('data_inicio')}}" id="data_ini" placeholder="__/__/____">
                    @if($errors->has('data_inicio')) <div class="invalid-feedback">{{$errors->first('data_inicio')}}
                    </div>@endif
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="data_fim">Data de Encerramento *</label>
                    <input type="date" class="form-control {{$errors->has('data_fim') ? 'is-invalid' : ''}}"
                        name="data_fim" value="{{old('data_fim')}}" id="data_fim" placeholder="__/__/____">
                    @if($errors->has('data_fim')) <div class="invalid-feedback">{{$errors->first('data_fim')}}</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="hora_inicio">Horário de Início *</label>
                    <input type="time" class="form-control {{$errors->has('hora_inicio') ? 'is-invalid' : ''}}"
                        name="hora_inicio" value="{{old('hora_inicio')}}" id="hora_inicio"
                        placeholder="Horário de Início">
                    @if($errors->has('hora_inicio')) <div class="invalid-feedback">{{$errors->first('hora_inicio')}}
                    </div>@endif

                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="hora_fim">Horário de Encerramento *</label>
                    <input type="time" class="form-control {{$errors->has('hora_fim') ? 'is-invalid' : ''}}"
                        name="hora_fim" value="{{old('hora_fim')}}" id="hora_fim" placeholder="Horário de Encerramento">
                    @if($errors->has('hora_fim')) <div class="invalid-feedback">{{$errors->first('hora_fim')}}</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">

                    <label for="slug">Endereço para inscrição *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">gintop.com.br/</div>
                        </div>
                        <input type="text" class="form-control {{$errors->has('slug') ? 'is-invalid' : ''}}" name="slug"
                            value="{{old('slug')}}" onkeyup="this.value = Trim( this.value )" pattern="[a-zA-Z0-9]+"
                            id="slug" placeholder="Link">
                        @if($errors->has('slug')) <div class="invalid-feedback">{{$errors->first('slug')}}</div>@endif
                    </div>
                    <small class="text-muted">Sem espaços e caracteres</small>
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="tamanho_impressao">Dimensões para impressão *</label>
                    <input type="text" class="form-control {{$errors->has('tamanho_impressao') ? 'is-invalid' : ''}}"
                        name="tamanho_impressao" value="{{old('tamanho_impressao')}}" id="tamanho_impressao"
                        placeholder="Dimensões para impressão">
                    @if($errors->has('tamanho_impressao')) <div class="invalid-feedback">
                        {{$errors->first('tamanho_impressao')}}</div>@endif
                    <small class="text-muted">Separar por ; (ponto e virgula)</small>
                </div>
            </div>
            <div class="row div">
                <div class="col-12 col-md-6 form-group">
                    <label for="controle">Controle de Acesso?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input sim" type="radio" name="controle" id="controle" value="sim">
                        <label class="form-check-label" for="controle">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input nao" type="radio" name="controle" id="controle" value="nao"
                            checked>
                        <label class="form-check-label" for="controle">
                            Não
                        </label>
                    </div>
                    <div class="codigo" style="display: none">
                        <label for="controle">Tipo de código</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="codigo" id="barcode" value="qr">
                            <label class="form-check-label" for="barcode">
                                QR <i class="fas fa-qrcode" style="font-size: 50px"></i>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="codigo" id="barcode" value="barcode">
                            <label class="form-check-label" for="barcode">
                                Código de Barra <i class="fas fa-barcode" style="font-size: 50px"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="custom-file">
                        <label for="banner" class="custom-file-label">Banner</label>
                        <input type="file" class="custom-file-input" name="url_imagem" id="banner"
                            onchange="readURL(this,'img');" value="{{old('url_imagem')}}" placeholder="Banner">
                        <small class="text-muted">O tamanho recomendado é de 1600x900 (o mesmo para capas de eventos do
                            Facebook)
                        </small>
                        <div class="row img">
                            <div class="col-md-6"><img id="img" height="100" /></div>
                            <div class="col-md-6"><a href="#"><i
                                        style="font-size: 20px; padding: 10px; @if(!isset($evento->url_imagem))display:none @endif"
                                        class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-12 col-md-6 form-group">
                    <label for="local">Nome do Local</label>
                    <input type="text" class="form-control" name="nome_local" value="{{old('nome_local')}}" id="local"
                        placeholder="Local">
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="endereco">Endereço do Local</label>
                    <input id="pac-input" class="controls form-control" type="text" name='endereco_local'
                        value="{{old('endereco_local')}}" placeholder="Buscar Endereço">

                </div>
                <div id="map"></div>
                
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="salas">Salas</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sala" @if(old('sala')=='sim' ) checked @endif
                            id="salas" value="sim">
                        <label class="form-check-label" for="salas">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sala" id="salas" value="nao"
                            @if(old('sala')=='nao' ) checked @endif>
                        <label class="form-check-label" for="salas">
                            Não
                        </label>
                    </div>
                    
                </div>

                <div class="col-12 col-md-12 form-group">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                data-target="#eventopago" >Tornar o evento pago?</button>
                </div>
            </div>
            

            <div class="row div" >
                                        <div class="col-12 col-md-6 form-group">
                                                <label for="mesadoevento"> O evento possui mesa?</label>
                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input sim" type="radio" name="mesadoevento" id="mesadoevento" value="simmesa">
                                        <label class="form-check-label" for="mesadoevento">
                                            Sim
                                        </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input nao" type="radio" name="mesadoevento" id="mesadoevento" value="naomesa">
                                            <label class="form-check-label" for="mesadoevento">
                                                Não
                                            </label>
                                        </div>                  
                                 </div>
                                 <div class="quantidademesas" style="display: none">
                                    <label for="mesadoevento">Quantidade de mesas</label><br>
                                    <div class="form-check form-check-inline">
                                    <label for="local">Quantidade permitida por compra</label>
                                    <input type="text" class="form-control" name="quantidademesas" value="2" id="quantidademesas"
                                        placeholder="Quantidade">
                                    </div>
                                </div>
             </div>


            <div class="row">
                <div class="col-12 col-md-12 form-group">
                    <label for="descricao">Descrição</label><br>
                    <textarea name="descricao" id="summernote">{{ old('descricao') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
            <a href="/evento/" class="btn btn-danger btn-sm">Cancelar</a>
    </div>
    </form>
</div>

<!-----------------------------------------MODAL EVENTO PAGO ------------------------------------------>

<div class="modal fade bd-example-modal-lg" id="eventopago" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="TituloModalCentralizado">Informações de pagamento </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                    <div class="modal-body">
                        <div class="row">  
                                        <div class="col-12 col-md-6 form-group">
                                        <label for="local">Nome do ingresso?</label>
                                        <input type="text" class="form-control" name="nomeingresso" value="{{old('nomeingresso')}}" id="nomeingresso"
                                            placeholder="Nome do ingresso">
                                        </div>
                                            <div class="col-12 col-md-6 form-group">
                                        <label for="local"> Quantidade de ingressos?</label>
                                        <input type="number" class="form-control" name="quantidade" value="{{old('quantidade')}}" id="quantidade"
                                            placeholder=" ">
                                        </div>

                                        <div class="col-12 col-md-6 form-group">
                                        <label for="local"> Qual o valor do ingresso ?</label>
                                        <input type="text" class="form-control money" name="valor" value="{{old('valor')}}" id="valor"
                                            placeholder="R$">
                                        </div>

                                        <div class="col-12 col-md-6 form-group">
                                        <label for="local">Quantidade permitida por compra: </label>
                                        <input type="number" class="form-control" name="quantidade" value="{{old('quantidade')}}" id="quantidade"
                                            placeholder=" ">
                                        </div>                      

                                            <div class="modal-body" id='excluir'>
                                            <div class="row" id="linha">
                                                            <div class="col-6">
                                                                    <label> O ingresso possui meia entrada?</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="meia" @if(old('meia')=='sim' ) checked @endif
                                                                id="meiaentrada" value="sim">
                                                            <label class="form-check-label" for="meiaentrada">
                                                                Sim
                                                            </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="meia" id="meiaentrada" value="nao"
                                                                    @if(old('meia')=='nao' ) checked @endif>
                                                                <label class="form-check-label" for="meiaentrada">
                                                                    Não
                                                                </label>
                                                            </div>                
                                            </div>

                                                    <div class="row" id="linha">
                                                            <div class="col-6">
                                                                    <label for="mesas"> O evento possui mesa?</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                            <input class="form-check-input simmesa" type="radio" name="mesa"
                                                                id="mesadoevento" value="sim">
                                                            <label class="form-check-label simmesa" for="mesadoevento">
                                                                Sim
                                                            </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="mesa" id="mesadoevento" value="nao">
                                                                <label class="form-check-label" for="mesadoevento">
                                                                    Não
                                                                </label>
                                                            </div>                  
                                                    </div>
                                                    <div class="quantidademesas" style="display: none">
                                                        <label for="mesadoevento">Quantidade de mesas</label><br>
                                                        <div class="col-12 col-md-6 form-group">
                                                        <label for="local">Quantidade permitida por compra</label>
                                                        <input type="text" class="form-control" name="quantidademesas" value="{{old('quantidademesas')}}" id="quantidademesas"
                                                            placeholder="Quantidade">
                                                        </div>
                                                    </div>

                                                    <div class="row" id="linha">
                                                            <div class="col-6">
                                                                    <label> O evento possui lotes?</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="lote" @if(old('lote')=='sim' ) checked @endif
                                                                id="lotes" value="sim">
                                                            <label class="form-check-label" for="lotes">
                                                                Sim
                                                            </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="lote" id="lotes" value="nao"
                                                                    @if(old('lote')=='nao' ) checked @endif>
                                                                <label class="form-check-label" for="lotes">
                                                                    Não
                                                                </label>
                                                            </div>

                                                                            
                                                    </div>

                                                    <br>
                                                    <div class="row">
                                                    <div class="col-12 col-md-6 form-group">
                                                    <label for="data_ini">Data de Início das vendas</label>
                                                    <input type="date" class="form-control {{$errors->has('data_inicio') ? 'is-invalid' : ''}}"
                                                        name="data_iniciovenda" value="{{old('data_iniciovenda')}}" id="data_iniv" placeholder="__/__/____">
                                                    @if($errors->has('data_iniciovenda')) <div class="invalid-feedback">{{$errors->first('data_inicio')}}
                                                    </div>@endif
                                                </div>
                                                <div class="col-12 col-md-6 form-group">
                                                    <label for="data_fim">Data de Encerramento </label>
                                                    <input type="date" class="form-control {{$errors->has('data_fim') ? 'is-invalid' : ''}}"
                                                        name="data_fimvenda" value="{{old('data_fimvenda')}}" id="data_fimv" placeholder="__/__/____">
                                                    @if($errors->has('data_fim')) <div class="invalid-feedback">{{$errors->first('data_fim')}}</div>
                                                    @endif
                                                </div>
                                                </div>

                                                <div class="row">
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="hora_inicio">Horário de Início das vendas</label>
                                        <input type="time" class="form-control {{$errors->has('hora_inicio') ? 'is-invalid' : ''}}"
                                            name="hora_iniciovenda" value="{{old('hora_iniciovenda')}}" id="hora_iniciov"
                                            placeholder="Horário de Início">
                                        @if($errors->has('hora_inicio')) <div class="invalid-feedback">{{$errors->first('hora_inicio')}}
                                        </div>@endif

                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="hora_fim">Horário de Encerramento </label>
                                        <input type="time" class="form-control {{$errors->has('hora_fim') ? 'is-invalid' : ''}}"
                                            name="hora_fimvenda" value="{{old('hora_fimvenda')}}" id="hora_fimv" placeholder="Horário de Encerramento">
                                        @if($errors->has('hora_fim')) <div class="invalid-feedback">{{$errors->first('hora_fim')}}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                              </div>
                            </div>
                              
                                        <div class="modal-footer">
                                                 <button type="submit" class="btn btn-primary">Salvar</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        </div>
                </div>
        </div>
</div>


@endsection

@section('javascript')
{{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMq_cGIjEUAdZ2MAhRhOw6y2tZC25StV4&callback=initMap">
    </script> --}}

{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZsIYQI3o0JKtxQvEWAdgPWNbO1NRIdNs&libraries=places"></script> --}}

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZsIYQI3o0JKtxQvEWAdgPWNbO1NRIdNs&libraries=places&callback=initAutocomplete"
    async defer></script>

<script type="text/javaScript">

    $('a').on('click', function() {
        //$('#banner').val('');
        $('.img').hide();
        $('.div').attr('style', "padding-bottom: 0px;");
    });
    function readURL(input, id) {
                if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#'+id).remove('src', e.target.result);                    
                    $('.div').attr('style', "padding-bottom: 100px;");
                    $('.img').fadeIn("show");
                    $('#'+id).attr('src', e.target.result);
                    $('i').show();
                }
                    reader.readAsDataURL(input.files[0]);
                }
            }
    

        $('#summernote').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
                ],
            height: 200
        });
        function Trim(str){
        return str.replace(/[' ']/g,'');
    }
    document.getElementById("slug").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
           return false;
       };


    $("input").change(function() {
        if ($('.sim').prop("checked") == true) {
            $('.codigo').fadeIn("show");
        } else {
            var ele = document.getElementsByName("codigo");
            for(var i=0;i<ele.length;i++)
            ele[i].checked = false;        
            $('.codigo').hide();   
        }
    });

    $("input").change(function() {
        if ($('.simmesa').prop("checked") == true) {
            $('.quantidademesas').fadeIn("show");
        } else {
            var ele1 = document.getElementsByName("quantidademesas");
            for(var i=0;i<ele1.length;i++)
            ele1[i].checked = false;        
            $('.quantidademesas').hide();   
        }
    });

// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -33.8688, lng: 151.2195},
    zoom: 13,
    mapTypeId: 'roadmap'
  });

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

    </script>

@endsection