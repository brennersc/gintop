<?php

$evento = DB::table('eventos')->where('id', [$data['id_evento']])->first();

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');


$data_inicio    = date("d/m/Y", strtotime($evento->data_inicio));
$data_fim       = date("d/m/Y", strtotime($evento->data_fim));

$cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $data['cpf']);

$query = str_replace(' / ', '" or nome = "', $data['palestras']);

$query = ' "'.$query.'" ';

$sql = 'SELECT * FROM salas WHERE nome = '.$query.' and id_evento = '.$data['id_evento']. '';

$sql = DB::select($sql);

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GinTop</title>
    <meta name="GinTop" content="width=device-width, initial-scale=1.0" />
</head>

<body style="margin: 0; padding: 0; background-color: #e6e6e6;  font-family:Arial, sans-serif; ">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-radius: 10px;">
        <tr>
            <td align="center" bgcolor="" style="">
                @if(isset($evento->url_imagem))
                <img src="https://gintop.com.br/storage/{{$evento->url_imagem}}" alt="{{$evento->nome}}"
                    width="100%" height="230" style="display: block;border-radius: 10px;" />
                @else
                <img src="https://gintop.com.br/storage/imagens/bannertop.png" alt="{{$evento->nome}}"
                    width="100%" height="230" style="display: block;border-radius: 10px;" />
                @endif
            </td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" style="padding: 30px; border-radius: 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="260" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <h2>Olá {{$data['nome']}},</h2>

                                <h3>Seu credenciamento foi realizado com sucesso para o {{$evento->nome}}</h3>

                                <h4>Confira seu cadastro em <a href="https://gintop.com.br/visitante">GinTop</a>.</h4>

                                <h4>Faça login utilizando seu CPF: <b>{{$cpf}}</b></h4>

                                <h4>Sua Senha: <i>{{$data['cpf']}}</i></h4>

                                <hr>

                                <h4>Informações para auxilialo(a):</h4>

                                <h4><b>Começa:</b> {{$data_inicio}} às {{$evento->hora_inicio}}</h4>

                                <h4><b>Termina:</b> {{$data_fim}} às {{$evento->hora_fim}}</h4>

                                <h4><b>Local:</b> {{$evento->nome_local}} - {{$evento->endereco_local}}</h4>

                                <h4><b>Palestras:</b> {{ $data['palestras'] }}</h4>
                                @foreach ($sql as $item)
                                    <h4>{{$item->nome}}</h4>
                                    <table border="0">
                                        <tr>
                                            <td><b>Local</b></td>
                                            <td>{{$item->nome_local}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Palestrante</b></td>
                                            <td>{{$item->palestrante}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Começa</b></td>
                                            <td>{{ date("d/m/Y", strtotime($item->data_inicio))}} às {{$item->hora_inicio}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Termina</b></td>
                                            <td>{{ date("d/m/Y", strtotime($item->data_fim))}} às {{$item->hora_fim}}</td>
                                        </tr>
                                    </table>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#cbefff" style="padding: 30px; border-radius: 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="left" width="75%" style="font-size:12px;">
                            <b>© 2020 Top Tecnologia</b> - Todos os direitos reservados.<br />
                            Este é um e-mail automático.
                        </td>
                        <td>
                            <a href="http://www.toptecnologia.com.br">
                                <img src="https://gintop.com.br/storage/imagens/logo-toptecnologia.png" alt="Site"
                                    width="120" height="40" style="display: block;" border="0" />
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br><br>
</body>
</html>