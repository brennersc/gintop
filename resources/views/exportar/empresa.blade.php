

<table class="table table-ordered table-bordered table-hover table-striped table-sm " id="tabelaempresa">
      <thead>
            <tr>      
                     <th><b>Razão Social</b></th>
                     <th><b>CNPJ</b></th>
                     <th><b>Nome do Responsável</b></th>
                     <th><b>Email</b></th>
                     <th><b>Celular</b></th>
                     <th><b>Telefone Fixo</b></th>
                     <th><b>Site</b></th>
                     <th><b>CEP</b></th>
                     <th><b>rua</b></th>
                     <th><b>numero</b></th>
                     <th><b>complemento</b></th>
                     <th><b>bairro</b></th>
                     <th><b>cidade</b></th>
                     <th><b>estado</b></th>

            </tr>
    </thead>
    <tbody>
                
            <tr>
                @foreach ($empresa as $empre)                

                            <td>{{$empre->razao_social}}</td>
                            <td>{{ preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $empre->cnpj) }}</td>
                            <td>{{$empre->responsavel}}</td>
                            <td>{{$empre->email}}</td>
                            <td>{{$empre->celular}}</td>
                            <td>{{$empre->telefone}}</td>
                            <td>{{$empre->site}}</td>
                            <td>{{$empre->cep}}</td>
                            <td>{{$empre->rua}}</td>
                            <td>{{$empre->numero}}</td>
                            <td>{{$empre->complemento}}</td>
                            <td>{{$empre->bairro}}</td>
                            <td>{{$empre->cidade}}</td>
                            <td>{{$empre->estado}}</td>
                 @endforeach     
            </tr>
            
    </tbody> 
</table> 
