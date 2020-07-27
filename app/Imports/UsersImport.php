<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Evento;
use App\Campo_cred;
use App\Credenciamento;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ControladorCredenciamento;
use Maatwebsite\Excel\Excel;

class UsersImport implements ToModel, WithHeadingRow
{
    protected $exibir;

    public function __construct(Evento $id)
    {
        $this->evento = $id;
    }

    public function model(array $row)
    {
        $id = $this->evento->id;

        $campos = Campo_cred::select('nome', 'cracha', 'id')->where('id_evento', $id)->get();

        //$row      =   collect($row);
        $contcampos =   count($campos);

        if(isset($row['palestras'])){

            $cont       =   count($row) - 1;
        }else{
            
            $cont       =   count($row);
        }

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
                if(key($row) != 'palestras'){
                    if (strtolower($c->nome) ==  key($row)) {
                        $nome = $nome . ' ' . key($row) . ', ';
                        $i++;
                    }
                }
                $Tnome = $nome;
            }
            next($row);
        }


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
        /*
        echo '<br>contando campos iguais - i ' . $i . '<br>';
        echo 'contador campos salvos - contcampos ' . $contcampos . '<br>';
        echo 'contador linhas tabela - cont ' . $cont . '<br>';

        exit();
        */

        $verifica_cpf = Credenciamento::select('cpf', 'id')->where('id_evento', $id)->where('email', $row['cpf'])->first();

        $palestras   = isset($row['palestras']) ? $row['palestras'] : 'Nenhuma selecionda';

        if (!isset($verifica_cpf->cpf)) {
            foreach ($campos as $c) {
                $valor      = strtolower($c->nome);
                $id         = $id;
                $id_campo   = $c->id;
                $nome       = $row['nome'];
                $email      = $row['email'];
                $celular    = $row['celular'];
                $cpf        = $row['cpf'];
                $palestras  = $palestras;
                $campo      = $c->nome;
                $valor      = $row[$valor];
                $cracha     = $c->cracha;

                DB::insert(
                    'insert into credenciamentos (
                    id_campo_cred, id_evento, nome, email, celular, cpf, campo, cracha, palestras, valor_salvo, created_at, updated_at) 
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $id_campo, $id, $nome, $email, $celular, $cpf, $campo, $cracha, $palestras, str_replace(";", ",", $valor), NOW(), NOW()
                    ]
                );
            }
        }
        $verifica_email = null;
        
        //exit();
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
