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
            <td align="center" bgcolor="" style="padding: 30px">
                <img src="https://gintop.com.br/storage/imagens/logo-toptecnologia.png" alt=""
                    width="200" height="80" style="display: block;" />
            </td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" style="padding: 30px; border-radius: 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="260" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                <h3>Seja bem vindo {{$empresa->responsavel}},</h3>

                                <h4>Sua empresa {{$empresa->nome_fantasia}} foi cadastrada com sucesso ao Sistema Gin Top</h4>
                                
                                @if($status == 1)
                                    <h4>Seu Login é: {{$empresa->email}}</h4>
                                    <h4>Sua Senha é: {{base64_encode($empresa->celular)}}</h4>
                                
                                    Assim que fizer o login recomendamos que troque sua senha!

                                @else
                                    <h4>Seu Login e Senha permanecem o mesmo, nada foi alterado!</h4>
                                @endif

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