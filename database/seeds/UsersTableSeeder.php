<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Usuario;
use App\Empresa;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //EMPRESA ADMIN
        Empresa::create([          
            'cnpj'              => '00000000000000', 
            'nome_fantasia'     => 'Administrador',
            'razao_social'      => 'Administrador',
            'responsavel'       => 'Administrador',
            'celular'           => '(00) 00000-0000',
            'telefone'          => '(00) 00000-0000',
            'email'             => 'toptecnologia.com',
            'site'              => 'toptecnologia.com',
            'cep'               => '00000-00',
            'estado'            => 'MG',
            'cidade'            => '-',
            'bairro'            => '-', 
            'rua'               => '-', 
            'numero'            => '-',
            'complemento'       => '-',   
            'ativo'             => '2',                      
        ]);

        $id = Empresa::select('id')->where('cnpj', '00000000000000')->where('nome_fantasia', 'Administrador')->first();

        //USUARIOS ADMIN
        User::create([
            'name'          => 'Júlio Gonçalves',
            'email'         => 'juliogoncalves@toptecnologia.com',
            'password'      => bcrypt('12345678'),
            'cargo'         => '0',
            'id_empresa'    => $id->id,
            'empresa'       => 'Administrador'
        ]);
        User::create([
            'name'          => 'Brenner Cunha',
            'email'         => 'brennersc@gmail.com',
            'password'      => bcrypt('12345678'),
            'cargo'         => '0',
            'id_empresa'    => $id->id,
            'empresa'       => 'Administrador'
        ]);

        Usuario::create([
            'nome'          => 'Júlio Gonçalves',
            'email'         => 'juliogoncalves@toptecnologia.com',
            'cargo'         => '0',
            'id_empresa'    => $id->id,
            'empresa'       => 'Administrador'
        ]);
        Usuario::create([
            'nome'          => 'Brenner Cunha',
            'email'         => 'brennersc@gmail.com',
            'cargo'         => '0',
            'id_empresa'    => $id->id,
            'empresa'       => 'Administrador'
        ]);

        //EMPRESA TESTE
        // Empresa::create([            
        //     'cnpj'              => '55759851000104', 
        //     'nome_fantasia'     => 'Nome Fantasia',
        //     'razao_social'      => 'Razão Social',
        //     'responsavel'       => 'Responsavel Empresa',
        //     'celular'           => '(31) 00000-0000',
        //     'telefone'          => '(31) 00000-0000',
        //     'email'             => 'email@gmail.com',
        //     'site'              => 'teste.com',
        //     'cep'               => '32410-059',
        //     'estado'            => 'MG',
        //     'cidade'            => 'ibirite',
        //     'bairro'            => 'sr de fatima', 
        //     'rua'               => 'sr de fatima', 
        //     'numero'            => '205',
        //     'complemento'       => 'casa',   
        //     'ativo'             => '1',                      
        // ]);

    }
}
