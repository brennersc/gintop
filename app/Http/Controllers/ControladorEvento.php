<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Evento;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Auth;

class ControladorEvento extends Controller
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
        if(Auth::user()->id_empresa == 1){
            $evento = Evento::where('ativo', true)->paginate(10);
        }else{
            $evento = Evento::where('ativo', true)->where('id_empresa', Auth::user()->id_empresa )->paginate(10);
        }

        if (isset($evento)) {
            return view('evento.evento', compact('evento'));
        }
        return abort(404);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('evento.novoevento');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $ingresso = $request->input('ingresso');

        $ini = implode('-', array_reverse(explode('/', $request->input('data_inicio'))));
        $fim = implode('-', array_reverse(explode('/', $request->input('data_fim'))));

        $validator = Validator::make(
            $request->all(),
            [
                'empresa'           => 'required|max:200',
                'nome'              => 'required|max:200|min:3',
                'slug'              => 'required|unique:eventos|min:5',
                'data_inicio'       => 'required|min:8',
                'data_fim'          => 'required|min:10',
                'hora_inicio'       => 'required',
                'hora_fim'          => 'required',
                'tamanho_impressao' => 'required'
            ],
            [
                'required'  => 'O :attribute é obrigatorio!',
                'min'       => 'O :attribute não pode ter menos de :min caracteres!',
                'max'       => 'O :attribute não pode ter mais de :max caracteres!',
                'unique'    => 'O :attribute já esta sendo usado!'
            ]
        );

        if ($validator->fails()) {
            return redirect('/evento/novo')->withErrors($validator)->withInput();
        }

        $evento = new Evento();

        if ($request->file('url_imagem') != null) {
            $path = $request->file('url_imagem')->store('imagens', 'public');
            $evento->url_imagem = $path;
        }

        $evento->nome               = $request->input('nome');
        $evento->id_empresa         = $request->input('empresa');
        $evento->slug               = $request->input('slug');
        $evento->data_inicio        = $ini;
        $evento->data_fim           = $fim;
        $evento->hora_inicio        = $request->input('hora_inicio');
        $evento->hora_fim           = $request->input('hora_fim');
        $evento->tamanho_impressao  = $request->input('tamanho_impressao');
        $evento->codigo             = $request->input('codigo');
        $evento->descricao          = $request->input('descricao');
        $evento->nome_local         = $request->input('nome_local');
        $evento->endereco_local     = $request->input('endereco_local');
        $evento->sala               = $request->input('sala');
        $evento->ingresso           = $request->input('ingresso');
        $evento->mesa               = $request->input('mesa');

        $evento->save();

        $id_evento = Evento::select('id')
            ->where('slug', '=', $evento->slug)
            ->get();

        $id_evento = preg_replace("/[^0-9]/", "", $id_evento);

        DB::insert('insert into campo_creds (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Nome', 'nome', 'text', 1, 1, 1, NOW(), NOW()]);

        DB::insert('insert into campo_creds (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Email', 'email', 'email', 1, 1, 0, NOW(), NOW()]);

        DB::insert('insert into campo_creds (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Celular', 'celular', 'tel', 1, 1, 0, NOW(), NOW()]);;
        
        DB::insert('insert into campo_creds (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Cpf', 'cpf', 'cpf', 1, 1, 0, NOW(), NOW()]);

        DB::insert('insert into campo_caexes (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Nome', 'nome', 'text', 1, 1, 1, NOW(), NOW()]);

        DB::insert('insert into campo_caexes (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Email', 'email', 'email', 1, 1, 0, NOW(), NOW()]);

        DB::insert('insert into campo_caexes (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Celular', 'celular', 'tel', 1, 1, 0, NOW(), NOW()]);

        DB::insert('insert into campo_caexes (
            id_evento, nome, slug, tipo, obrigatorio, unico, cracha, created_at, updated_at
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_evento, 'Cpf', 'cpf', 'cpf', 1, 1, 0, NOW(), NOW()]);


        if($ingresso == 'sim'){
            //echo 'entrou';
            //$evento = Evento::where('id', $id_evento)->first();
            //return redirect('ingresso');
            return redirect('ingresso/'.$id_evento);

        }else{
            return redirect('evento')->with('mensagem', 'cadastrado');
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
        if(Auth::user()->id_empresa == 1){
            $exibir = Evento::find($id);
        }else{
            $exibir = Evento::where('id_empresa', Auth::user()->id_empresa )->find($id);
        }

        if (isset($exibir)) {
            return view('evento.informacoesevento', ['exibir' => $exibir]);
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
        if(Auth::user()->id_empresa == 1){
            $evento = Evento::find($id);
        }else{
            $evento = Evento::where('id_empresa', Auth::user()->id_empresa )->find($id);
        }

        if (isset($evento)) {
            return view('evento.editarevento', compact('evento'));
        }
        return abort(404);
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
        if(Auth::user()->id_empresa == 1){
            $evento = Evento::find($id);
        }else{
            $evento = Evento::where('id_empresa', Auth::user()->id_empresa )->find($id);
        }

        if (isset($evento)) {

            $ini = implode('-', array_reverse(explode('/', $request->input('data_inicio'))));
            $fim = implode('-', array_reverse(explode('/', $request->input('data_fim'))));


            if ($request->file('url_imagem') != null) {
                $img_old = $evento->url_imagem;
                Storage::disk('public')->delete($img_old);

                $path = $request->file('url_imagem')->store('imagens', 'public');
                $evento->url_imagem = $path;
            }

            $evento->nome               = $request->input('nome');
            $evento->id_empresa         = $request->input('empresa');
            $evento->slug               = $request->input('slug');
            $evento->data_inicio        = $ini;
            $evento->data_fim           = $fim;
            $evento->hora_inicio        = $request->input('hora_inicio');
            $evento->hora_fim           = $request->input('hora_fim');
            $evento->tamanho_impressao  = $request->input('tamanho_impressao');
            $evento->codigo             = $request->input('codigo');
            $evento->descricao          = $request->input('descricao');
            $evento->nome_local         = $request->input('nome_local');
            $evento->endereco_local     = $request->input('endereco_local');
            $evento->sala               = $request->input('sala');
            $evento->ingresso           = $request->input('ingresso');
            $evento->mesa               = $request->input('mesa');

            $evento->save();

            return redirect('evento')->with('mensagem', 'atualizado');
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
        if(Auth::user()->id_empresa == 1){
            $evento = Evento::find($id);
        }else{
            $evento = Evento::where('id_empresa', Auth::user()->id_empresa )->find($id);
        }
        //$evento = Evento::find($id);
        
        if (isset($evento)) {
            //$evento->delete();
            DB::table('eventos')->where('id', $id)->update([
                'ativo'          => false,
                'updated_at'     => NOW()
            ]);
            return redirect('evento')->with('mensagem', 'apagado');
        }
        return abort(404);
    }

    public function img(Request $request)
    {
        //
        $id         = $request['id'];
        $pegaimagem = Evento::select('url_imagem')->where('id', $id)->first();
        $pegaimagem = $pegaimagem->url_imagem;

        Storage::disk('public')->delete($pegaimagem);

        $atualizar = Evento::find($id);
        $atualizar->url_imagem = null;
        $atualizar->save();

        $retorno = array(
            'mensagem' => "Apagou!",
            'sucesso' => 0
        );

        return response(json_encode($retorno, 200));
    }

    public function procurar(Request $request)
    {
        $busca   = $request->input('nome');

        if($busca != ""){ 

            $evento = Evento::where('nome','LIKE','%'.$busca.'%')->where('ativo', true)->get(); 
            
        if(count($evento) > 0){

            echo  '<div class="alert alert-success " role="alert">';
            echo    'Os resultados para <b> '.$busca.' </b> são :';
            echo  '</div>';

            echo '<table class="table table-ordered table-bordered" style="text-align: center;">';
            echo    '<thead class=table-active>';
            echo    '<tr>';
            echo    '<th>Nome</th>';
            echo    '<th>Ações</th>';
            echo    '<th>Campos Credenciamento / Caex</th>';
            echo    '<th>Salas</th>';
            echo    '<th>Visitantes Credenciamento / Caex</th>';
            echo    '</tr>';
            echo    '</thead>';

            foreach($evento as $tabela){
               
            echo '<div class="procurar">';
            echo '<tr>';

            // foreach($valor_campos as $valor){
            //     echo '<td>'.$valor.'</td>';
            // }

            echo    '<td>'.$tabela->nome.'</td>';

            $ROTAEDITAR = '/editar/'.$tabela->id.'';
            $ROTAAPAGAR = '/evento/apagar/'.$tabela->id.'';
            $ROTAINFO = '/info/'.$tabela->id.'';
            $ROTAVISITANTES = '/visitantes/'.$tabela->id.'';
            $ROTACAEX = '/caexs/'.$tabela->id.'';


            echo '<td>';
            echo       '<div class="btn-group" role="group" aria-label="Basic example">';
            echo                  "<a href=".$ROTAEDITAR." class='btn btn-sm btn-primary'><i class='far fa-edit'></i> Editar</a>";
            echo                  "<a href=".$ROTAAPAGAR." class='btn btn-sm btn-danger'><i class='far fa-trash-alt'></i> Apagar</a>";
            echo                  "<a href=".$ROTAINFO." class='btn btn-sm btn-success'><i class='fas fa-info-circle'></i> Informações</a>";
            echo       '</div>';
            echo '</td>'; 

            echo '<td>';
            echo '<div class="btn-group" role="group" aria-label="Basic example">';
                 echo     '<button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#dlgEmpresa'.$tabela->id.'"><i class="fas fa-id-badge"></i> Credenciamento </button>';
                 echo     '<button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#dlgCAEX'.$tabela->id.'caex"><i class="fas fa-address-card"></i> Caex </button>';
            echo '</div>';
            echo '</td>';

            echo '<td>';
                if($tabela->sala == 'sim'){
                echo ' <button href="/editar/{{$even->id}}" class="btn btn-sm  btn-outline-primary botaosala"
                                data-toggle="modal" data-target="#SALA'.$tabela->id.'"><i class="fas fa-door-open"></i>
                                Salas</button>';
                }else{
                echo ' <button class="btn btn-sm  btn-outline-primary disabled"><i class="fas fa-door-closed"
                                    aria-disabled="true" disabled></i> Salas</button>';
                }
            echo '</td>';
           

            echo '<td>';
            echo       '<div class="btn-group" role="group" aria-label="Basic example">';
            echo                  "<a href=".$ROTAVISITANTES." class='btn btn-sm btn-outline-success'><i class='fas fa-list'></i> Visitantes</a>";
            echo                  "<a href=".$ROTACAEX." class='btn btn-sm btn-outline-success'><i class='fas fa-list'></i> CAEX</a>";
            echo       '</div>';
            echo '</td>';
               

            echo '</tr>';
            echo '</div>';
            
        }

            echo '</table>';

            echo '<hr>';
        
        }
        if(count($evento) == 0){

        echo '<div class="alert alert-danger" role="alert">';
        echo 'Nenhum resultado encontrado!';
        echo '</div>';
        }  
        }
        
    }

}
