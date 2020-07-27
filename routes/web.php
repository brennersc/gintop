<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home',     function () { return view('index'); });

Route::get('/register', function () { return view('auth.login'); });

Route::get('/',         function () { return view('auth.login'); });


Route::get('/emailteste',     function () { return view('email.empresa'); });

// ------------- login visitentes ---------------

Route::prefix('visitante')->group(function(){
    Route::get('/login',                    'Auth\VisitanteController@index')->name('visitante.login');                                     //login visitantes
    Route::post('/login',                   'Auth\VisitanteController@login')->name('visitante.login.submit');                              //botão entrar visitantes
    Route::get('/',                         'ControladorVisitantes_login@index')->name('visitante')->middleware('auth:visitantes_login');   //home
    Route::post('/alterar/{id}',            'ControladorVisitantes_login@update')->name('alterar')->middleware('auth:visitantes_login');    //alterar login visitantes
    Route::get('/apagar/{cpf}&{id_evento}', 'ControladorVisitantes_login@destroy')->name('apagar')->middleware('auth:visitantes_login');    //alterar login visitantes
    Route::post('/senha',                   'ControladorVisitantes_login@senha')->middleware('auth:visitantes_login');                      //alterar senha visitantes
    Route::post('/horas',                   'ControladorVisitantes_login@horas')->middleware('auth:visitantes_login');   
    Route::post('/remember',                 'ControladorVisitantes_login@remember')->name('remember');                            //horas palestras
    Route::get('/recuperar',                 function (){ 
        return view('auth.passwords.email_visitante');
    })->name('recuperar');         //esqueceu senha

});
    Route::get('/editarvisitante/{id_evento}', 'ControladorVisitantes_login@edit')->name('editar');    //editar visitantes
 

//  --------------- empresa ---------------

//Route::get('/empresa',  'ControladorEmpresa@indexView'); 
Route::get('/empresa',              'ControladorEmpresa@indexView')->middleware('auth');    // cadastro de empresa / salva/ edita/ apaga/ mostra
Route::get('/infoempresa/{id}',     'ControladorEmpresa@info')->middleware('auth');         // informações empresa
Route::post('/cnpj',                'ControladorEmpresa@cnpj')->middleware('auth');         // verificar cnpj
Route::get('/exportarempresa/{id}', 'ControladorEmpresa@export')->name('excelempresa');     // exportar empresa
Route::get('/procurarempresa',      'ControladorEmpresa@procurar');                         // buscar empresa
Route::post('/emailempresa',        'ControladorEmpresa@email') ;                           // verificar email empresa

//  --------------- usuario ---------------

Route::get('/usuario',          'ControladorUsuario@indexView')->middleware('auth');    // cadastro de usuario / salva/ edita/ apaga/ mostra
Route::post('/email',           'ControladorUsuario@email') ;                           // verificar email
Route::post('/senha',           'ControladorUsuario@senha');                            // trocar senha
Route::get('/procurarusuario',  'ControladorUsuario@procurar');                         // buscar empresa

//  --------------- evento ---------------

Route::get('/evento',               'ControladorEvento@index');     // todos eventos
Route::get('/evento/novo',          'ControladorEvento@create');    // novo evento
Route::post('/salvarevento',        'ControladorEvento@store');     // salvar evento
Route::get('/evento/apagar/{id}',   'ControladorEvento@destroy');   //apagar evento
Route::get('/editar/{id}',          'ControladorEvento@edit');      //editar evento
Route::post('/evento/{id}',         'ControladorEvento@update');    // mudar evento
Route::get('/info/{id}',            'ControladorEvento@show');      // mostrar todas infos
Route::get('/removerimg',           'ControladorEvento@img');       // remover imagem
Route::get('/procurarevento',       'ControladorEvento@procurar');  // buscar empresa

//  --------------- credenciamento ---------------

Route::get('/Campo_cred',               'ControladorCampo_cred@store');                         //guardar campos formulario
Route::get('/credenciamento/{slug}',    'ControladorCampo_cred@show');                          // mostrar campos formularios
Route::post('/salvarcred/{slug}',       'ControladorCredenciamento@store');                     // salvar dados do visitante
Route::post('/cadastrarcred/{id}',      'ControladorCredenciamento@storeCadastro');             // salvar dados do visitante
Route::get('/visitantes/{id}',          'ControladorCredenciamento@show')->middleware('auth');  // mostrar visitantes cadastrados
Route::get('/editarvisitantes',         'ControladorCredenciamento@update');                    // Editar dados do visitante
Route::get('/cred',                     'ControladorCampo_cred@cred');                          // verificar nome campo CRED
Route::get('/apagarcred',               'ControladorCampo_cred@destroy');                       // remover campo do formulario
Route::post('/horas',                   'ControladorCredenciamento@horas');                     // Verificar horas salas
Route::post('/info/editarcred/{id}',    'ControladorCampo_cred@update');                        // Editar campo cred
Route::get('/unicocred',                'ControladorCredenciamento@unico');                        // Editar campo cred

//  --------------- caex ---------------

Route::get('/Campo_caex',               'ControladorCampo_caex@store');     //guardar campos formulario
Route::get('/caex/{slug}',              'ControladorCampo_caex@show');      // mostrar campos formularios
Route::post('/salvarcaex/{slug}',       'ControladorCaex@store');           // salvar caex do visitante
Route::post('/cadastrarcaex/{id}',      'ControladorCaex@storeCadastro');   // salvar dados do visitante
Route::get('/caexs/{id}',               'ControladorCaex@show');            // mostrar caex cadastrados
Route::get('/editarcaex',               'ControladorCaex@update');          // Editar dados do visitante
Route::get('/caex',                     'ControladorCampo_caex@caex');      // verificar nome CAEX
Route::get('/apagarcaex',               'ControladorCampo_caex@destroy');   // remover campo do formulario
Route::post('/info/editarcaex/{id}',    'ControladorCampo_caex@update');    // Editar campo cred
Route::get('/unicocaex',                'ControladorCaex@unico');           // Editar campo cred

//  --------------- visitantes CRED ---------------

Route::get('/exportarCRED/{id}',        'ControladorCredenciamento@export')->name('excelcred');     //exportar visitantes credenciamento
Route::post('/importar/{id}',           'ControladorCredenciamento@import')->name('importcred');    //importar visitantes credenciamento
Route::get('/imprimir/{cpf}&{id}',      'ControladorCredenciamento@print')->name('imprimir');       //imprimir visitantes credenciamento
Route::get('/apagarvisitante/{cpf}',    'ControladorCredenciamento@destroy');                       // ocultar visitantes
Route::get('/emailcred',                'ControladorCredenciamento@emailcred');                     // verificar email CRED
Route::get('/cnpjcred',                 'ControladorCredenciamento@cnpjcred');                      // verificar cnpj CRED
Route::get('/cpfcred',                  'ControladorCredenciamento@cpfcred');                       // verificar CPF CRED
Route::get('/procurarcred',             'ControladorCredenciamento@procurar');                      // BUSCAR CRED

//  --------------- visitantes CAEX ---------------

Route::get('/exportarCAEX/{id}',            'ControladorCaex@export')->name('excelcaex');   //exportar visitantes credenciamento
Route::post('/importcaex/{id}',             'ControladorCaex@import')->name('importcaex');  //importar visitantes credenciamento
Route::get('/imprimircaex/{email}&{id}',    'ControladorCaex@print')->name('imprimircaex'); //imprimir visitantes credenciamento
Route::get('/apagarcaex/{cpf}',             'ControladorCaex@destroy');                     // ocultar CAEX 
Route::get('/emailcaex',                    'ControladorCaex@emailcaex');                   // verificar email CAEX
Route::get('/cnpjcaex',                     'ControladorCaex@cnpjcaex');                    // verificar cnpj CAEX
Route::get('/cpfcaex',                      'ControladorCaex@cpfcaex');                     // verificar CPF CAEX
Route::get('/procurarcaex',                 'ControladorCaex@procurar');                    // BUSCAR CAEX

//  --------------- SALA ---------------

Route::get('/sala',                     'ControladorSala@store');                       // salvar sala
Route::get('/sala/{id}',                'ControladorSala@show');                        // informações sala
Route::post('/info/editarsala/{id}',    'ControladorSala@update');                      // atualizar sala
Route::get('/sala/apagar/{id}',         'ControladorSala@destroy');                     // apagar sala
Route::post('/codigo',                  'ControladorSala@codigo');                      // buscar codigo sala
Route::get('/exportarsala/{id}',        'ControladorSala@export')->name('excelsala');   // exportar sala

//  --------------- INGRESSOS ---------------

Route::get('/ingresso/{id_evento}',             'ControladorIngresso@show')->name('ingresso');  // cadastrar ingresso
Route::post('/criaringresso',                   'ControladorIngresso@store');                   // salvar ingresso
Route::get('/desativarIngresso',                'ControladorIngresso@remover');                 // remover ingresso do formulario
Route::get('/editaringresso/{id}&{id_eveto}',   'ControladorIngresso@edit')->name('editaringresso');  // remover ingresso do formulario 
Route::get('/apagaringresso/{id}&{id_eveto}',   'ControladorIngresso@destroy')->name('apagaringresso');   //apagar evento
Route::post('/atualizaringresso/{id}',          'ControladorIngresso@update')->name('atualizaringresso');   //apagar evento

//  --------------- Meses ---------------

Route::get('/mesas/{id_evento}',                'ControladorMesa@show')->name('mesas');  // cadastrar ingresso
Route::post('/criarmesas',                      'ControladorMesa@store');                   // salvar ingresso
Route::get('/desativarMesa',                    'ControladorMesa@remover');                 // remover ingresso do formulario
//Route::get('/editarmesas/{id}&{id_eveto}',      'ControladorMesa@edit')->name('editarmesa');  // remover ingresso do formulario 
Route::get('/apagarmesas/{id}&{id_eveto}',      'ControladorMesa@destroy')->name('apagarmesa');   //apagar evento
Route::post('/atualizarmesas/{id}&{id_eveto}',  'ControladorMesa@update')->name('atualizarmesa');   //apagar evento


//  --------------- TESTES ---------------
Route::get('/pagamentoMesa/{cpf}&{id_eveto}&{id_mesa}&{id_venda_mesa}',     'ControladorPagamento@payMesa')->name('pagamentomesa');

Route::get('/pagamento/{cpf}&{id_eveto}&{id_venda_ingresso}&{id_ingresso}', 'ControladorPagamento@pay')->name('pagamento');  // remover ingresso do formulario 

Route::post('pago/',           'ControladorPagamento@CredPayment')->name('pagar');

Route::get('teste/boleto',      'ControladorPagamento@boleto')->name('boleto');
Route::post('teste/boletopay',  'ControladorPagamento@boletoPayment')->name('boletoPayment');

Route::get('/novoevento',   function () { return view('teste.novoevento'); })->name('novoevento');


// INSERT INTO `venda_ingressos` (`id`, `id_evento`, `id_ingresso`, `cpf`, `qntd`, `preco`, `total`, `data_compra`, `pago`, `ativo`, `created_at`, `updated_at`) VALUES (NULL, '8', '5', '11859005608', '1', '1100000', '1100000', '2020-04-15', 'nao', '1', '2020-04-15 17:04:20', '2020-04-15 17:04:20'), (NULL, '8', '5', '52479372014', '1', '1100000', '1111000', '2020-04-15', 'nao', '1', '2020-04-15 17:21:40', '2020-04-15 17:21:40'), (NULL, '8', '6', '52479372014', '1', '11000', '1111000', '2020-04-15', 'nao', '1', '2020-04-15 17:21:40', '2020-04-15 17:21:40'), (NULL, '8', '6', '22048484093', '1', '11000', '11000', '2020-04-15', 'nao', '1', '2020-04-15 17:22:27', '2020-04-15 17:22:27')
