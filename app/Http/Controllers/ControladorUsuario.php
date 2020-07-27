<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Usuario;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Empresa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ControladorUsuario extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexView()
    {
        return view('usuario.usuario');
    }

    public function index()
    {
       // echo Auth::user()->name;
        $usuario = User::all();
        return $usuario->toJson();
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
        $id         = $request->input('empresas');

        $empresa    = Empresa::select('nome_fantasia')->where('id',  $id)->first();      

        // $usuario    = new Usuario();
        // $usuario->nome          = $request->input('nome');
        // $usuario->email         = $request->input('email');
        // $usuario->cargo         = $request->input('cargo');
        // $usuario->id_empresa    = $request->input('empresas');
        // $usuario->empresa       = $empresa->nome_fantasia;
        // $usuario->save();

        $senha = mt_rand();

        $usuario               = new User();
        $usuario->name         = $request->input('nome');
        $usuario->email        = $request->input('email');
        $usuario->cargo        = $request->input('cargo');
        $usuario->id_empresa   = $request->input('empresas');
        $usuario->empresa      = $empresa->nome_fantasia;
        $usuario->password     = bcrypt($senha);
        $usuario->save();


        $mail = Mail::send('email.usuario', ['usuario' => $usuario, 'senha' => $senha], function ($message) use ($usuario, $senha) {
            $message->from('contato@gintop.com', 'GinTop');
            $message->to($usuario->email, $usuario->nome);
            $message->subject('Cadastro Feito!');
        });


        // User::create([
        //     'name'          => $request->input('nome'),
        //     'email'         => $request->input('email'),
        //     'cargo'         => $request->input('cargo'),
        //     'id_empresa'    => $request->input('empresas'),
        //     'empresa'       => $empresa->nome_fantasia,
        //     'password'      => bcrypt('12345678')
        // ]);

        // DB::insert('insert into users (
        //     id_empresa, name, email, cargo, empresa, password, created_at, updated_at ) values (?, ?, ?, ?, ?, ?, ?, ?)', 
        //     [$request->input('empresas'), $request->input('nome'), $request->input('email'), $request->input('cargo'),
        //     $empresa->nome_fantasia, bcrypt('12345678'), NOW(), NOW()]);

        return json_encode($usuario);
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
        $usuario = User::find($id);
        if (isset($usuario)) {
            return json_encode($usuario);
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


        $empresa    = Empresa::select('nome_fantasia')->where('id',  $request->input('empresas'))->first();   

        $usuario    = User::find($id);
        //$user       = User::find($id);

        // if (isset($usuario)) {
        //     $usuario->nome          = $request->input('nome');
        //     $usuario->email         = $request->input('email');
        //     $usuario->cargo         = $request->input('cargo');
        //     $usuario->id_empresa    = $request->input('empresas');
        //     $usuario->empresa       = $empresa->nome_fantasia;
        //     $usuario->save();           
        //}
        if (isset($usuario)) {
            $usuario->name             = $request->input('nome');
            $usuario->email            = $request->input('email');
            $usuario->cargo            = $request->input('cargo');
            $usuario->id_empresa       = $request->input('empresas');
            $usuario->empresa          = $empresa->nome_fantasia;
            $usuario->save();
        }

        return json_encode($usuario);

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
       // $usuario    = User::find($id);
        $usuario       = User::find($id);

        $senha  = base64_encode('$empresa->celular*');

        $id_senha = '/'.$id.'user';
        $id_senha  = base64_encode($id_senha);

        if (isset($usuario)) {
            //$usuario->delete();
            //$user->delete();
            if($usuario->ativo == 1){
                // DB::table('usuarios')->where('id', $id)->update([
                //     'ativo'         => false,
                //     'updated_at'    => NOW()                    
                // ]);
                DB::table('users')->where('id', $id)->update([
                    'ativo'         => false,
                    'password'      => bcrypt($senha),
                    'updated_at'    => NOW()
                ]);

                $status = 0;
            }else{
                // DB::table('usuarios')->where('id', $id)->update([
                //     'ativo'          => true,
                //     'updated_at'     => NOW()
                // ]);
                DB::table('users')->where('id', $id)->update([
                    'ativo'          => true,
                    'password'       => bcrypt($id_senha),
                    'updated_at'     => NOW()
                ]);

                $status = 1;
            }

            $mail = Mail::send('email.desativado', ['usuario' => $usuario, 'status' => $status, 'senha' => $id_senha], function ($message) use ($usuario, $status, $id_senha) {
                $message->from('contato@gintop.com', 'GinTop');
                $message->to($usuario->email, $usuario->nome);
                $message->subject('Cadastro Feito!');
            });

            return response('OK', 200);
        }
        return abort(404);
    }

    public function email(Request $request)
    {

        $email = $request->input('email');

        $pegaemail = DB::table('users')
            ->select('email')
            ->where('email', [$email])
            ->get();

        //$pegaemail = preg_replace("/[^0-9]/", "", $pegaemail);

        $pegaemail = str_replace("email", "", $pegaemail);
        $pegaemail = str_replace("[{", "", $pegaemail);
        $pegaemail = str_replace("}]", "", $pegaemail);
        $pegaemail = str_replace(":", "", $pegaemail);
        $pegaemail = str_replace('"', "", $pegaemail);

        //deixar letras minusculas
        $email      = strtolower($email);
        $pegaemail  = strtolower($pegaemail);

        if (isset($pegaemail)) {
            if ($pegaemail === $email) {
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
            'email'     =>  $email,
            'email banco'     =>  $pegaemail,
            'mensagem'  => "sucesso!",
            'sucesso'   => 0
        );
        return response(json_encode($retorno, 200));
    }

    public function senha(Request $request)
    {

        $senha          = $request->input('senha');
        $confirmasenha  = $request->input('confirmasenha');
        $id             = $request->input('id');

        if ($senha == $confirmasenha) {

            $usuario = User::find($id);
            if (isset($usuario)) {
                $usuario->password = Hash::make($senha);
                $usuario->save();

                $retorno = array(
                    'mensagem'          => "sucesso!",
                    'sucesso'           => 0
                );
                return response(json_encode($retorno, 200));
            }
        } else {
            $retorno = array(
                'mensagem'          => "ERRO SENHAS DIFERENTES!",
                'sucesso'           => 1
            );
            return response(json_encode($retorno, 200));
        }
    }

    public function procurar(Request $request)
    {
        $busca   = $request->input('nome');

        if($busca != ""){ 

            $usuario = User::where('ativo', true)->where('name','LIKE','%'.$busca.'%')->orWhere('email','LIKE','%'.$busca.'%')->get();
            // $itens = DB::table('empresas')
            // ->where('nome_fantasia','LIKE','%'.$busca.'%')
            // ->orWhere('cnpj','LIKE','%'.$busca.'%')
            // ->get();
            
        if(count($usuario) > 0){

            echo  '<div class="alert alert-success " role="alert">';
            echo    'Os resultados para <b> '.$busca.' </b> são :';
            echo  '</div>';

            echo '<table class="table table-ordered table-hover">';
            echo    '<thead> <tr>';
            echo    '<th>ID</th>';
            echo    '<th>Nome</th>';
            echo    '<th>Email</th>';
            echo    '<th>Cargo</th>';
            echo    '<th>Empresa</th>';
            echo    '<th>Ações</th>';
            echo    '</tr> </thead>';

            foreach($usuario as $tabela){
               
            echo '<div class="procurar">';
            echo '<tr>';

            // foreach($valor_campos as $valor){
            //     echo '<td>'.$valor.'</td>';
            // }

            if(($tabela->cargo) == 0){
                        $tabela->cargo = 'Administrador';
            }
            if(($tabela->cargo) == 1){
                $tabela->cargo = 'Empresa';
            }
            if(($tabela->cargo) == 2){
                $tabela->cargo = 'Usuário';
            }

            echo    '<td>'.$tabela->id.'</td>';
            echo    '<td>'.$tabela->name.'</td>';
            echo    '<td>'.$tabela->email.'</td>';
            echo    '<td>'.$tabela->cargo.'</td>';
            echo    '<td><a href=/infoempresa/"'.$tabela->id.'">'.$tabela->empresa.'</a></td>';
            
            echo '<td>';
            echo    '<div class="btn-group" role="group" aria-label="Basic example">';
            echo        '<button class="btn btn-sm btn-primary" onclick="editar('.$tabela->id.')"><i class="far fa-edit"></i> Editar </button>';
            echo        '<button class="btn btn-sm btn-danger" onclick="modalremover('.$tabela->id.')"><i class="far fa-trash-alt"></i> Apagar </button>';
            echo    '</div>';
            echo '</td>';   

            echo '</tr>';
            echo '</div>';
            
        }

            echo '</table>';
            echo '<hr>';
        
        }
        if(count($usuario) == 0){

        echo '<div class="alert alert-danger" role="alert">';
        echo 'Nenhum resultado encontrado!';
        echo '</div>';
        }  
        }
        
    }

}