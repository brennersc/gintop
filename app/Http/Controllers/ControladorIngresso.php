<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use App\Ingresso;
use App\Mesa;

class ControladorIngresso extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $ini                = implode('-', array_reverse(explode('/', $request->input('data_inicio'))));
        $fim                = implode('-', array_reverse(explode('/', $request->input('data_fim'))));
        $preco              = preg_replace("/[^0-9]/", "", $request->input('preco'));
        $total              = preg_replace("/[^0-9]/", "", $request->input('total'));
        $preco_meia_entrada = preg_replace("/[^0-9]/", "", $request->input('preco_meia_entrada'));
        $total_meia_entrada = preg_replace("/[^0-9]/", "", $request->input('total_meia_entrada'));

        $ingresso = new Ingresso();

        $ingresso->id_evento            = $request->input('id_evento');
        $ingresso->id_ingresso          = $request->input('lote_anterior');
        $ingresso->nome                 = $request->input('nome');
        $ingresso->qntd                 = $request->input('qntd');
        $ingresso->preco                = $preco;        
        $ingresso->total                = $total;
        $ingresso->meia                 = $request->input('meia');
        $ingresso->meia_entrada         = $request->input('meia_entrada');
        $ingresso->qntd_meia_entrada    = $request->input('qntd_meia_entrada');
        $ingresso->preco_meia_entrada   = $preco_meia_entrada;
        $ingresso->total_meia_entrada   = $total_meia_entrada;
        $ingresso->periodo              = $request->input('periodo');
        $ingresso->data_inicio          = $ini;
        $ingresso->hora_inicio          = $request->input('hora_inicio');
        $ingresso->data_fim             = $fim;
        $ingresso->hora_fim             = $request->input('hora_fim');
        $ingresso->dispo                = $request->input('dispo');
        $ingresso->convidados           = $request->input('convidados');
        $ingresso->qntd_min_compra      = $request->input('qntd_min_compra');
        $ingresso->qntd_max_compra      = $request->input('qntd_max_compra');
        $ingresso->descricao            = $request->input('descricao');

        $ingresso->save();

        if($request->input('meia') == 1){
                $meia = new Ingresso();
                $meia->id_evento            = $request->input('id_evento');
                $meia->id_ingresso          = $request->input('lote_anterior');
                $meia->nome                 = $request->input('meia_entrada');
                $meia->qntd                 = $request->input('qntd_meia_entrada');
                $meia->preco                = $preco_meia_entrada;       
                $meia->total                = $total_meia_entrada;
                $meia->periodo              = $request->input('periodo');
                $meia->data_inicio          = $ini;
                $meia->hora_inicio          = $request->input('hora_inicio');
                $meia->data_fim             = $fim;
                $meia->hora_fim             = $request->input('hora_fim');
                $meia->dispo                = $request->input('dispo');
                $meia->convidados           = $request->input('convidados');
                $meia->qntd_min_compra      = $request->input('qntd_min_compra');
                $meia->qntd_max_compra      = $request->input('qntd_max_compra');
                $meia->descricao            = $request->input('descricao');
                $meia->save();
        }

        //return json_encode($ingresso);       

        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_evento)
    {
        //
        $ingressos  = Ingresso::where('id_evento', $id_evento)->get();
        $evento     = Evento::find($id_evento);
        $mesas      = Mesa::where('id_evento', $id_evento)->first();

        if (isset($evento)) {
            return view('ingresso.novoingresso', ['evento' => $evento, 'id' => $id_evento, 'ingressos' => $ingressos, 'mesas' => $mesas]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $id_evento)
    {
        //
        $evento     = Evento::find($id_evento);
        $ingressos  = Ingresso::where('id_evento', $id_evento)->get();
        $edit       = Ingresso::find($id);
     
        if (isset($evento)) {
            return view('ingresso.editar', ['evento' => $evento, 'id' => $id_evento, 'ingressos' => $ingressos, 'edit' => $edit]);
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
        // $id_evento  = $request->input('id_evento');

        // echo ' id ingresso ' . $id;
        // echo ' id evento ' . $id_evento;

        // die();

        $id_evento                  = $request->input('id_evento');

        $ini                        = implode('-', array_reverse(explode('/', $request->input('data_inicio'))));
        $fim                        = implode('-', array_reverse(explode('/', $request->input('data_fim'))));
        $preco                      = preg_replace("/[^0-9]/", "", $request->input('preco'));
        $total                      = preg_replace("/[^0-9]/", "", $request->input('total'));
        $preco_meia_entrada         = preg_replace("/[^0-9]/", "", $request->input('preco_meia_entrada'));
        $total_meia_entrada         = preg_replace("/[^0-9]/", "", $request->input('total_meia_entrada'));

        $edit                       = Ingresso::find($id);

        $edit->id_evento            = $request->input('id_evento');
        $edit->id_ingresso          = $request->input('lote_anterior');
        $edit->nome                 = $request->input('nome');
        $edit->qntd                 = $request->input('qntd');
        $edit->preco                = $preco ;        
        $edit->total                = $total;
        $edit->meia                 = $request->input('meia');
        $edit->meia_entrada         = $request->input('meia_entrada');
        $edit->qntd_meia_entrada    = $request->input('qntd_meia_entrada');
        $edit->preco_meia_entrada   = $preco_meia_entrada;
        $edit->total_meia_entrada   = $total_meia_entrada;
        $edit->periodo              = $request->input('periodo');
        $edit->data_inicio          = $ini;
        $edit->hora_inicio          = $request->input('hora_inicio');
        $edit->data_fim             = $fim;
        $edit->hora_fim             = $request->input('hora_fim');
        $edit->dispo                = $request->input('dispo');
        $edit->convidados           = $request->input('convidados');
        $edit->qntd_min_compra      = $request->input('qntd_min_compra');
        $edit->qntd_max_compra      = $request->input('qntd_max_compra');
        $edit->descricao            = $request->input('descricao');
        $edit->save();
        

        $edit       = Ingresso::find($id);
        $evento     = Evento::find($id_evento);
        $ingressos  = Ingresso::where('id_evento', $id_evento)->get();

        return view('ingresso.editar', ['evento' => $evento, 'id' => $id_evento, 'ingressos' => $ingressos, 'edit' => $edit]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id_evento)
    {
        //
        $ingresso = Ingresso::find($id);
        $ingresso->delete();

        return redirect()->back();
        
    }

    public function remover(Request $request)
    {
        //
        $id     = $request->input('id');
        $status = $request['status'];
        
        echo $id  . ' ' . $status;


        if($status == 0){
            Ingresso::where('id', $id)->update([
                'ativo'          => true,
                'updated_at'     => NOW()
            ]);
            $retorno = array(
                'mensagem'      => 'REMOVIDO',
                'id'            => $id,
                'sucesso'       => 0
            );  
        }else{
            Ingresso::where('id', $id)->update([
                'ativo'          => false,
                'updated_at'     => NOW()
            ]); 
            $retorno = array(
                'mensagem'      => 'ADICIONADO',
                'id'            => $id,
                'sucesso'       => 1
            );
        }

        return response(json_encode($retorno, 200));
        exit();
    }
}
