<table class="table table-ordered table-bordered table-hover table-striped table-sm " id="tabelasalassa">
        <thead>
                <tr>
                        <th><b>Nome</b></th>
                        <th><b>CPF</b></th>
                        <th><b>Código</b></th>
                        <th><b>Horário</b></th>
                </tr>
        </thead>
        <tbody>
                @foreach ($sala as $salas)
                        <tr>
                                <td>{{ $salas->nome }}</td>
                                <td>{{ preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $salas->cpf) }}</td>
                                <td>{{ $salas->codigo }}</td>
                                <td>{{ date(" d/m/Y - H:i ", strtotime($salas->horario)) }}</td>
                        </tr>
                @endforeach
        </tbody>
</table>