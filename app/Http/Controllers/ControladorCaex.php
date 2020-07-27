<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caex;
use App\Evento;
use App\Campo_caex;
use App\Sala;
use App\Visitantes_login;
use App\Exports\UsersExportCaex;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\CredImport;
use Illuminate\Support\Facades\Mail;
use Validator;

class ControladorCaex extends Controller
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

    // CADASTROS
    public function storeCadastro(Request $request, $id)
    {
        //
        $data = new Caex();
        $field = $request->all();
        $count = count($field['id_campo_caex']);

        $palestras      = isset($request['palestras']) ? implode(' / ', $request['palestras']) : 'Nenhuma selecionda';

        $cpf = preg_replace("/[^0-9]/", "", $field['cpf'])[0];

        $query = Caex::select('cpf')->where('cpf', '=', $cpf)->where('id_evento', '=', $field['id_evento'][0])->first();

        if (isset($query)) {

            return redirect()->back()->with('mensagem', 'CPF');
            //die();
        }

        //Gravar quantidade na tabela salas
        $pal = explode(' / ', $palestras);
        $id = $field['id_evento'][0];
        foreach ($pal as $value) {

            $qnte = Sala::select('quantidade', 'visitantes')->where('nome', $value)->where('id_evento', $id)->first();
            
            if(isset($qnte)){
                if ($qnte->quantidade != 0) {
                    if ($qnte->visitantes < $qnte->quantidade) {  

                        //entra se quantidade for diferente de 0 e visitantes menores que quantidade 
                        $soma = ($qnte->visitantes + 1);
                        //soma mais um a os visitentes e faz o update 
                        DB::table('salas')->where('nome', $value)->where('id_evento', $id)
                            ->update([
                                'visitantes'    => $soma,
                                'updated_at'    => NOW()
                            ]);
                    }
                }
            }
        }


        for ($i = 0; $i < $count; $i++) {
            $data['id_campo_caex']  = $field['id_campo_caex'][$i];
            $data['id_evento']      = $field['id_evento'][$i];
            $data['nome']           = $field['nome'][$i];
            $data['email']          = $field['email'][$i];
            $data['celular']        = $field['celular'][$i];
            $data['cpf']            = preg_replace("/[^0-9]/", "", $field['cpf'])[$i];
            $data['campo']          = str_replace(";", ",", $field['campo'][$i]);
            $data['cracha']         = $field['cracha'][$i];
            $data['tipo']           = $field['tipo'][$i];
            //$data['valor_salvo']    = $field['valor_salvo'][$i];

            if ($data['tipo'] == 'checkbox') {
                if (isset($field['checkbox'])) {
                    $data['valor_salvo']  = implode(' / ', $field['checkbox']);
                } else {
                    $data['valor_salvo'] = 'null'.$i;
                }
            } else {
                $data['valor_salvo']    = isset($field['valor_salvo'][$i]) ? $field['valor_salvo'][$i] : 'null'.$i;
            }
            DB::insert(
                'insert into caexes (
                id_campo_caex, id_evento, nome, email, celular, cpf, campo, cracha, valor_salvo, palestras, created_at, updated_at) 
                values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['id_campo_caex'], $data['id_evento'], $data['nome'], $data['email'],
                    $data['celular'], $data['cpf'], $data['campo'], $data['cracha'], str_replace(";", ",", $data['valor_salvo']), $palestras, NOW(), NOW()
                ]
            );
        }
                //die();
        $data['palestras']  = $palestras;

        $cpf        =   preg_replace("/[^0-9]/", "", $data['cpf']);
        $password   =   bcrypt($cpf);

        $exiteCPF = Visitantes_login::select('cpf')->where('cpf', $cpf)->first();

        if (!isset($exiteCPF)) {
            DB::insert(
                'insert into visitantes_logins (
                name, email, cpf, password, created_at, updated_at) 
                values (?, ?, ?, ?, ?, ?)',
                [$data['nome'], $data['email'], $cpf, $password, NOW(), NOW()]
            );
        }

        // Enviar email
        Mail::send('email.view', ['data' => $data],  function ($message) use ($data) {

            $message->from('contato@gintop.com', 'GinTop');
            $message->to($data['email'], $data['nome']);
            $message->subject('Cadastro Feito!');
        });
        
        return redirect('/caexs/' . $id);
    }

    //visitantes caex
    public function store(Request $request, $slug)
    {

        $data = new Caex();
        $field = $request->all();
        $count = count($field['id_campo_caex']);

        $palestras   = isset($request['palestras']) ? implode(' / ', $request['palestras']) : 'Nenhuma selecionda';

        $cpf = preg_replace("/[^0-9]/", "", $field['cpf'])[0];

        $query = Caex::select('cpf')->where('cpf', '=', $cpf)->where('id_evento', '=', $field['id_evento'][0])->first();

        if (isset($query)) {

            return redirect('/caex/' . $slug)->with('erro', 'CPF');
            //die();
        }

        //Gravar quantidade na tabela salas
        $pal = explode(' / ', $palestras);
        $id = $field['id_evento'][0];
        foreach ($pal as $value) {

            $qnte = Sala::select('quantidade', 'visitantes')->where('nome', $value)->where('id_evento', $id)->first();

            if(isset($qnte)){
                if ($qnte->quantidade != 0) {
                    if ($qnte->visitantes < $qnte->quantidade) {  

                        //entra se quantidade for diferente de 0 e visitantes menores que quantidade 
                        $soma = ($qnte->visitantes + 1);
                        //soma mais um a os visitentes e faz o update 
                        DB::table('salas')->where('nome', $value)->where('id_evento', $id)
                            ->update([
                                'visitantes'    => $soma,
                                'updated_at'    => NOW()
                            ]);
                    }
                }
            }
        }

        for ($i = 0; $i < $count; $i++) {
            $data['id_campo_caex']  = $field['id_campo_caex'][$i];
            $data['id_evento']      = $field['id_evento'][$i];
            $data['nome']           = $field['nome'][$i];
            $data['email']          = $field['email'][$i];
            $data['celular']        = $field['celular'][$i];
            $data['cpf']            = preg_replace("/[^0-9]/", "", $field['cpf'])[$i];
            $data['campo']          = str_replace(";", ",", $field['campo'][$i]);
            $data['cracha']         = $field['cracha'][$i];
            $data['tipo']           = $field['tipo'][$i];
            
            //$data['valor_salvo']    = $field['valor_salvo'][$i];

            if ($data['tipo'] == 'checkbox') {
                if (isset($field['checkbox'])) {
                    $data['valor_salvo']  = implode(' / ', $field['checkbox']);
                } else {
                    $data['valor_salvo'] = 'null'.$i;
                }
            } else {
                $data['valor_salvo']    = isset($field['valor_salvo'][$i]) ? $field['valor_salvo'][$i] : 'null'.$i;
            }
            DB::insert(
                'insert into caexes (
                id_campo_caex, id_evento, nome, email, celular, cpf, campo, cracha, valor_salvo, palestras, created_at, updated_at) 
                values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['id_campo_caex'], $data['id_evento'], $data['nome'], $data['email'],
                    $data['celular'], $data['cpf'], $data['campo'], $data['cracha'], str_replace(";", ",", $data['valor_salvo']), $palestras, NOW(), NOW()
                ]
            );
        }
        
        $data['palestras']  = $palestras;

        $cpf        =   preg_replace("/[^0-9]/", "", $data['cpf']);
        $password   =   bcrypt($cpf);

        $exiteCPF = Visitantes_login::select('cpf')->where('cpf', $cpf)->first();

        if (!isset($exiteCPF)) {
            DB::insert(
                'insert into visitantes_logins (
                name, email, cpf, password, created_at, updated_at) 
                values (?, ?, ?, ?, ?, ?)',
                [$data['nome'], $data['email'], $cpf, $password, NOW(), NOW()]
            );
        }


        // Enviar email
        Mail::send('email.view', ['data' => $data], function ($message) use ($data) {
            $message->from('contato@gintop.com', 'GinTop');
            $message->to($data['email'], $data['nome']);
            $message->subject('Cadastro Feito!');
        });

        return redirect('/caex/' . $slug)->with('mensagem', $data['nome']);
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
        $campoCred  = Campo_caex::where('id_evento', '=', $id)
                        ->where('ativo', true)
                        ->get();

        $exibir     = Evento::where('id', $id)->first();

        $salas      = Sala::select('nome', 'id', 'data_inicio','data_fim', 'hora_inicio', 'hora_fim', 'nome_local')
                        ->whereRaw('id_evento = ? and ativo = true', [$exibir->id])
                        ->get();
                
        // (visitantes < quantidade or quantidade = 0) and 
        // $salas = DB::select('select nome, id, data_inicio, data_fim, hora_inicio, hora_fim, nome_local from salas where (visitantes < quantidade or quantidade = ?) and id_evento = ? and ativo = ? ', [0,$exibir->id,true]);

        if (isset($exibir)) {
            return view('evento.caexs', ['exibir' => $exibir, 'campoCred' => $campoCred, 'salas' => $salas]);
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
    public function update(Request $request)
    {
        //
        $field = $request->all();
        $count = count($field['id_campo_caex']);

        $id = $field['id_evento'][0];

        $palestras   = isset($request['palestras']) ? implode(' / ', $request['palestras']) : 'Nenhuma selecionda';

        $pegaPalestras = Caex::select('palestras')->where('id_evento', $id)->where('id', $field['id'][0])->first();

        $pp = explode(' / ', $pegaPalestras->palestras);

        foreach ($pp as $p) {
           // echo $p . '<br>';
            $tirapalestras = Sala::select('quantidade', 'visitantes')->where('nome', $p)->where('id_evento', $id)->first();
            if (isset($tirapalestras)) {
                if ($tirapalestras->quantidade != 0) {
                    if ($tirapalestras->visitantes < $tirapalestras->quantidade) {

                        //entra se quantidade for diferente de 0 e visitantes menores que quantidade 
                        $subtrai = ($tirapalestras->visitantes - 1);
                        //soma mais um a os visitentes e faz o update 
                        DB::table('salas')->where('nome', $p)->where('id_evento', $id)
                            ->update([
                                'visitantes'    => $subtrai,
                                'updated_at'    => NOW()
                            ]);
                    }
                }
            }
        }

        $qnte = '';

        //Gravar quantidade na tabela salas
        $pal = explode(' / ', $palestras);
        $id = $field['id_evento'][0];
        foreach ($pal as $value) {
            //echo $value . '<br>';
            $qnte = Sala::select('quantidade', 'visitantes')->where('nome', $value)->where('id_evento', $id)->first();

            //echo $qnte;
            if (isset($qnte)) {
                if ($qnte->quantidade != 0) {
                    if ($qnte->visitante < $qnte->quantidade) {

                        //entra se quantidade for diferente de 0 e visitantes menores que quantidade 
                        $soma = ($qnte->visitantes + 1);
                        //echo $soma . '<br>';
                        //soma mais um a os visitentes e faz o update 
                        DB::table('salas')->where('nome', $value)->where('id_evento', $id)
                            ->update([
                                'visitantes'    => $soma,
                                'updated_at'    => NOW()
                            ]);
                    }
                }
            }
        }

        for ($i = 0; $i < $count; $i++) {
            $data['id']             = $field['id'][$i];
            $data['id_campo_caex']  = $field['id_campo_caex'][$i];
            $data['nome']           = $field['nome'][$i];
            $data['email']          = $field['email'][$i];
            $data['celular']        = $field['celular'][$i];
            $data['cpf']            = preg_replace("/[^0-9]/", "", $field['cpf'])[$i];
            $data['tipo']           = $field['tipo'][$i];
            //$data['palestras']       = isset($field['palestras'][$i]) ? implode(' / ', $field['palestras']) : '';

            //$data['valor_salvo']    = isset($field['valor_salvo'][$i]) ? str_replace(";", ",", $field['valor_salvo'])[$i] : 'null'.$i;

            if ($data['tipo'] == 'checkbox') {
                if (isset($field['checkbox'])) {
                    $data['valor_salvo']  = implode(' / ', $field['checkbox']);
                } else {
                    $data['valor_salvo'] = 'null'.$i;
                }
            } else {
                $data['valor_salvo']  = isset($field['valor_salvo'][$i]) ? $field['valor_salvo'][$i] : 'null'.$i;
            }

            DB::table('caexes')->where('id', $data['id'])
                ->update([
                    'nome'          => $data['nome'],
                    'email'         => $data['email'],
                    'celular'       => $data['celular'],
                    'cpf'           => $data['cpf'],
                    'valor_salvo'   => str_replace(";", ",", $data['valor_salvo']),
                    'palestras'     => $palestras,
                    'updated_at'    => NOW()
                ]);
        }
        return redirect()->back()->with('mensagem', 'atualizadas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($cpf)
    {
        //
        $cpf_ = base64_decode($cpf);

        $Visitatnte = Caex::where('cpf', $cpf_);

        if (isset($Visitatnte)) {
            //$evento->delete();            
            DB::table('caexes')->where('cpf', $cpf_)
            ->update([
                'ativo'          => 2,
                'updated_at'     => NOW()
            ]);

            return redirect()->back()->with('mensagem', 'apagado');
        }
        return abort(404);
    }

    // imprimir
    public function print($cpf, $id)
    {
        $itens = DB::table('caexes')
            ->select('nome', 'email', 'celular', 'cpf')
            ->selectRaw('GROUP_CONCAT(DISTINCT valor_salvo  ORDER BY id ASC SEPARATOR ";") as valor_salvo')
            ->selectRaw('GROUP_CONCAT(DISTINCT id  ORDER BY id ASC SEPARATOR ";") as id')
            ->where('cracha', true)
            ->where('cpf', [$cpf])
            ->where('id_evento', $id)
            ->orderByRaw('nome')
            ->groupBy('nome', 'email', 'celular', 'cpf')
            ->get();

        $code       = DB::table('caexes')->select('id', 'id_evento', 'id_campo_caex')->where('id_evento', $id)->where('cpf', $cpf)->first();

        $sizepage   = DB::table('eventos')->select('tamanho_impressao', 'codigo')->where('id', $id)->first();

        if (isset($itens)) {
            return view('imprimir.imprimir', ['itens' => $itens, 'sizepage' => $sizepage, 'code' => $code]);
        }
        return abort(404);
    }

    public function export(Evento $id)
    {
        return Excel::download(new UsersExportCaex($id), 'caex' . NOW() . '.xlsx');
    }

    //importar
    public function import(Request $request, Evento $id, CredImport $mensagem)
    {
        $filePath = $request->file('import')->store('public');

       
        if ((isset($filePath)) || ($filePath != '')) {
            Excel::import(new CredImport($id), $filePath);
        }
        return redirect()->back();
    }

    /*
    public function emailcaex(Request $request)
    {
        $email      = $request['email'];
        $id_evento  = $request['id_evento'];

        $pegaemail = DB::table('caexes')
            ->select('email')
            ->where('email', [$email])
            ->where('id_evento', [$id_evento])
            ->first();

        if (isset($pegaemail)) {
            if ($pegaemail->email === $email) {
                $retorno = array(
                    'mensagem' => "O Email já está cadastrado.",
                    'sucesso' => 1
                );
                return response(json_encode($retorno, 200));
            }
        } else {
            $retorno = array(
                'mensagem' => "O Email já está cadastrado.",
                'sucesso' => 0
            );
            return response(json_encode($retorno, 200));
        }
    }
*/
    public function cnpjcaex(Request $request)
    {

        //$visitantecpf = preg_replace("/[^0-9]/", "",  $request['cnpj']);

        $visitantecpf   = $request['cnpj'];
        $id_evento      = $request['id_evento'];

        $cnpj = DB::table('caexes')
            ->select('valor_salvo')
            ->where('id_evento', '=', [$id_evento])
            ->where('campo', '=', 'cnpj')
            ->where('valor_salvo', '=', [$visitantecpf])
            ->get();

        $cnpj           = preg_replace("/[^0-9]/", "", $cnpj);
        $visitantecpf   = preg_replace("/[^0-9]/", "", $visitantecpf);


        if (isset($cnpj)) {
            if ($cnpj == $visitantecpf) {
                $retorno = array(
                    'mensagem' => "O CNPJ já está cadastrado.",
                    'sucesso' => 1
                );
                return response(json_encode($retorno, 200));
                exit();
            }
        }
        if ($visitantecpf != $cnpj) {
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
            //'cnpj banco' => $cnpj,
            'mensagem' => 'CNPJ válido',
            'sucesso' => 0
        );

        return response(json_encode($retorno, 200));
        exit();
    }

    public function cpfcaex(Request $request)
    {

        //$visitantecpf = preg_replace("/[^0-9]/", "",  $request['cnpj']);

        $visitantecpf   = $request['cpf'];
        $id_evento      = $request['id_evento'];

        $cpf = DB::table('caexes')
            ->select('valor_salvo')
            ->where('id_evento', '=', [$id_evento])
            ->where('campo', '=', 'cpf')
            ->where('valor_salvo', '=', [$visitantecpf])
            ->get();

        $cpf = preg_replace("/[^0-9]/", "", $cpf);

        $visitantecpf = preg_replace("/[^0-9]/", "", $visitantecpf);

        if (isset($cpf)) {
            if ($cpf == $visitantecpf) {
                $retorno = array(
                    'mensagem' => "O CNPJ já está cadastrado.",
                    'sucesso' => 1
                );
                return response(json_encode($retorno, 200));
                exit();
            }
        }
        if ($visitantecpf != $cpf) {
            $validator = Validator::make(
                $request->all(),
                [
                    'cpf' => 'cpf'
                ]
            );
            if ($validator->fails()) {
                $retorno = array(
                    'mensagem' => 'cpf inválido',
                    'sucesso' => 2
                );

                return response(json_encode($retorno, 200));
                exit();
            }
        }

        $retorno = array(
            //'cnpj banco' => $cnpj,
            'mensagem' => 'cpf válido',
            'sucesso' => 0
        );

        return response(json_encode($retorno, 200));
        exit();
    }

    public function procurar(Request $request)
    {
        $id     = $request['id'];
        $busca   = $request->input('nome');

        $campoCaex = Campo_caex::where('id_evento', '=', $id)->get();
        $exibir = Evento::find($id);

        
        //$busca = Input::get ( 'busca' );

        // echo '<div class="procurar">';
        //     echo $id . '<br>';
        //     echo $busca . '<br>';
        //     //echo $busca . '<br>';
        // echo '</div>';
        // die();

        if($busca != ""){ 

            $itens = DB::table('caexes')
            ->select('nome', 'email', 'celular', 'cpf')
            ->selectRaw('GROUP_CONCAT(DISTINCT valor_salvo ORDER BY id ASC SEPARATOR ";") as valor_salvo')
            ->selectRaw('GROUP_CONCAT(DISTINCT id ORDER BY id ASC SEPARATOR ";") as id')
            ->where('nome','LIKE','%'.$busca."%")
            ->orwhere('cpf','LIKE','%'.$busca."%")
            ->where('id_evento', [$id])
            ->where('ativo', true)
            ->orderByRaw('nome')
            ->groupBy('nome','email','celular', 'cpf')
            ->get();

        // $itens = DB::table('caexes')
        // ->where('nome','LIKE','%'.$busca."%")->get();


        if(count($itens) > 0){
        
            echo  '<div class="alert alert-success " role="alert">';
            echo    'Os resultados para <b> '.$busca.' </b> são :';
            echo  '</div>';

            echo '<table class="table table-ordered table-bordered table-hover table-striped table-sm">';
            echo    '<tr>';
            foreach($campoCaex as $cx){
                echo    '<th>'.$cx->nome.'</th>';
            }
            echo    '<th>Ações</th>';
            echo    '</tr>';

            foreach($itens as $tabela){
    
            $cod = str_replace(';' ,'',$tabela->id);
            // $cod = base64_encode($tabela->email);
            $strExemple = $tabela->valor_salvo;
            $valor_campos = explode(';', $strExemple);


            echo '<div class="procurar">';
            echo '<tr style="font-size: 13px;">';

            foreach($valor_campos as $valor){
                echo '<td>'.$valor.'</td>';
            }
            $ROTA = '/imprimircaex/'.$tabela->email.'&'.$id.'';
            echo '<td style="text-align:center;">';
                echo '<div class="btn-group" role="group" aria-label="Basic example">';
                    echo '<button class="btn btn-sm btn-outline-primary" role="button" data-toggle="modal" data-target="#edit'.$cod.'"><i class="far fa-edit"></i> Editar</button>';
                    echo "<a  class='btn btn-sm btn-outline-primary' href=".$ROTA." target='_blank' class='btn btn-sm btn-outline-primary'><i class='fas fa-print'></i> Imprimir</a>";
                    echo "<button role='button' data-toggle='modal' data-target='#apagar".$cod."'  class='btn btn-sm btn-outline-danger'><i class='fas fa-trash-alt'></i> Apagar</button>";
                echo '</div>';
            echo '</td>';

            echo '</tr>';
            echo '</div>';
            
            }
            echo '</table>';
        }
        if(count($itens) <= 0){
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Nenhum resultado encontrado!';
        echo '</div>';
        }  
    } 
        
    }

    public function unico(Request $request)
    {
        $id             = $request['id_evento'];
        $id_campo_caex  = $request['id_campo_caex'];
        $campo          = $request['campo'];

        $verifica = Caex::select('valor_salvo')
                ->where('id_campo_caex', $id_campo_caex)
                ->where('id_evento', $id)
                ->where('valor_salvo', $campo)->first();
        
               
        if($verifica != null){

            $retorno = array(
                    'mensagem'  => 'Valor ja existe',
                    'campo'     => $verifica->valor_salvo,
                    'unico'     => '.unico'.$id_campo_caex,
                    'sucesso'   => 0
                );

            return response(json_encode($retorno, 200));

        }else{

            $retorno = array(
                    'mensagem'  => 'Sucesso',
                    'unico'     => '.unico'.$id_campo_caex,
                    'sucesso'   => 1
                );

            return response(json_encode($retorno, 200));

        }

    }

}