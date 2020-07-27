<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Evento;
use App\Campo_caex;
use App\Caex;
use Illuminate\Support\Facades\DB;

class CredImport implements ToModel, WithHeadingRow
{
    protected $exibir;

    public function __construct(Evento $id)
    {
        $this->evento = $id;
    }

    public function model(array $row)
    {
        $id = $this->evento->id;


        $campos = Campo_caex::select('nome', 'cracha', 'id')->where('id_evento', $id)->get();

        //$row      =   collect($row);
        $contcampos =   count($campos);
        $cont       =   count($row);

        $i      = 0;
        $nome   = '';
        $falta  = null;
        $mais   = null;

        // if ($contcampos != $cont) {
        //     echo 'Quantidade de campos diferente <br>';
        // } elseif ($contcampos == $cont) {
        //     echo 'Quantidade de campos iguais <br>';
        // }

        while (current($row)) {
            foreach ($campos as $c) {
                if (strtolower($c->nome) ==  key($row)) {
                    $nome = $nome . ' ' . key($row) . ', ';
                    $i++;
                }
                $Tnome = $nome;
            }
            next($row);
        }

        echo '<br>';
        if ($contcampos > $i) {
            $falta = $contcampos - $i;
            if ($contcampos != $cont) {
                $mensagem = 'Faltam ' . $falta . ' !<br>' .
                    'Titulos cabeçalho corretos - <b>' . $Tnome . '</b> verifique os ' . $falta . ' campos que faltam! <br>';
                //return with('mensagem', $mensagem);
                return;
                exit();
                echo $mensagem;
            } elseif ($contcampos == $cont) {
                $mensagem =  $falta . ' campos incorretos!<br>' .
                    'Titulos cabeçalho corretos - <b>' . $Tnome . '</b> verifique os ' . $falta . ' campos incorretos! <br>';
                //return with('mensagem', $mensagem);
                return;
                exit();
                echo $mensagem;
            }
        }

        if ($contcampos < $cont) {
            $mais = $cont - $contcampos;
            if ($contcampos == $cont) {
                $mensagem = $mais . ' campos diferentes! <br>' .
                    'Titulos cabeçalho corretos -  <b>' . $Tnome . '</b> verifique os outros ' . $mais . ' campos';
                //return with('mensagem', $mensagem);
                return;
                exit();
                echo $mensagem;
            } elseif ($contcampos != $cont) {
                $mensagem = $mais . ' campos a mais! <br>' .
                    'Titulos cabeçalho corretos -  <b>' . $Tnome . '</b> verifique os outros ' . $mais . ' campos';
                //return redirect('/visitantes/' . $id)->with('mensagem', $mensagem);
                return;
                exit();
                echo $mensagem;
            }
        } else {
            $mais =  $contcampos - $cont;
            if ($mais != 0) {
                if ($contcampos == $cont) {
                    $mensagem = $mais . ' campos a mais! <br>' .
                        'Titulos cabeçalho corretos -  <b>' . $Tnome . '</b> verifique os outros ' . $mais . ' campos';
                    //return with('mensagem', $mensagem);
                    // return;
                    // exit();
                    echo $mensagem;
                } elseif ($contcampos != $cont) {
                    $mensagem = $mais . ' campos diferentes! <br>' .
                        'Titulos cabeçalho corretos -  <b>' . $Tnome . '</b> verifique os outros ' . $mais . ' campos';
                    //return with('mensagem', $mensagem);
                    // return;
                    // exit();
                    echo $mensagem;
                }
            }
        }

        //contadores
        
        // echo '<br>contando campos iguais - i ' . $i . '<br>';
        // echo 'contador campos salvos - contcampos ' . $contcampos . '<br>';
        // echo 'contador linhas tabela - cont ' . $cont . '<br>';
        // exit();
        

        $verifica_cpf= Caex::select('cpf', 'id')->where('id_evento', $id)->where('email', $row['cpf'])->first();

        if (!isset($verifica_cpf)) {
            foreach ($campos as $c) {
                $valor      = strtolower($c->nome);
                $id         = $id;
                $id_campo   = $c->id;
                $nome       = $row['nome'];
                $email      = $row['email'];
                $celular    = $row['celular'];
                $cpf        = $row['cpf'];
                $campo      = $c->nome;
                $valor      = $row[$valor];
                $cracha     = $c->cracha;

                DB::insert(
                    'insert into caexes (
                    id_campo_caex, id_evento, nome, email, celular, cpf, campo, cracha, valor_salvo, created_at, updated_at) 
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $id_campo, $id, $nome, $email, $celular, $cpf, $campo, $cracha, str_replace(";", ",", $valor), NOW(), NOW()
                    ]
                );
            }
        }
        $verifica_email = null;
        //exit();
        //$mensagem = 'sucesso';

        return;
    }
}

/*
print_r($row);
echo '<br>';
var_dump($row);
echo '<br>';
var_export($row);
echo '<br>';
*/

// return new Credenciamento([
//     'id_evento'     => $id,
//     'id_campo_cred' => 30,
//     'nome'          => $row['nome'],
//     'email'         => $row['email'],
//     'celular'       => $row['celular'],
//     'campo'         => $campo,
//     'valor_salvo'   => $valor,
//     'cracha'        => $cracha,
// ]);


//verificar oq está
// echo  $id      . '<br>';
// echo  $nome     . '<br>';
// echo  $email    . '<br>';
// echo  $celular  . '<br>';
// echo  $campo    . '<br>';
// echo  $valor     . '<br>';
// echo  $cracha   . '<br>';

//return back();
//exit();
