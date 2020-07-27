<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Evento;
use App\Usuario;
use App\User;
use Validator;
use App\Exports\UsersExportEmpresa;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Controladorempresa extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function indexView()
    {
        return view('empresa.empresa');
    }

    public function index()
    {
        //echo Auth::user()->email; die;
        $empresa = Empresa::orderBy('nome_fantasia')->where('ativo', '!=', 2)->get();
        return $empresa->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //return view('empresa');
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

        $empresa = new Empresa();
        $empresa->cnpj          = preg_replace("/[^0-9]/", "", $request->input('cnpj'));
        $empresa->nome_fantasia = $request->input('nome_fantasia');
        $empresa->razao_social  = $request->input('razao_social');
        $empresa->responsavel   = $request->input('responsavel');
        $empresa->telefone      = $request->input('telefone');
        $empresa->celular       = $request->input('celular');
        $empresa->email         = $request->input('email');
        $empresa->site          = $request->input('site');
        $empresa->cep           = $request->input('cep');
        $empresa->estado        = $request->input('estado');
        $empresa->cidade        = $request->input('cidade');
        $empresa->bairro        = $request->input('bairro');
        $empresa->rua           = $request->input('rua');
        $empresa->numero        = $request->input('numero');
        $empresa->complemento   = $request->input('complemento');
        $empresa->save();

        //pega id pelo cnpj
        $id     = Empresa::select('id')->where('cnpj',$empresa->cnpj)->first();

        $senha  = base64_encode($empresa->celular);

        $user   = User::select('email')->where('email',$empresa->email)->first();

        $status = 0;

        if(!isset($user)){
        //insere usuario
        DB::insert('insert into usuarios (
            id_empresa, nome, email, cargo, empresa, created_at, updated_at ) values (?, ?, ?, ?, ?, ?, ?)', 
            [$id->id, $empresa->responsavel, $empresa->email, '1', $empresa->nome_fantasia, NOW(), NOW()]);

        //insere login
        DB::insert('insert into users (
            id_empresa, name, email, cargo, empresa, password, created_at, updated_at ) values (?, ?, ?, ?, ?, ?, ?, ?)', 
            [$id->id, $empresa->responsavel, $empresa->email, '1', $empresa->nome_fantasia, bcrypt($senha), NOW(), NOW()]);

            $status = 1;
        }        

        // Enviar email
        $mail = Mail::send('email.empresa', ['empresa' => $empresa, 'status' => $status], function ($message) use ($empresa, $status) {
            $message->from('contato@gintop.com', 'GinTop');
            $message->to($empresa->email, $empresa->responsavel);
            $message->subject('Cadastro Feito!');
        });

        echo $empresa;

        if(isset($mail)){
            return json_encode($empresa);
        }
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
        $empresa = Empresa::find($id);

        if (isset($empresa)) {
            return json_encode($empresa);
        }
        return abort(404);
    }

    public function info($id)
    {
        //
        $empresa    = Empresa::find($id);
        $evento     = Evento::where('id_empresa', $id)->get();
        $usuario    = User::where('id_empresa', $id)->get();

        if (isset($empresa)) {
            return view('empresa.informacoes', ['empresa' => $empresa,'evento' => $evento, 'usuario' => $usuario]);
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
        $empresa = Empresa::find($id);
        if (isset($empresa)) {
            $empresa->cnpj          = preg_replace("/[^0-9]/", "", $request->input('cnpj'));
            $empresa->nome_fantasia = $request->input('nome_fantasia');
            $empresa->razao_social  = $request->input('razao_social');
            $empresa->responsavel   = $request->input('responsavel');
            $empresa->telefone      = $request->input('telefone');
            $empresa->celular       = $request->input('celular');
            $empresa->email         = $request->input('email');
            $empresa->site          = $request->input('site');
            $empresa->cep           = $request->input('cep');
            $empresa->estado        = $request->input('estado');
            $empresa->cidade        = $request->input('cidade');
            $empresa->bairro        = $request->input('bairro');
            $empresa->rua           = $request->input('rua');
            $empresa->numero        = $request->input('numero');
            $empresa->complemento   = $request->input('complemento');
            $empresa->save();
            return json_encode($empresa);
        }
        return abort(404);
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
        $empresa    = Empresa::find($id);
        if (isset($empresa)) {
            if($empresa->ativo == 1){
                DB::table('empresas')->where('id', $id)->update([
                    'ativo'          => false,
                    'updated_at'     => NOW()
                ]);
                DB::table('eventos')->where('id_empresa', $id)->update([
                    'ativo'          => false,
                    'updated_at'     => NOW()
                ]);
                // DB::table('usuarios')->where('id_empresa', $id)->update([
                //     'ativo'          => false,
                //     'updated_at'     => NOW()
                // ]);
                // DB::table('users')->where('id_empresa', $id)->update([
                //     'ativo'          => false,
                //     'updated_at'     => NOW()
                // ]);
            }else{
                DB::table('empresas')->where('id', $id)->update([
                    'ativo'          => true,
                    'updated_at'     => NOW()
                ]);
                DB::table('eventos')->where('id_empresa', $id)->update([
                    'ativo'          => true,
                    'updated_at'     => NOW()
                ]);
                // DB::table('usuarios')->where('id_empresa', $id)->update([
                //     'ativo'          => true,
                //     'updated_at'     => NOW()
                // ]);
                // DB::table('users')->where('id_empresa', $id)->update([
                //     'ativo'          => true,
                //     'updated_at'     => NOW()
                // ]);
            }
            //$empresa->delete();
            return response('OK', 200);
        }
        return abort(404);
    }

    public function cnpj(Request $request)
    {
        $empresacnpj = preg_replace("/[^0-9]/", "", $request->input('cnpj'));

        $cnpj = DB::table('empresas')
            ->select('cnpj')
            ->where('cnpj', [$empresacnpj])
            ->get();

        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);

        if (isset($cnpj)) {
            if ($cnpj == $empresacnpj) {
                $retorno = array(
                    'mensagem' => "O CNPJ já está cadastrado.",
                    'sucesso' => 1
                );
                return response(json_encode($retorno, 200));
                exit();
            }
        }
        if ($empresacnpj != $cnpj) {
            $validator = Validator::make(
                $request->all(),
                [
                    'cnpj' => 'cnpj'
                ]
            );
            if ($validator->fails()) {
                $retorno = array(
                    'mensagem' => 'CNPJ inválido',
                    'sucesso' => 2
                );

                return response(json_encode($retorno, 200));
                exit();
            }
        }

        $retorno = array(
            'mensagem' => 'CNPJ válido',
            'sucesso' => 0
        );

        return response(json_encode($retorno, 200));
        exit();
    }

    public function email(Request $request)
    {

        $email      = $request->input('email');

        $pegaemail = DB::table('empresas')
                        ->select('email')
                        ->where('email', [$email])
                        ->get();

        $emailUSER = DB::table('users')
                        ->select('email')
                        ->where('email', [$email])
                        ->get();

        //limpar email users
        $emailUSER = str_replace("email", "",   $emailUSER);
        $emailUSER = str_replace("[{", "",      $emailUSER);
        $emailUSER = str_replace("}]", "",      $emailUSER);
        $emailUSER = str_replace(":", "",       $emailUSER);
        $emailUSER = str_replace('"', "",       $emailUSER);

        //limpar email empresas
        $pegaemail = str_replace("email", "", $pegaemail);
        $pegaemail = str_replace("[{", "", $pegaemail);
        $pegaemail = str_replace("}]", "", $pegaemail);
        $pegaemail = str_replace(":", "", $pegaemail);
        $pegaemail = str_replace('"', "", $pegaemail);

        //deixar letras minusculas
        $email      = strtolower($email);
        $pegaemail  = strtolower($pegaemail);
        $emailUSER  = strtolower($emailUSER);

        if (isset($pegaemail)) {
            if (($pegaemail === $email) || ($emailUSER === $email)){
                $retorno = array(
                    'email'     =>  $email,
                    'mensagem'  => "O Email já está cadastrado.",
                    'sucesso'   => 1
                );
                return response(json_encode($retorno, 200));
                exit();
            }
        }

        $retorno = array(
            'email'         =>  $email,
            'email banco'   =>  $pegaemail,
            'mensagem'      => "sucesso!",
            'sucesso'       => 0
        );
        return response(json_encode($retorno, 200));
    }

    public function procurar(Request $request)
    {
        $busca   = $request->input('nome');

        if ($busca != "") {
            $empresa = Empresa::where('nome_fantasia', 'LIKE', '%'.$busca.'%')->where('ativo', true)->orWhere('cnpj', 'LIKE', '%'.$busca.'%')->get();
            // $itens = DB::table('empresas')
            // ->where('nome_fantasia','LIKE','%'.$busca.'%')
            // ->orWhere('cnpj','LIKE','%'.$busca.'%')
            // ->get();
            
            if (count($empresa) > 0) {
                echo  '<div class="alert alert-success " role="alert">';
                echo    'Os resultados para <b> '.$busca.' </b> são :';
                echo  '</div>';

                echo '<table class="table table-ordered table-hover">';
                echo    '<tr>';
                echo    '<th>ID</th>';
                echo    '<th>Nome</th>';
                echo    '<th>CNPJ</th>';
                echo    '<th>Razão Social</th>';
                echo    '<th>Ações</th>';
                echo    '</tr>';

                foreach ($empresa as $tabela) {
                    echo '<div class="procurar">';
                    echo '<tr>';

                    echo    '<td>'.$tabela->id.'</td>';
                    echo    '<td>'.$tabela->nome_fantasia.'</td>';
                    echo    '<td  class="cnpj" >'.$tabela->cnpj.'</td>';
                    echo    '<td>'.$tabela->razao_social.'</td>';

            
                    echo '<td>';
                    echo        '<div class="btn-group" role="group" aria-label="Basic example">';
                    echo            '<button class="btn btn-sm btn-primary" onclick="editar('.$tabela->id.')"><i class="far fa-edit"></i>  Editar </button>';
                    echo            '<button class="btn btn-sm btn-danger" onclick="modalremover('.$tabela->id.')"><i class="far fa-trash-alt"></i> Apagar </button>';
                    echo            '<a class="btn btn-sm btn-success"  href="/infoempresa/'.$tabela->id.'"><i class="fas fa-info-circle"></i> Info </a> ';
                    echo        '</div>';
                    echo '</td>';

                    echo '</tr>';
                    echo '</div>';
                }

                echo '</table>';
                echo '<hr>';
            }
            if (count($empresa) == 0) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Nenhum resultado encontrado!';
                echo '</div>';
            }
        }
    }

    //exportar
    public function export(Empresa $id)
    {
        return Excel::download(new UsersExportEmpresa($id), 'infomações-empresa.xlsx');
    }
}
