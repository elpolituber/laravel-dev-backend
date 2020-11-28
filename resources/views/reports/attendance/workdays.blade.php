<table>
    <thead>
    <tr>
        <th>MES</th>
        <th>Nro. CÉDULA</th>
        <th>APELLIDOS Y NOMBRES COMPLETOS</th>
        <th>DÍA LABORADOS MES ANTERIOR (incluye fines de semana)</th>
        <th>DÍA LABORADOS MES ACTUAL (incluye fines de semana)</th>
        <th>TIPO DE DESVINCULACIÓN</th>
        <th>DENOMINACIÓN DEL PUESTO</th>
        <th>NOMBRE DEL INSTITUTO</th>
        <th>CORREO ELECTRÓNICO PERSONAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
        <tr>
            <td>{{strtoupper($report['month'])}}</td>
            <td>{{ $report['user']['identification']}}</td>
            <td>{{ $report['user']['first_lastname']}} {{ $report['user']['second_lastname']}}
                {{ $report['user']['first_name']}} {{ $report['user']['second_name']}}</td>
            <td>{{$report['days13']}}</td>
            <td>{{$report['days18']}}</td>
            <td></td>
            <td></td>
            <td>{{$report['institution']}}</td>
            <td>{{$report['user']['personal_email']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
