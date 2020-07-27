<?php

namespace App\Http\Controllers;

use App\Caex;
use App\Campo_caex;
use App\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ControladorCampo_caex extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

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
        $data = new Campo_caex();
        $field = $request->all();

        if ($field['nome'] == '') {
            return redirect('evento')->with('nome', 'Campo nome vazio!!!');
            die();
        }

        $count = count($field['id_evento']);

        for ($i = 0; $i < $count; $i++) {
            $data['id_evento'] = $field['id_evento'][$i];
            $data['nome'] = $field['nome'][$i];
            $data['slug'] = $field['nome'][$i];
            $data['obrigatorio'] = isset($field['obrigatorio'][$i]) ? true : false;
            $data['unico'] = isset($field['unico'][$i]) ? true : false;
            $data['cracha'] = isset($field['cracha'][$i]) ? true : false;
            $data['tipo'] = $field['tipo'][$i];
            $data['opcoes'] = $field['opcoes'][$i];
            $data['tamanho'] = $field['tamanho'][$i];
            //$data->save();
            DB::insert(
                'insert into campo_caexes (
                id_evento, nome, slug, obrigatorio, unico, cracha, tipo, opcoes, tamanho, created_at, updated_at)
                values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['id_evento'], $data['nome'], $data['slug'], $data['obrigatorio'], $data['unico'],
                    $data['cracha'], $data['tipo'], $data['opcoes'], $data['tamanho'], NOW(), NOW(),
                ]
            );
        }
        return redirect('evento')->with('caex', 'Campos caexes inseridos com Sucesso!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $exibir = Evento::where('slug', $slug)->first();
        if (isset($exibir)) {
            return view('formularios.formCaex', ['exibir' => $exibir]);
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
        $caex = Campo_caex::find($id);

        $nome = ucfirst($request->input('nome'));

        if( ($nome == 'Nome')||
            ($nome == 'Email')||
            ($nome == 'Celular')||
            ($nome == 'Cpf')){

                return redirect()->back()->with('erro', 'erro');
                die();
                
            }

        if (isset($caex)) {

            $caex->nome         = ucfirst($request->input('nome'));
            $caex->tipo         = $request->input('tipo');
            $caex->obrigatorio  = isset($request['obrigatorio']) ? true : false;
            $caex->unico        = isset($request['unico']) ? true : false;
            $caex->cracha       = isset($request['cracha']) ? true : false;
            $caex->tamanho      = $request->input('tamanho');
            $caex->opcoes       = $request->input('opcoes');

            $caex->save();

            Caex::where('id_campo_caex', $id)->update([
                'campo'         => ucfirst($request->input('nome')),
                'updated_at'    => NOW()
            ]);

            return redirect()->back()->with('mensagem', 'atualizado');
        }
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //

        $id = $request->input('id');
        $status = $request->input('status');
        echo $id . '<br>';
        echo $status;

        //pegar informaçoes do campo cadastrado
        $info = Campo_caex::select('id_evento', 'nome', 'cracha')->where('id', $id)->first();

        if ($status == 0) {

            Campo_caex::where('id', $id)->update([
                'ativo'         => false,
                'updated_at'    => NOW(),
            ]);
            Caex::where('id_campo_caex', $id)->where('ativo', 1)->update([
                'ativo'         => false,
                'updated_at'    => NOW(),
            ]);
            //$cred->delete();
            //return redirect()->back();

        } else {

            Campo_caex::where('id', $id)->update([
                'ativo'         => true,
                'updated_at'    => NOW(),
            ]);
            Caex::where('id_campo_caex', $id)->where('ativo', 0)->update([
                'ativo'         => true,
                'updated_at'    => NOW(),
            ]);

            //pegar os cpf dos usuarios cadastrados no evento
            $cpf = Caex::select('cpf')->where('id_evento', $info->id_evento)->groupBy('cpf')->get();

            foreach ($cpf as $c) {

                //verificar se campo existe
                $campo = Caex::select('cpf')->where('id_evento', $info->id_evento)->where('campo', $info->nome)->where('cpf', $c->cpf)->first();

                $contar = Campo_caex::select('id')->where('id_evento', $info->id_evento)->get();

                $contar = count($contar) - 1;

                if (!isset($campo)) {

                    $info_cadastros = Caex::select('*')->where('id_evento', $info->id_evento)->where('cpf', $c->cpf)->first();

                    DB::insert(
                        'insert into caexes
                            (id_campo_caex, id_evento, nome, email, celular, cpf, campo, valor_salvo, cracha, palestras, ativo, created_at, updated_at)
                            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [$id, $info->id_evento, $info_cadastros->nome, $info_cadastros->email, $info_cadastros->celular, preg_replace("/[^0-9]/", "", $info_cadastros->cpf), $info->nome, 'null' . $id, $info->cracha, $info_cadastros->palestras, true, NOW(), NOW()]
                    );
                }
            }
        }
        //return abort(404);

    }

    public function caex(Request $request)
    {
        $id             = $request->input('id_evento');
        $nome_caex      = ucfirst($request->input('nome'));
        $tipo           = $request->input('tipo');
        $obrigatorio    = isset($request['obrigatorio']) ? true : false;
        $unico          = isset($request['unico']) ? true : false;
        $cracha         = isset($request['cracha']) ? true : false;
        $tamanho        = $request->input('tamanho');
        $opcoes         = $request->input('opcoes');

        //verificar se tipo vazio ou nulo
        if ($tipo == '' or $tipo == null) {
            $retorno = array(
                'tipo'      => $tipo,
                'mensagem'  => 'tipo VAZIO ou NULO',
                'id'        => $id,
                'sucesso'   => 3,
            );
            return response(json_encode($retorno, 200));
            exit();
        }

        //verificar se nome vazio ou nulo
        if ($nome_caex == '' or $nome_caex == null) {
            $retorno = array(
                'nome' => $nome_caex,
                'mensagem' => 'nome VAZIO ou NULO',
                'id' => $id,
                'sucesso' => 0,
            );
            return response(json_encode($retorno, 200));
            exit();
        }

        //buscar nome no banco
        $nome_salvo = DB::table('campo_caexes')
            ->select('nome')
            ->where('nome', [$nome_caex])
            ->where('id_evento', [$id])
            ->get();

        //lmpar nome
        $nome_salvo = str_replace("nome", "", $nome_salvo);
        $nome_salvo = str_replace("[{", "", $nome_salvo);
        $nome_salvo = str_replace("}]", "", $nome_salvo);
        $nome_salvo = str_replace(":", "", $nome_salvo);
        $nome_salvo = str_replace('"', "", $nome_salvo);

        // verificar nome exixtente
        if (isset($nome_salvo)) {

            // deixar nome letra minuscola
            $nome_salvo = strtolower($nome_salvo);
            $nome_caex = strtolower($nome_caex);

            //verificar se os nomes são iguais
            if ($nome_salvo == $nome_caex) {
                $retorno = array(
                    'nome' => $nome_caex,
                    'nome salvo' => $nome_salvo,
                    'mensagem' => 'nome existente',
                    'id' => $id,
                    'sucesso' => 1,
                );
                return response(json_encode($retorno, 200));
                exit();
            }
        }

        //salvar nome
        $caex = new Campo_caex();

        $caex->id_evento    = $id;
        $caex->nome         = ucfirst($nome_caex);
        $caex->slug         = $nome_caex;
        $caex->tipo         = $tipo;
        $caex->obrigatorio  = $obrigatorio;
        $caex->unico        = $unico;
        $caex->cracha       = $cracha;
        $caex->tamanho      = $tamanho;
        $caex->opcoes       = $opcoes;

        $caex->save();

        $ExiteCaex = Caex::select('*')->where('id_evento', $id)->get();

        if (isset($ExiteCaex)) {

            $pegarId    = Campo_caex::select('id')->where('slug', $nome_caex)->first();

            $Pegarcaexs = Caex::select('nome', 'email', 'celular', 'cpf', 'palestras')->where('id_evento', $id)->where('ativo','!=', 2)->groupBy('nome', 'email', 'celular', 'cpf', 'palestras')->get();

            foreach ($Pegarcaexs as $pegar) {
                //salvar novo campo tabela caex

                $NewCaex = new Caex();

                $NewCaex->id_campo_caex = $pegarId->id;
                $NewCaex->id_evento     = $id;
                $NewCaex->nome          = $pegar->nome;
                $NewCaex->email         = $pegar->email;
                $NewCaex->celular       = $pegar->celular;
                $NewCaex->cpf           = preg_replace("/[^0-9]/", "", $pegar->cpf);
                $NewCaex->campo         = ucfirst($nome_caex);
                $NewCaex->valor_salvo   = '';
                $NewCaex->cracha        = $cracha;
                $NewCaex->palestras     = $pegar->palestras;

                $NewCaex->save();
            }
        }

        //retornar mensagem SALVO
        $retorno = array(
            'salvou'    => $caex->save(),
            'nome'      => ucfirst($nome_caex),
            'nome slvo' => $nome_salvo,
            'mensagem'  => 'nome salvo',
            'id'        => $id,
            'sucesso'   => 2,
        );

        return response(json_encode($retorno, 200));
    }
}
