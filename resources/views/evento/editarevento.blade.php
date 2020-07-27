<?php
namespace App\Http\Controllers\ControladorEmpresa;
use Illuminate\Http\Request;
use App\Empresa;

$empresa = Empresa::all();


$data_inicio = date("d/m/Y", strtotime($evento->data_inicio));
$data_fim = date("d/m/Y", strtotime($evento->data_fim));
?>
@extends('layout.app', ["current" => "evento"])

@section('body')
<style>
    @media(max-width: 900px) {
            .div{
                margin-bottom: 50px ;
            }
    }
</style>
<div class="card border">
    <div class="card-body">
        <form action="/evento/{{$evento->id}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <input type="hidden" class="id_evento" id="id_evento" value="{{$evento->id}}" name="id_evento">
                <div class="col-12 col-md-6 form-group">
                    <label for="nomeevento">Nome da evento</label>
                    <input type="text" class="form-control" name="nome" value="{{$evento->nome}}" id="nome">
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="empresa" class="control-label">Empresa</label>
                    <select class="form-control" id="empresa" name="empresa">
                        @foreach ($empresa as $empre)
                        @if($evento->id_empresa == $empre->id)
                        <option value="{{$empre->id}}" selected readonly>{{$empre->nome_fantasia}}</option>
                        @endif
                        <option value="{{$empre->id}}">{{$empre->nome_fantasia}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="data_ini">Data de Início</label>
                    <input type="text" class="form-control data_ data_calendario" name="data_inicio" value="{{$data_inicio}}"
                        id="data_inicio" placeholder="Data Início">
                        <small class="text-muted">Somente números</small>
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="data_fim">Data de Encerramento</label>
                    <input type="text" class="form-control data_ data_calendario" name="data_fim" value="{{$data_fim}}" id="data_fim"
                        placeholder="Data Encerramento">
                        <small class="text-muted">Somente números</small>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="hora_inicio">Horário de Início</label>
                    <input type="time" class="form-control" name="hora_inicio" value="{{$evento->hora_inicio}}"
                        id="hora_inicio" placeholder="Horário de Início">
                        <small class="text-muted">Somente números</small>
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="hora_fim">Horário de Encerramento</label>
                    <input type="time" class="form-control" name="hora_fim" value="{{$evento->hora_inicio}}"
                        id="hora_fim" placeholder="Horário de Encerramento">
                        <small class="text-muted">Somente números</small>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">

                    <label for="slug">Endereço para inscrição *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">gintop.com.br/</div>
                        </div>
                        <input type="text" class="form-control" name="slug" value="{{$evento->slug}}"
                            onkeyup="this.value = Trim( this.value )" id="slug" placeholder="Link">
                    </div>
                    <small class="text-muted">Sem espaços e caracteres</small>
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="tamanho_impressao">Dimensões para impressão *</label>
                    <input type="text" class="form-control" name="tamanho_impressao"
                        value="{{$evento->tamanho_impressao}}" onkeyup="this.value = Trim( this.value )" id="tamanho_impressao"
                        placeholder="Dimensões para impressão">
                    <small class="text-muted">Separar por ; (ponto e virgula)</small>
                </div>
            </div>

            <div class="row div" @if(isset($evento->url_imagem))
                style="padding-bottom: 100px;" @endif >
                <div class="col-12 col-md-6 form-group">
                    <label for="controle">Controle de Acesso?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input sim" type="radio" name="controle" id="controle" value="sim"
                            @if($evento->codigo != '') checked @endif>
                        <label class="form-check-label" for="controle">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input nao" type="radio" name="controle" id="controle" value="nao"
                            @if($evento->codigo == '') checked @endif>
                        <label class="form-check-label" for="controle">
                            Não
                        </label>
                    </div>
                    <div class="codigo" @if($evento->codigo != '') style="display: show" @else style="display: none"
                        @endif>
                        <label for="controle">Tipo de código</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="codigo" id="barcode" value="qr"
                                @if($evento->codigo == 'qr') checked @endif>
                            <label class="form-check-label" for="barcode">
                                QR <i class="fas fa-qrcode" style="font-size: 50px"></i>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="codigo" id="barcode" value="barcode"
                                @if($evento->codigo == 'barcode') checked @endif
                            >
                            <label class="form-check-label" for="barcode">
                                Código de Barra <i class="fas fa-barcode" style="font-size: 50px"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 div">
                    <div class="custom-file">
                        <label for="banner" class="custom-file-label">Banner</label>
                        <input type="file" class="custom-file-input" name="url_imagem" id="banner"
                            onchange="readURL(this,'img');" value="{{$evento->url_imagem}}" placeholder="Banner">
                        <small class="text-muted">O tamanho recomendado é de 1600x900 (o mesmo para capas de eventos
                            do
                            Facebook)
                        </small>

                        <div class="row img">
                            <div class="col-md-6"><img id="img" height="100" @if(isset($evento->url_imagem))
                                src="/storage/{{$evento->url_imagem}}"@endif/></div>
                            <div class="col-md-6"><a id="a" href="#"><i
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
                    <input type="text" class="form-control" name="nome_local" id="local" placeholder="Local"
                        value="{{$evento->nome_local}}">
                </div>
                <div class="col-12 col-md-6 form-group">
                    <label for="endereco">Endereço do Local</label>
                    <input id="pac-input" class="controls form-control" type="text" name='endereco_local'
                        placeholder="Buscar Endereço" value="{{$evento->endereco_local}}">

                </div>
                <div id="map"></div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 form-group">
                    <label for="salas">Salas</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sala" id="salas" value="sim"
                            @if($evento->sala == 'sim') checked @endif >
                        <label class="form-check-label" for="salas">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sala" id="salas" value="nao"
                            @if($evento->sala == 'nao') checked @endif >
                        <label class="form-check-label" for="salas">
                            Não
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 form-group">
                    <label for="ingresso">O evento necessia de venda de ingressos? </label> <small class="text-muted">(Se sim você será redirecionado a página para criar os ingressos, após o cadastro do Evento)</small><br>
                   
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="ingresso" @if(old('sala')=='sim' ) checked @endif
                            id="ingresso" value="sim" @if($evento->ingresso == 'sim') checked @endif>
                        <label class="form-check-label" for="ingresso">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="ingresso" id="ingresso" value="nao"
                        @if($evento->ingresso == 'nao') checked @endif>
                        <label class="form-check-label" for="ingresso">
                            Não
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 form-group">
                    <label for="mesa">O evento haverá venda de mesas? <small class="text-muted">(Se sim você será redirecionado a página para criar as mesas e assentos, após o cadastro do Evento)</small> </label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input switch" type="radio" name="mesa" @if($evento->mesa == 'sim') checked @endif id="mesa" value="sim">
                        <label class="form-check-label" for="mesa">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="mesa" id="mesa" value="nao" @if($evento->mesa == 'nao') checked @endif>
                        <label class="form-check-label" for="mesa">
                            Não
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 form-group">
                    <label for="descricao">Descrição</label><br>
                    <textarea name="descricao" id="summernote">{{$evento->descricao}}</textarea>
                </div>
            </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <a href="/evento/" class="btn btn-danger btn-sm">Cancelar</a>
        </form>
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

    $('#a').on('click', function() {
        //$('#banner').val('');
        $('.img').hide();
        $('.div').attr('style', "padding-bottom: 0px;");

        $.ajax({
            type: 'GET',
            url: '/removerimg',
            data: {
                id:     $('#id_evento').val(),
                img:    $('#banner').val(),
                apagar:  'apagar'
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.sucesso == 0) {
                    //alert('vazio');
                }
            }
        });
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
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM.-_".indexOf(chr) < 0)
           return false;
       };

       document.getElementById("tamanho_impressao").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890;".indexOf(chr) < 0)
           return false;
       };


       $(document).ready(function() {
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