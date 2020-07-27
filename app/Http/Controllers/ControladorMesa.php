<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Evento;
use App\Mesa;

class ControladorMesa extends Controller
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
        $total  = preg_replace("/[^0-9]/", "", $request->input('valormesa'));
        $mesa   = new Mesa();
        
        $mesa->id_evento   = $request->input('id_evento');
        $mesa->qntd        = $request->input('qntdmesas');
        $mesa->assentos    = $request->input('qntdassentos');
        $mesa->valor       = $total;
        $mesa->descricao   = $request->input('descricaomesa');

        if ($request->file('url_imagem') != null) {
            $path = $request->file('url_imagem')->store('imagens/mesas', 'public');
            $mesa->url_imagem = $path;
        }

        $mesa->save();

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
        echo $id_evento;

        //$ingressos  = Ingresso::where('id_evento', $id_evento)->get();
        $evento     = Evento::find($id_evento);

        if (isset($evento)) {
            return view('mesa.mesa', ['evento' => $evento, 'id' => $id_evento, 'ingressos' => $ingressos]);
        }
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
        //

        $mesa = Mesa::find($id);

        $total  = preg_replace("/[^0-9]/", "", $request->input('valormesa'));
        
        if ($request->file('url_imagem') != null) {
            $img_old = $mesa->url_imagem;
            Storage::disk('public')->delete($img_old);

            $path = $request->file('url_imagem')->store('imagens/mesas', 'public');
            $mesa->url_imagem = $path;
        }

        $mesa->id_evento   = $request->input('id_evento');
        $mesa->qntd        = $request->input('qntdmesas');
        $mesa->assentos    = $request->input('qntdassentos');
        $mesa->valor       = $total;
        $mesa->descricao   = $request->input('descricaomesa');

        if ($request->file('url_imagem') != null) {
            $path = $request->file('url_imagem')->store('imagens/mesas', 'public');
            $mesa->url_imagem = $path;
        }
        
        $mesa->save();

        return back();
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
        $mesa = Mesa::find($id);
        
        $img_old = $mesa->url_imagem;
        Storage::disk('public')->delete($img_old);

        $mesa->delete();

        return redirect()->back();
    }

    public function remover(Request $request)
    {
        //
        $id     = $request->input('id');
        $status = $request['status'];
        
        if($status == 0){
            Mesa::where('id', $id)->update([
                'ativo'          => true,
                'updated_at'     => NOW()
            ]);
            $retorno = array(
                'mensagem'      => 'REMOVIDO',
                'id'            => $id,
                'sucesso'       => 0
            );  
        }else{
            Mesa::where('id', $id)->update([
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
