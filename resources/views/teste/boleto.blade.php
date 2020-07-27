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

@if (Session::has('sucesso'))
        <div id='alert' class="alert alert-success alert-dismissible fade show" role="alert"
        style="box-shadow: 0px 0px 20px #A4A4A4;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Boletos gerados com sucesso!</h4>
        <p>Link download PDF:</p>
        <a href="{{ env('GETNET_URL_API') }}/v1/payments/boleto/{{ session('sucesso') }}/pdf">PDF</a>
        <p>Link download HTML:</p>
        <a href="{{ env('GETNET_URL_API') }}/v1/payments/boleto/{{ session('sucesso') }}/html">HTML</a> 
        <br>        
        </div>    
@endif

<div class="card border">
        <div class="card-header">
                <div class="row">
                        <div class="col-md-10 col-sm-12">
                                <h3><i class="fas fa-pay"></i> Pagamento Boleto API GetNet
                                        <b></b></h3>
                        </div>
                </div>
        </div>
        <div class="card-body">
                        <form id="boleto" action="{{ route('boletoPayment') }}" method="post">
                                <input type="hidden" name="cart_id" value="1">
                                <div class="modal-header">
                                        <h5 class="modal-title">Dados:</h5>
                                </div>
                                <div class="modal-body">                               
                                        <div class="row">
                                                <div class="form-group col-6">
                                                        <label for="name" class="control-label">Nome Completo:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="name" id="name" class="form-control" value="Jose da silva">
                                                        </div>
                                                </div>
                                                <div class="form-group col-6">
                                                        <label for="cpf" class="control-label">CPF:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="cpf" id="cpf" class="form-control" value="11859005608">
                                                        </div>
                                                </div> 
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
                                                <div class="form-group col-4">
                                                        <label for="" class="control-label">Valor:</label>
                                                        <div class="input-group">
                                                                <input type="text" name="amount" id="amount" class="form-control" required>
                                                        </div>
                                                </div>
                                        </div>
                                        @csrf                                            
                                </div>                                                                                
                                <div class="modal-footer">
                                        <button type="submit" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#modal-default"><i class="fas fa-file-alt"></i> Gerar Boleto</button>
                                </div>

                        </form>       
                      
        </div>
        <div class="card-footer">
                <a href="/empresa/" class="btn btn-sm btn-primary" role="button">Voltar</a>
        </div>
</div>
<br><br>
@endsection


@section('javascript')
<script type="text/javascript">
// GRAVAR CAMPO E VERIFICAR CREDENCIAMENTO
//     $("#boleto").on("submit", function() {
//         //alert('teste');
//         $.ajax({
//             type:       'GET',
//             url:        'teste/boletopay',
//             data: {
//                 _token: $(this).attr('_token').val(),
//                 name:   $('#name').val(),
//                 cpf:    $('#cpf').val(),
//                 cep:    $('#cep').val(),
//                 rua:    $('#rua').val(),
//                 num:    $('#num').val(),
//                 bairro: $('#bairro').val(),
//                 cidade: $('#cidade').val(),
//                 uf:     $('#uf').val(),
//                 amount: $('#amount').val()
//             },
//             dataType: 'JSON',
//             success: function(data) {
//                 if (data.sucesso == 0) {
//                         console.log(data.mensagem);
//                         alert('sucesso');

//                 }
//             }
//         });
//         return false;
//     });
</script>
@endsection