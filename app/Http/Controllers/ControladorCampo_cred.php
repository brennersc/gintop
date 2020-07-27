<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campo_cred;
use App\Evento;
use App\Credenciamento;
use Illuminate\Support\Facades\DB;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ControladorCampo_cred extends Controller
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

        // $validator = Validator::make($request->all(), [
        //     'nome' => 'required|unique:campo_creds|max:200|min:3'
        // ],
        //     [
        //         'required'  => 'O :attribute é obrigatorio!',
        //         'min'       => 'O :attribute não pode ter menos de :min caracteres!',
        //         'max'       => 'O :attribute não pode ter mais de :max caracteres!',
        //         'unique'    => 'O :attribute já esta sendo usado!'
        //     ]
        // );

        // if ($validator->fails()) {
        //     return redirect('evento')->with('error' , $validator);
        // }

        $data = new Campo_cred();


        $field = $request->all();

        if ($field['nome'] == '') {
            return redirect('evento')->with('nome', 'Campo nome vazio!!!');
            die();
        }

        $count = count($field['id_evento']);
        for ($i = 0; $i < $count; $i++) {
            $data['id_evento']      = $field['id_evento'][$i];
            $data['nome']           = $field['nome'][$i];
            $data['slug']           = $field['nome'][$i];
            $data['obrigatorio']    = isset($field['obrigatorio'][$i]) ? true : false;
            $data['unico']          = isset($field['unico'][$i]) ? true : false;
            $data['cracha']         = isset($field['cracha'][$i]) ? true : false;
            $data['tipo']           = $field['tipo'][$i];
            $data['opcoes']         = $field['opcoes'][$i];
            $data['tamanho']        = $field['tamanho'][$i];
            //$data->save();
            DB::insert(
                'insert into campo_creds (
                id_evento, nome, slug, obrigatorio, unico, cracha, tipo, opcoes, tamanho, created_at, updated_at) 
                values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['id_evento'], $data['nome'], $data['slug'], $data['obrigatorio'], $data['unico'],
                    $data['cracha'], $data['tipo'], $data['opcoes'], $data['tamanho'], NOW(), NOW()
                ]
            );
        }
        return redirect('evento')->with('cred', 'Campos credenciamento inseridos com Sucesso!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //

        $exibir = Evento::where('slug', $slug)->first();

        if (isset($exibir)) {
            return view('formularios.formCredenciamento', ['exibir' => $exibir]);
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
        $cred = Campo_cred::find($id);
        
        $nome = ucfirst($request->input('nome'));

        if( ($nome == 'Nome')||
            ($nome == 'Email')||
            ($nome == 'Celular')||
            ($nome == 'Cpf')){

                return redirect()->back()->with('erro', 'erro');
                die();
                
            }


        if (isset($cred)) {

            $cred->nome         = ucfirst($request->input('nome'));
            $cred->tipo         = $request->input('tipo');
            $cred->obrigatorio  = isset($request['obrigatorio']) ? true : false;
            $cred->unico        = isset($request['unico']) ? true : false;
            $cred->cracha       = isset($request['cracha']) ? true : false;
            $cred->tamanho      = $request->input('tamanho');
            $cred->opcoes       = $request->input('opcoes');

            $cred->save();

            Credenciamento::where('id_campo_cred', $id)->update([
                    'campo'          => ucfirst($request->input('nome')),
                    'updated_at'     => NOW()
                ]);

            return redirect()->back()->with('mensagem', 'atualizado');

        }
        return redirect('/info');

    }

    public function foo(Request $request)
    {
        // $ids = $request->id[0];
        // $achvs = $request->valor_salvo[0];
        // DB::table('campo_creds')->where('id', $ids)
        // ->update(['valor_salvo' => $achvs ]);
        // return redirect('evento');
        //     foreach($request->id as $key => $value){
        //         $quarters = Campo_cred::find($request->id[$key]);
        //         if(isset($quarters->valor_salvo)){
        //             $quarters->valor_salvo = $request->valor_salvo[$key];
        //         }else{
        //             $quarters->valor_salvo = null;
        //         }
        //         $quarters->save();
        //   }
        //   return redirect('evento');

        // $data= new Campo_cred();
        // $field = $request->all();
        // $count = count($field['id_evento']);

        // for ($i = 0; $i < $count ; $i++) {
        //     $data['id_evento']      = $field['id_evento'][$i];
        //     $data['nome']           = $field['nome'][$i];
        //     $data['slug']           = $field['nome'][$i];
        //     $data['obrigatorio']    = isset($field['obrigatorio'][$i]) ? true : false;
        //     $data['unico']          = isset($field['unico'][$i]) ? true : false;
        //     $data['cracha']         = isset($field['cracha'][$i]) ? true : false;
        //     $data['tipo']           = $field['tipo'][$i];
        //     $data['opcoes']         = $field['opcoes'][$i];
        //     $data['tamanho']        = $field['tamanho'][$i];
        //     $data->save();

        // }
        // return redirect('evento');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @author
     */
    public function destroy(Request $request)
    {
        //

        $id     = $request->input('id');
        $status = $request->input('status');
        
        // echo $id.'<br>';
        // echo $status;

        //pegar informaçoes do campo cadastrado
        $info = Campo_cred::select('id_evento', 'nome', 'cracha')->where('id', $id)->first();
        
        if($status == 0){

                Campo_cred::where('id', $id)->update([
                    'ativo'          => false,
                    'updated_at'     => NOW()
                ]);
                Credenciamento::where('id_campo_cred', $id)->where('ativo', 1)->update([
                    'ativo'          => false,
                    'updated_at'     => NOW()
                ]);
                //$cred->delete();
                //return redirect()->back();
            
        }else{

                Campo_cred::where('id', $id)->update([
                    'ativo'          => true,
                    'updated_at'     => NOW()
                ]);

                //verificar se usuario foi excluido e não ativar o campo
                Credenciamento::where('id_campo_cred', $id)->where('ativo', 0)->update([
                    'ativo'          => true,
                    'updated_at'     => NOW()
                ]);

                //pegar os cpf dos usuarios cadastrados no evento
                $cpf = Credenciamento::select('cpf')->where('id_evento', $info->id_evento)->groupBy('cpf')->get();

                foreach($cpf as $c){

                    //verificar se campo existe                    

                    $campo  = Credenciamento::select('cpf')->where('id_evento', $info->id_evento)->where('campo', $info->nome)->where('cpf', $c->cpf)->first();
                    
                    $contar = Campo_cred::select('id')->where('id_evento', $info->id_evento)->get();


                    $contar = count($contar) - 1;

                    if(!isset($campo)){

                        $info_cadastros = Credenciamento::select('*')->where('id_evento', $info->id_evento)->where('cpf', $c->cpf)->first();

                        DB::insert('insert into credenciamentos 
                            (id_campo_cred, id_evento, nome, email, celular, cpf, campo, valor_salvo, cracha, palestras, ativo, created_at, updated_at) 
                            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                            [$id, $info->id_evento, $info_cadastros->nome, $info_cadastros->email,$info_cadastros->celular,preg_replace("/[^0-9]/", "", $info_cadastros->cpf),$info->nome,'null'.$id,$info->cracha,$info_cadastros->palestras,true,NOW(),NOW()]);
                    }
                }
            
        }
        //return abort(404);

    }

    public function cred(Request $request)
    {
        $id             = $request->input('id_evento');
        $nome_cred      = ucfirst($request->input('nome'));
        $tipo           = $request->input('tipo');
        $obrigatorio    = isset($request['obrigatorio']) ? true : false;
        $unico          = isset($request['unico']) ? true : false;
        $cracha         = isset($request['cracha']) ? true : false;
        $tamanho        = $request->input('tamanho');
        $opcoes         = $request->input('opcoes');
      
        
        //verificar se tipo vazio ou nulo
        if ($tipo == '' or $tipo == null) {
            $retorno = array(
                'nome'          => $tipo,
                'mensagem'      => 'tipo VAZIO ou NULO',
                'id'            => $id,
                'sucesso'       => 3
            );
            return response(json_encode($retorno, 200));
            exit();
        }

        //verificar se nome vazio ou nulo
        if ($nome_cred == '' or $nome_cred == null) {
            $retorno = array(
                'nome'          => $nome_cred,
                'mensagem'      => 'nome VAZIO ou NULO',
                'id'            => $id,
                'sucesso'       => 0
            );
            return response(json_encode($retorno, 200));
            exit();
        }

        //buscar nome no banco
        $nome_salvo = DB::table('campo_creds')
        ->select('nome')
        ->where('nome', [$nome_cred])
        ->where('id_evento', [$id])
        ->get();

        //limpar nome
        $nome_salvo = str_replace("nome", "", $nome_salvo);
        $nome_salvo = str_replace("[{", "", $nome_salvo);
        $nome_salvo = str_replace("}]", "", $nome_salvo);
        $nome_salvo = str_replace(":", "", $nome_salvo);
        $nome_salvo = str_replace('"', "", $nome_salvo);

        // verificar nome exixtente
        if (isset($nome_salvo)) {

            // deixar nome letra minuscola
            $nome_salvo = strtolower($nome_salvo);
            $nome_cred  = strtolower($nome_cred);

            //verificar se os nomes são iguais
            if ($nome_salvo == $nome_cred) {
                $retorno = array(
                    'nome'          => $nome_cred,
                    'nome salvo'    => $nome_salvo,
                    'mensagem'      => 'nome existente',
                    'id'            => $id,
                    'sucesso'       => 1
                );
                return response(json_encode($retorno, 200));
                exit();
            }
        }
        

        //salvar nome
        $cred = new Campo_cred();

        $cred->id_evento    = $id;
        $cred->nome         = ucfirst($nome_cred);
        $cred->slug         = $nome_cred;
        $cred->tipo         = $tipo;
        $cred->obrigatorio  = $obrigatorio;
        $cred->unico        = $unico;
        $cred->cracha       = $cracha;
        $cred->tamanho      = $tamanho;
        $cred->opcoes       = $opcoes;

        $cred->save();

        $ExiteCred = Credenciamento::select('*')->where('id_evento', $id)->get();

        if (isset($ExiteCred)) {
            $pegarId    = Campo_cred::select('id')->where('slug', $nome_cred)->first();

            $Pegarcreds = Credenciamento::select('nome', 'email', 'celular', 'cpf', 'palestras')->where('id_evento', $id)->where('ativo','!=', 2)->groupBy('nome', 'email', 'celular', 'cpf', 'palestras')->get();
            
            $contar     = Campo_cred::select('id')->where('id_evento', $id)->get();

            $contar = count($contar) - 1;

            foreach ($Pegarcreds as $pegar) {
                //salvar novo campo tabela credenciamentos

                $NewCred = new Credenciamento();

                $NewCred->id_campo_cred    = $pegarId->id;
                $NewCred->id_evento        = $id;
                $NewCred->nome             = $pegar->nome;
                $NewCred->email            = $pegar->email;
                $NewCred->celular          = $pegar->celular;
                $NewCred->cpf              = preg_replace("/[^0-9]/", "",  $pegar->cpf);
                $NewCred->campo            = ucfirst($nome_cred);
                $NewCred->valor_salvo      = 'null'.$contar ;
                $NewCred->cracha           = $cracha;
                $NewCred->palestras        = $pegar->palestras;

                $NewCred->save();
            }
        }
            
        //retornar mensagem SALVO
        $retorno = array(
                'salvou'        => $cred->save(),
                'nome'          => ucfirst($nome_cred),
                'nome slvo'     => $nome_salvo,
                'mensagem'      => 'nome salvo',
                'id'            => $id,
                'sucesso'       => 2
            );

        return response(json_encode($retorno, 200));
    }
}