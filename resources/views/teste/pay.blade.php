<?php

?>
@extends('layout.app', ["current" => "empresa"])

@section('body')

@if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" id='message'>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Aviso!</h4>
        {{Session::get('error')['message']}}. <br>
        Código: {{Session::get('error')['status_code']}}.<br>
        {{Session::get('error')['details'][0]['description']}}.<br>
        {{Session::get('error')['details'][0]['description_detail']}}
        
        </div>    
@endif

@if(session('message'))
        
        <div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
        style="box-shadow: 0px 0px 20px #A4A4A4;">
        <h4><i class="icon fa fa-check"></i> Aviso!</h4>
        {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
        </div>
@endif

<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-md-10 col-sm-12">
                                <h3><i class="fas fa-pay"></i> Pagamento cartão de crédito API GetNet
                                        <b></b></h3>
                        </div>
                </div>
        </div>
        <div class="card-body">
                        <form action="{{ route('pagamento') }}" method="post">
                                <input type="hidden" name="cart_id" value="1">
                                <div class="modal-header">
                                        <h5 class="modal-title">Endereço</h5>
                                </div>
                                <div class="modal-body">                               
                                        <div class="row">

                                                <div class="form-group col-6">
                                                        <label for="cep" class="control-label">CEP:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="cep" id="cep" onblur="pesquisacep(this.value);" class="form-control" value="30130-000">
                                                        </div>
                                                </div>
                                                <div class="form-group col-6">
                                                        <label for="rua" class="control-label">Rua:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="rua" id="rua" class="form-control" value="Avenida Afonso Pena">
                                                        </div>
                                                </div>
                                                <div class="form-group col-6">
                                                        <label for="num" class="control-label">Nº:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="num" id="num" class="form-control" value="123">
                                                        </div>
                                                </div>
                                                <div class="form-group col-6">
                                                        <label for="bairro" class="control-label ">Bairro:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="bairro" id="bairro" class="form-control" value="Centro">
                                                        </div>
                                                </div>
                                                <div class="form-group col-6">
                                                        <label for="cidade" class="control-label ">Cidade:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="cidade" id="cidade" class="form-control" value="Belo Horizonte">
                                                        </div>
                                                </div>

                                                <div class="form-group col-6">
                                                        <label for="uf" class="control-label">Estado *</label>
                                                        <div class="input-group">
                                                                <select class="form-control" id="uf" name="uf" placeholder="Estado" required>
                                                                <option value="AC">Acre</option>
                                                                <option value="AL">Alagoas</option>
                                                                <option value="AP">Amapá</option>
                                                                <option value="AM">Amazonas</option>
                                                                <option value="BA">Bahia</option>
                                                                <option value="CE">Ceará</option>
                                                                <option value="DF">Distrito Federal</option>
                                                                <option value="ES">Espírito Santo</option>
                                                                <option value="GO">Goiás</option>
                                                                <option value="MA">Maranhão</option>
                                                                <option value="MT">Mato Grosso</option>
                                                                <option value="MS">Mato Grosso do Sul</option>
                                                                <option value="MG" selected>Minas Gerais</option>
                                                                <option value="PA">Pará</option>
                                                                <option value="PB">Paraíba</option>
                                                                <option value="PR">Paraná</option>
                                                                <option value="PE">Pernambuco</option>
                                                                <option value="PI">Piauí</option>
                                                                <option value="RJ">Rio de Janeiro</option>
                                                                <option value="RN">Rio Grande do Norte</option>
                                                                <option value="RS">Rio Grande do Sul</option>
                                                                <option value="RO">Rondônia</option>
                                                                <option value="RR">Roraima</option>
                                                                <option value="SC">Santa Catarina</option>
                                                                <option value="SP">São Paulo</option>
                                                                <option value="SE">Sergipe</option>
                                                                <option value="TO">Tocantins</option>

                                                                </select>
                                                        </div>
                                                </div>

                                        </div>
                                        <br>
                                        <div class="">
                                                <h5 class="modal-title"><i class="fa fa-credit-card"></i> Dados para pagamentos com cartão de crédito: </h5>
                                        </div>

                                        <div class="row">
                                        @csrf
                                                <div class="form-group col-12">
                                                        <label for="" class="control-label">Nome do titular do cartão:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="nome" id="" class="form-control" value="José da Silva">
                                                        </div>
                                                </div>

                                                <div class="form-group col-8">
                                                        <label for="" class="control-label">Número do cartão:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="numero" id="" class="form-control" required>
                                                        </div>
                                                </div>

                                                <div class="form-group col-4">
                                                        <label for="" class="control-label">Código de segurança:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="cod" id="" class="form-control" value="123">
                                                        </div>
                                                </div>

                                                <div class="form-group col-2">
                                                        <label for="" class="control-label">Mês:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="mes" id="" class="form-control" value="03">
                                                        </div>
                                                </div>

                                                <div class="form-group col-2">
                                                        <label for="" class="control-label">Ano:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="ano" id="" class="form-control" value="24">
                                                        </div>
                                                </div>

                                                <br>
                                                
                                                {{-- <h3 class="text-success">Total: </h3> --}}
                                                <div class="form-group col-4">
                                                        <label for="" class="control-label">Valor:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="amount" id="" class="form-control" required>
                                                        </div>
                                                </div>
                                                
                                        </div>    

                                        

                                </div>                                
                                <div class="modal-footer">
                                        <button type="submit" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#modal-default"><i class="fa fa-credit-card"></i> Efetuar pagamento</button>
                                </div>

                        </form>       
                      
        </div>
        <div class="card-footer">
                <a href="/empresa/" class="btn btn-sm btn-primary" role="button">Voltar</a>
        </div>
</div>
<br><br>
<h3 section="section/Cartoes-para-Teste"><a class="share-link" href="#section/Cartoes-para-Teste"></a><a name="cartoes-para-teste"></a>Cartões para Teste</h3>
<p>Para fim de teste podem ser utilizados os seguintes cartões:</p>
<table class="table table-ordered table-bordered">
        <tbody><tr>
          <th>Cartão</th>
          <th>Tipo de Teste</th>
          <th>Resultado do Teste</th>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222280001</td>
          <td>Transação Autorizada</td>
          <td><i class="fa fa-check-circle-o fa-1x" aria-hidden="true"></i> Transação Aprovada</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222270002</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Cartão Inválido</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222260003</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Cartão Vencido</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222250004</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Estabelecimento Inválido</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222240005</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Saldo Insuficiente</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222230006</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Autorização Recusada</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222220007</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Transacao Não Processada</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-mastercard fa-2x" aria-hidden="true"></i> 5155901222210008</td>
          <td>Transação Não Autorizada</td>
          <td><i class="fab fa-times-circle-o fa-1x" aria-hidden="true"></i> Excede o Limite de Retiradas</td>
        </tr>
        <tr>
          <td><i class="fab fa-cc-visa fa-2x" aria-hidden="true"></i> 4012001037141112</td>
          <td>Transação Autorizada</td>
          <td><i class="fab fa-check-circle-o fa-1x" aria-hidden="true"></i> Transação Aprovada</td>
        </tr>
      </tbody></table>

@endsection