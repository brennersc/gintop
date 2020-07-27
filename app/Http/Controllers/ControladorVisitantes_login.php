<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Credenciamento;
use App\Evento;
use App\Visitantes_login;
use App\Campo_cred;
use App\Sala;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ControladorVisitantes_login extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    // public function __construct()
    // {
    //     $this->middleware('auth:visitantes_login');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $eventos = DB::table('eventos')
            ->selectRaw('distinct eventos.*')
            ->join('credenciamentos', 'credenciamentos.id_evento', '=', 'eventos.id')
            ->where('cpf', Auth::guard('visitantes_login')->user()->cpf)
            ->where('credenciamentos.ativo', true)
            ->orderBy('eventos.data_inicio')
            ->get();

        $mesas = DB::table('mesas')
            ->selectRaw('mesas.*')
            ->selectRaw('venda_mesas.*')
            ->join('venda_mesas', 'venda_mesas.id_mesa', '=', 'mesas.id')
            ->where('cpf', Auth::guard('visitantes_login')->user()->cpf)
            ->where('venda_mesas.ativo', true)
            ->get();

        $ingressos = DB::table('ingressos')
            ->selectRaw('ingressos.*')
            ->selectRaw('venda_ingressos.*')
            ->join('venda_ingressos', 'venda_ingressos.id_ingresso', '=', 'ingressos.id')
            ->where('cpf', Auth::guard('visitantes_login')->user()->cpf)
            ->where('venda_ingressos.ativo', true)
            ->orderBy('ingressos.data_inicio')
            ->get();

        //return  json_encode($mesas);

        if (isset($eventos)) {
            return view('loginVisitantes.login_visitante', ['eventos' => $eventos, 'ingressos' => $ingressos, 'mesas' => $mesas]);
        }
        return abort(404);
    }

    public function update(Request $request, $id)
    {

        $visitante    = Visitantes_login::find($id);

        if (isset($visitante)) {
            $visitante->name    = $request->input('nome');
            $visitante->email   = $request->input('email');
            $visitante->save();

            return redirect('visitante')->with('mensagem', 'atualizada');
        }

        return abort(404);
    }

    public function edit($id_evento)
    {
        $id = $id_evento;

        $campoCred  = Campo_cred::where('id_evento', '=', $id)
            ->where('ativo', true)
            ->get();

        $exibir     = Evento::where('id', $id)->first();

        $salas      = Sala::select('nome', 'id', 'data_inicio', 'data_fim', 'hora_inicio', 'hora_fim')
            ->where('id_evento', $id)
            ->where('ativo', true)
            ->get();

        if (isset($exibir)) {
            return view('loginVisitantes.editar', ['exibir' => $exibir, 'campoCred' => $campoCred, 'salas' => $salas]);
        }
        return abort(404);
    }

    public function senha(Request $request)
    {

        $senha          = $request->input('senha');
        $confirmasenha  = $request->input('confirmasenha');
        $id             = $request->input('id');

        if ($senha == $confirmasenha) {

            $usuario = Visitantes_login::find($id);
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

    //horas
    public function horas(Request $request)
    {

        $nome = $request['nome'];
        //echo $nome .   '<br>';


        $horas = Sala::select('data_inicio', 'hora_inicio', 'hora_fim')->where('nome', $nome)->first();

        // ECHO $horas->data_inicio .   '<br>';
        // ECHO $horas->hora_inicio .   '<br>';
        // ECHO $horas->hora_fim .   '<br>';

        $compara = Sala::select('*')->where('nome', '!=', $nome)->where('nome', '<>', $nome)->get();

        $nomeT = '';

        foreach ($compara as $value) {
            if($value->nome != $nome){
                if ($horas->data_inicio == $value->data_inicio) {
                    if (($value->hora_inicio > $horas->hora_inicio) && ($value->hora_inicio < $horas->hora_fim)) {
                        $nome = $value->nome;
                    }
                    if (($value->hora_fim  > $horas->hora_inicio) && ($value->hora_fim < $horas->hora_fim)) {
                        $nome = $value->nome;
                    }
                    if ($value->hora_fim == $horas->hora_fim) {
                        $nome = $value->nome;
                    }
                }
                $nomeT = $nome.','.$nomeT;
            }
        }

        // echo $nomeT;
        // die();

        $retorno = array(
            'mensagens'     => 'sucesso',
            'nome'          => $nomeT,
            'sucesso'       => 0
        );

        return response(json_encode($retorno, 200));
    }

    //APAGAR
    public function destroy($cpf, $id_evento)
    {

        $cpf_ = base64_decode($cpf);
        $cpf_ = preg_replace("/[^0-9]/", "", $cpf_);

        $Visitatnte = Credenciamento::select('cpf')->where('cpf', $cpf_);

        if (isset($Visitatnte)) {
            DB::table('credenciamentos')->where('cpf', $cpf_)->where('id_evento', $id_evento)
                ->update([
                    'ativo'          => false,
                    'updated_at'     => NOW()
                ]);
            return redirect('visitante')->with('apagado', 'apagado');
        }
        return abort(404);
    }

    public function remember(Request $request)
    {

        $cpf = $request->input('cpf');

        $cpf_ = preg_replace("/[^0-9]/", "", $cpf);

        $senha = base64_encode($cpf_);

        $verifica_cpf = Visitantes_login::select('name','email','cpf')->where('cpf', $cpf_)->first();
        
        if($verifica_cpf != null){
            
            DB::table('visitantes_logins')->where('cpf', $cpf_)->update([
                'password'       => bcrypt($senha),
                'updated_at'     => NOW()
            ]);
            //die();

            $mail = Mail::send('email.recuperar', ['verifica_cpf' => $verifica_cpf, 'senha' => $senha], function ($message) use ($verifica_cpf, $senha) {
                $message->from('contato@gintop.com', 'GinTop');
                $message->to($verifica_cpf->email);
                $message->subject('Recuperação de Senha!');
            });

            return redirect()->back()->with('sucesso', 'sucesso');

        }else{


            return redirect()->back()->with('erro', 'erro')->withInput();
        }

    }
}
