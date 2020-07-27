<?php

namespace App\Http\Controllers;

use App\Pagamento;
use App\Evento;
use App\Ingresso;
use App\Visitante_login;
use App\VendaIngresso;
use App\Mesa;
use App\VendaMesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Getnet\GetnetReturn;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Event\HasEmitterInterface;
use GuzzleHttp\Exception\RequestException;

class ControladorPagamento extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        //
        return view('teste.teste');
    }

    public function pay($cpf, $id_evento, $id_venda_ingresso, $id_ingresso)
    {

        $ingresso   = Ingresso::find($id_ingresso);
        $evento     = Evento::find($id_evento);
        $venda      = VendaIngresso::find($id_venda_ingresso);
        //$Visitante  = Visitante_login::find($cpf);


        if (isset($ingresso)) {
            return view('loginVisitantes.pagamento', ['evento' => $evento, 'ingresso' => $ingresso, 'venda' => $venda]);
        }

    }

    public function payMesa($cpf, $id_evento, $id_mesa, $id_venda_mesa)
    {
        // echo $cpf;
        // echo $id_evento;
        // echo $id_mesa;
        // echo $id_venda_mesa; 

        
        $mesa       = Mesa::find($id_mesa);
        $evento     = Evento::find($id_evento);
        $venda      = VendaMesa::find($id_venda_mesa);

        if (isset($mesa)) {
            return view('loginVisitantes.pagamento', ['evento' => $evento, 'mesa' => $mesa, 'venda' => $venda]);
        }
        
    }



    public function boleto()
    {
        //
        return view('teste.boleto');
    }

    //Função para gerar o token da conta
    //Foi usado o curl devido a versão do guzzlehttp está desatualizada

    public function TokenGenerate(){
        try {
            //code...
            $chave = 'Basic '.base64_encode(env('GETNET_CLIENT_ID').':'.env('GETNET_CLIENT_SECRET'));
            $postFields ='scope=oob&grant_type=client_credentials';
            $header = array(
                'authorization: '.$chave,
                'content-type: application/x-www-form-urlencoded',
            );
            $ch = curl_init(env('GETNET_URL_API').'/auth/oauth/v2/token');
            
            curl_setopt($ch, CURLOPT_POST, 1);
    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    
            $request = json_decode(curl_exec($ch));        
            curl_close($ch);
            
            return $request;
        } catch (\Exception $e) {
            return view('errors.error', compact('e'));
        }
    }

    //Função para Tokenizar o cartão

    public function CardTokenizer($card){
        try {
            
            $client = new \GuzzleHttp\Client();
            $response = $client->post( env('GETNET_URL_API').'/v1/tokens/card',
                [
                    'headers' => [
                        'authorization' => 'Bearer '.$this->TokenGenerate()->access_token,
                        'content-type'  =>'application/json; charset=utf-8',
                    ],
                    'json' => [
                        'card_number' => $card
                    ]
                ]);
            $request        = json_decode($response->getBody()->getContents());
            $request->code  = $response->getStatusCode();
            return $request;            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Função para pagamento em sí, todos os campos do request são obrigatórios para o pagamento
    public function CredPayment(Request $request)
        {
            // $request->validate([
            //     'rua'       => 'required|string',
            //     'num'       => 'numeric|string',
            //     'bairro'    => 'required|string',
            //     'cidade'    => 'required|string',
            //     'estado'    => 'required|string',
            //     //'uf'         => 'required|string',

            //     'nome'      => 'required|string',
            //     'numero'    => 'required|numeric',
            //     'cod'       => 'required|numeric',
            //     'mes'       => 'required|numeric',
            //     'ano'       => 'required|numeric',
            //     'cart_id'   => 'required|numeric'
            // ],[
            //     'string'     => 'Somente texto',
            //     'numeric'    => 'Somente números.',
            //     'required'   => 'Campo obrigatório'
            // ]);

            try {            
                    $identificador = $request->identificador;
                    $amount = str_replace('.','',number_format($request->amount, 2, '.', ''));
                    $numero = str_replace(' ','',$request->numero);
                    $cpf = preg_replace("/[^0-9]/", "", $request->cpf);

                    $client = new \GuzzleHttp\Client();
                    $response = $client->post(env('GETNET_URL_API').'/v1/payments/credit',
                        [
                            'headers' => [
                                'Accept'=> 'application/json, text/plain, */*',
                                'authorization' => 'Bearer '.$this->TokenGenerate()->access_token,
                                'content-type'=>'application/json; charset=utf-8',
                            ],
                            'json' => [
                                'seller_id' => env('GETNET_SELLER_ID'),
                                //'currency' => '',
                                'amount' => $amount,
                                'order' => [
                                    'order_id' => 'ID - ' . $identificador .' - '. $request->id_venda,
                                    //'sales_tax' => '0',
                                    //'product_type' => 'service',
                                    ],
                                'customer' => [
                                    'customer_id'   => $cpf,//Identificação do usuario | CPF
                                    'first_name'    => $request->nome,
                                    'last_name'     => $request->nome,
                                    //'name' => 'Raphael de Oliveira Lima',
                                    'billing_address'   =>[
                                        'street'    => $request->rua,
                                        'number'    => '00',
                                        //'complement'=> 'Sala 1',
                                        'district'  => $request->bairro,
                                        'city'      => $request->cidade,
                                        'state'     => $request->estado,
                                        'country'   => 'Brasil',
                                        'postal_code'=> str_replace('-','',$request->cep)                                            
                                        ],
                                    ],
                                'device' => [
                                    'ip_address'=> request()->ip(),
                                ],
                                /*'shippings'=> [                            
                                    'address'=> [],
                                ],*/
                                'credit' => [
                                    'delayed'=> false,
                                    'save_card_data'=> false,
                                    'transaction_type'=> 'FULL',
                                    'number_installments'=> 1,
                                    //'authenticated'=> false,
                                    //'pre_authorization'=> false,
                                    'soft_deor'=> 'Atividades Extraclasse ID ',
                                    //'dynamic_mcc'=> 1799,
                                    'card'=>[
                                        'number_token'      => $this->CardTokenizer($numero)->number_token,
                                        'cardholder_name'   => $request->nome,
                                        'expiration_month'  => $request->mes,
                                        'expiration_year'   => $request->ano,
                                        'security_code'     => $request->cod,
                                        //'brand'=>'mastercard'
                                    ],
                                ],                        
                            ]
                        ]);
                    
                        $retorno = json_decode($response->getBody()->getContents());
                        $retorno->code = $response->getStatusCode();
                        
                        if($identificador == 'ingresso'){
                            DB::table('venda_ingressos')->where('id', $request->id_venda)->update([
                                'pago'           => 'sim',
                                'updated_at'     => NOW()
                            ]);
                        }else{
                            DB::table('venda_mesas')->where('id', $request->id_venda)->update([
                                'pago'           => 'sim',
                                'updated_at'     => NOW()
                            ]);
                        }
                        return redirect('visitante')->with('message','Pagamento efetuado com sucesso');
    
                            
            } catch (RequestException  $e) {
                $error = json_decode($e->getResponse()->getBody(),true);
                return redirect()->back()->with('error',$error);

            }
        }

//Função para pagamento em sí, todos os campos do request são obrigatórios para o pagamento
public function BoletoPayment(Request $request)
        {
            // $request->validate([
            //     'rua' => 'required|string',
            //     'num' => 'numeric|string',
            //     'bairro' => 'required|string',
            //     'cidade' => 'required|string',
            //     //'estado' => 'required|string',
            //     'uf' => 'required|string',


            //     'nome' => 'required|string',
            //     'numero' => 'required|numeric',
            //     'cod' => 'required|numeric',
            //     'mes' => 'required|numeric',
            //     'ano' => 'required|numeric',
            //     'cart_id' => 'required|numeric'
            // ],[
            //     'string' => 'Somente texto',
            //     'numeric' => 'Somente números.',
            //     'required' => 'Campo obrigatório'
            // ]);

            try {  
                
                    $amount = str_replace('.','',number_format($request->amount, 2, '.', ''));
        
                    $client = new \GuzzleHttp\Client();
                    $response = $client->post(env('GETNET_URL_API').'/v1/payments/boleto',
                        [
                            'headers' => [
                                'Accept'=> 'application/json, text/plain, */*',
                                'content-type'=>'application/json; charset=utf-8',
                                'authorization' => 'Bearer '.$this->TokenGenerate()->access_token,                                
                            ],
                            'json' => [
                                'seller_id' =>env('GETNET_SELLER_ID'),
                                //'currency' => '',
                                'amount' => $amount,
                                'order' => [
                                    'order_id' => 'TESTE TESTE TESTE TESTE',
                                    //'sales_tax' => '0',
                                    //'product_type' => 'service',
                                    ],
                                'boleto' => [
                                    "our_number"        => "000001946598",
                                    "document_number"   => "170500000019763",
                                    "expiration_date"   => "16/11/2020",
                                    "instructions"      => "Não receber após o vencimento",
                                    "provider"          => "santander"
                                ],
                                'customer' => [  
                                    "name"              =>  $request->name,                                  
                                    "document_type"     =>  "CPF",
                                    "document_number"   =>  $request->cpf,//Identificação do usuario | CPF
                                    //'name' => 'Raphael de Oliveira Lima',
                                    'billing_address'   =>[
                                        'street'        => $request->rua,
                                        'number'        => $request->num,
                                        //'complement'=> 'Sala 1',
                                        'district'      => $request->bairro,
                                        'city'          => $request->cidade,
                                        'state'         => $request->uf,
                                        'country'       => 'Brasil',
                                        'postal_code'   => str_replace('-','',$request->cep)
                                            
                                        ],
                                    ],                        
                            ]
                        ]);
                    
                        $retorno = json_decode($response->getBody()->getContents());
                        $retorno->code = $response->getStatusCode();

                        return redirect()->back()->with('sucesso',$retorno->payment_id);
   
                            
            } catch (RequestException  $e) {
                $error = json_decode($e->getResponse()->getBody(),true);
                return redirect()->back()->with('error',$error);

            }
        }


    public function cancelamento(Request $request)
    {
        $this->authorize('tesouraria',Auth::user());
        $request->validate([
            'id'=>'required|numeric',
            'amount' => 'nullable|string',
            'motivo' => 'required|string'            
        ]);
        try {
            $amount = intval(str_replace('.','',str_replace(',','',$request->amount)));        
            
            $getnet = new GetnetController;            
            $client = new \GuzzleHttp\Client();
            $response = $client->post(env('GETNET_URL_API').'/v1/payments/cancel/request',                
                [
                    'headers' => [
                        'seller_id'=> env('GETNET_SELLER_ID'),
                        'authorization' => 'Bearer '.$getnet->TokenGenerate()->access_token,
                        'content-type'=>'application/json; charset=utf-8',
                    ],
                    'json' => [
                        'payment_id' =>$inscricao->getnet->payment_id,                            
                        'cancel_amount' => $amount,
                        'cancel_custom_key' => 'Cancel'.date('YmdHi').'00000'.$inscricao->aluno_id,                            
                        ],
                ]);                   
            $retorno = json_decode($response->getBody()->getContents());
            $retorno->code = $response->getStatusCode();    
            
            return redirect()->back()->with('message','Cancelamento efetuado com sucesso');
            
        } catch (RequestException  $e) {            
            $error = json_decode($e->getResponse()->getBody(),true);            
            return redirect()->back()->with('error',$error);
        }
        
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function show(Pagamento $pagamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Pagamento $pagamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagamento $pagamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamento $pagamento)
    {
        //
    }
}
