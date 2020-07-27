<?php

namespace App\Http\Controllers;

use App\Sala;
use Illuminate\Http\Request;
use App\Campo_cred;
use App\Credenciamento;
use App\Registro;
use App\Exports\SalasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ControladorSala extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $ini = implode('-', array_reverse(explode('/', $request->input('data_inicio'))));
        $fim = implode('-', array_reverse(explode('/', $request->input('data_fim'))));

        $id             = $request->input('id_evento');
        $nome           = $request->input('nome');
        $local          = $request->input('local');
        $palestrante    = $request->input('palestrante');
        $quantidade     = $request->input('quantidade');
        $data_inicio    = $ini;
        $data_fim       = $fim;
        $hora_inicio    = $request->input('hora_inicio');
        $hora_fim       = $request->input('hora_fim');

        if($quantidade == ''){
            $quantidade = 0;
        }

        $existe = SALA::select('nome')->where('id_evento', $id)->where('nome', $nome)->first();

        //limpar nome
        // $existe = str_replace("nome", "", $existe);
        // $existe = str_replace("[{", "", $existe);
        // $existe = str_replace("}]", "", $existe);
        // $existe = str_replace(":", "", $existe);
        // $existe = str_replace('"', "", $existe);
        // echo $existe->nome;
        // die();
        if(isset($existe)){
            //verificar se NOME existe
            if ($existe->nome === $nome) {
                $resposta = array(
                    'nome' => $nome,
                    'nome existe' => $existe->nome,
                    'mensagem' => 'nome já existe',
                    'id' => $id,
                    'sucesso' => 2
                );
                return response(json_encode($resposta, 200));
                exit();
            }
        }

        //verificar se NOME vazio ou nulo
        if ($nome == ''
            or $nome == null) {
            $resposta = array(
                'nome' => $nome,
                'mensagem' => 'nome VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0
            );
            return response(json_encode($resposta, 200));
            exit();
        }
        if ($local == ''
            or $local == null) {
            $resposta = array(
                'nome' => $local,
                'mensagem' => 'local VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0
            );
            return response(json_encode($resposta, 200));
            exit();
        }
        if ($data_inicio == ''
            or $data_inicio == null) {
            $resposta = array(
                'nome' => $data_inicio,
                'mensagem' => 'data_inicio VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0
            );
            return response(json_encode($resposta, 200));
            exit();
        }
        if ($data_fim == ''
            or $data_fim == null) {
            $resposta = array(
                'nome' => $data_fim,
                'mensagem' => 'data_fim VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0
            );
            return response(json_encode($resposta, 200));
            exit();
        }
        if ($hora_inicio == ''
            or $hora_inicio == null) {
            $resposta = array(
                'nome' => $hora_inicio,
                'mensagem' => 'hora_inicio VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0
            );
            return response(json_encode($resposta, 200));
            exit();
        }
        if ($hora_fim == ''
            or $hora_fim == null) {
            $resposta = array(
                'nome' => $hora_fim,
                'mensagem' => 'hora_fim VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0
            );
            return response(json_encode($resposta, 200));
            exit();
        }

        $sala = new Sala();

        $sala->id_evento    = $id;
        $sala->nome         = $nome;
        $sala->nome_local   = $local;
        $sala->palestrante  = $palestrante;
        $sala->quantidade   = $quantidade;
        $sala->data_inicio  = $data_inicio;
        $sala->data_fim     = $data_fim;
        $sala->hora_inicio  = $hora_inicio;
        $sala->hora_fim     = $hora_fim;

        $sala->save();

        //retornar mensagem SALVO
        $retorno = array(
            'salvou' => $sala->save(),
            'nome' => ucfirst($nome),
            'mensagem' => 'SALA salvo',
            'id' => $id,
            'sucesso' => 1
        );

        return response(json_encode($retorno, 200));

        // echo '<br>';
        // echo  $id           . '<br>';
        // echo  $nome         . '<br>';
        // echo  $local        . '<br>';
        // echo  $palestrante  . '<br>';
        // echo  $quantidade   . '<br>';
        // echo  $data_inicio  . '<br>';
        // echo  $data_fim     . '<br>';
        // echo  $hora_inicio  . '<br>';
        // echo  $hora_fim     . '<br>';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $campoCred = Campo_cred::where('id_evento', '=', $id)->get();
        $exibir = Sala::find($id);
        if (isset($exibir)) {
            return view('sala.infosala', ['exibir' => $exibir, 'campoCred' => $campoCred]);
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $sala = Sala::find($id);

        $ini = implode('-', array_reverse(explode('/', $request->input('data_inicio'))));
        $fim = implode('-', array_reverse(explode('/', $request->input('data_fim'))));

        if (isset($sala)) {

            $sala->nome           = $request->input('nome');
            $sala->nome_local     = $request->input('local');
            $sala->palestrante    = $request->input('palestrante');
            $sala->quantidade     = $request->input('quantidade');
            $sala->data_inicio    = $ini;
            $sala->data_fim       = $fim;
            $sala->hora_inicio    = $request->input('hora_inicio');
            $sala->hora_fim       = $request->input('hora_fim');
            $sala->save();

            return redirect()->back()->with('mensagem', 'atualizado');
        }
        return redirect('/evento');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $sala = Sala::find($id);
        if (isset($sala)) {
            //$evento->delete();
            DB::table('salas')->where('id', $id)->update([
                'ativo'          => false,
                'updated_at'     => NOW()
            ]);
            return redirect()->back()->with('mensagem', 'apagado');
        }
        return abort(404);
    }

    public function codigo(Request $request)
    {
        //
        $busca  = $request->input('leitor');
        $id     = $request->input('id_sala');
        $lista  = explode("-", $busca);

        // VER OQ ESTA CHEGANDO
        // echo $busca . '<br>';
        // echo '<br>';
        // var_dump($lista) ;
        // echo sizeof($lista) . '<br>';
        // list($zero, $primeiro, $segundo, $terceiro) = explode("-", $busca);
        // echo $zero . '<br>';
        // echo $primeiro . '<br>';
        // echo $segundo . '<br>';
        // echo $terceiro . '<br>';
        // die;

        if (sizeof($lista) >= 4) {
            list($zero, $primeiro, $segundo, $terceiro) = explode("-", $busca);

            $nome = Credenciamento::select('nome', 'cpf')
                ->where('id', $primeiro)
                ->where('id_campo_cred', $segundo)
                ->where('id_evento', $terceiro)
                ->first();

            if (isset($nome)) {
                echo  '<div class="alert alert-success " role="alert" style="text-align: center;" >';
                echo   '<span style="text-align: center; font-size: 20px"> LEITURA EFETUADA <b> '.$nome->nome.' </b> <br>'.date('d/m/Y H:i:s').'</span><br>';
                echo  '</div>';

                $existe = Registro::select('*')
                ->where('id_evento', $terceiro)
                ->where('id_credenciamento', $primeiro)
                ->where('id_sala', $id)
                ->where('codigo', $busca)
                ->where('horario', NOW())
                ->first();

                if (!isset($existe)) {

                    DB::insert(
                    'insert into registros (
                            id_evento, id_credenciamento, id_sala, codigo, nome, cpf, horario, created_at, updated_at
                            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$terceiro, $primeiro, $id, $busca, $nome->nome, $nome->cpf, NOW(), NOW(), NOW()]
                
                );

                }
            } else {
                //echo '<center><img src="../storage/imagens/load.gif" alt="sometext" width=40 height=40></center>';
                echo  '<div class="alert alert-warning " role="alert" style="text-align: center;" >';
                echo   '<span style="text-align: center; font-size: 20px"> NÃO ENCONTRADO </span><br>';
                echo  '</div>';
            }
        } else {
            //echo '<center><img src="../storage/imagens/load.gif" alt="some text" width=40 height=40></center>';
            echo  '<div class="alert alert-danger " role="alert" style="text-align: center;" >';
            echo   '<span style="text-align: center; font-size: 20px"> CÓDIGO INVALIDO </span><br>';
            echo  '</div>';
        }
    }

    public function export(Sala $id)
    {
        return Excel::download(new SalasExport($id), 'Sala-' . NOW() . '.xlsx');
    }

}
