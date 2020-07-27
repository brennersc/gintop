<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ env('APP_NAME') }}</title>

    <!-- Fonts -->
    <!-- https://github.com/milon/barcode -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <style>
        small {
            overflow: auto;
        }

        @php $strExemple=$sizepage->tamanho_impressao;
        $size=explode(';', $strExemple);

        @endphp 
        @page {
            size: @foreach($size as $s) {
                    {
                    $s
                }
            }

            cm @endforeach;
            margin: 1px;
        }
        
    </style>
</head>

<body>
    @if(isset($sizepage->codigo))
    @if($sizepage->codigo == 'barcode')
    <div style="display: flex; align-items: center; justify-content: center">
        <div id="content">
            @foreach($itens as $item)
                @php
                    $strExemple = $item->valor_salvo;
                    $valor_campos = explode(';', $strExemple);
                @endphp
                @foreach ($valor_campos as $valor)
                    <center style="font-size: 20px">
                        <td>{{$valor}}</td>
                    </center>
                @endforeach
            @endforeach
            @if(isset($code->id_campo_cred))    
                {!! DNS1D::getBarcodeSVG('000' .'-'. $code->id .'-'. $code->id_campo_cred .'-'. $code->id_evento, "C39",1,40,"black", false); !!}
            @else
                {!! DNS1D::getBarcodeSVG('000' .'-'. $code->id .'-'. $code->id_campo_caex .'-'. $code->id_evento, "C39",1.5,40,"black", false); !!}
            @endif
        </div>
    </div>
    @else
    <table style="display:flex ; align-items: center; justify-content: center;">
        <tr>
            <td style="font-size: 25px;">
                @foreach($itens as $item)
                    @php
                        $strExemple = $item->valor_salvo;
                        $valor_campos = explode(';', $strExemple);
                    @endphp
                    @foreach ($valor_campos as $valor)
                        {{$valor}} <br>
                    @endforeach
                @endforeach
            </td>
            <td style="padding-left: 10px;">
                @if(isset($code->id_campo_cred))    
                    {!! DNS2D::getBarcodeSVG('000' .'-'. $code->id .'-'. $code->id_campo_cred .'-'. $code->id_evento, "QRCODE",4,4,"black") !!}
                @else
                    {!! DNS2D::getBarcodeSVG('000' .'-'. $code->id .'-'. $code->id_campo_caex .'-'. $code->id_evento, "QRCODE",4,4,"black") !!} 
                @endif
            </td>
        </tr>
    </table>
    @endif
    @else
    <div style="display: flex; align-items: center; justify-content: center">
        <div id="content">
            @foreach($itens as $item)
                @php
                    $strExemple = $item->valor_salvo;
                    $valor_campos = explode(';', $strExemple);
                @endphp

                @foreach ($valor_campos as $valor)
                    <center style="font-size: 30px">
                        <td>{{$valor}}</td>
                    </center>
                @endforeach
            @endforeach
        </div>
    </div>
    @endif
</body>
<script>
    window.print();
    window.close();
</script>

</html>