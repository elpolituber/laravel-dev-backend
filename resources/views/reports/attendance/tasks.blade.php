<style>
    table, th, td {
        border: 1px solid black;
    }
    th {
        text-align: left;
    }
</style>
<table>
    <thead>
    <tr>
        <th>NOMBRE DE LA INSTITUCIÓN:</th>
        <th>{{$institution->denomination}} {{$institution->name}}</th>
    </tr>
    <tr>
        <th>NOMBRE DEL RECTOR:</th>
        <th>Msc. IVÁN BORJA CARRERA</th>
    </tr>
    </thead>
</table>

<table>
    <thead>
    <tr>
        <th colspan="6">DESCRIPCIÓN DE LAS ACTIVIDADES REALIZADAS EN TELETRABAJO POR CADA UNO DE LOS FUNCIONARIOS</th>
    </tr>
    <tr>
        <th rowspan="2">FECHA</th>
        <th rowspan="2">DOCENTE / ADMINISTRATIVO RESPONSABLE</th>
        <th rowspan="2">PROCESOS</th>
        <th rowspan="2">ACTIVIDADES REALIZADA EN LA SEMANA</th>
        <th colspan="3">ESTADO DE LA ACTIVIDAD</th>
        <th rowspan="2">OBSERVACIONES</th>
    </tr>
    <tr>
        <th>EJECUTADA</th>
        <th>PENDIENTE</th>
        <th>TERMINADA</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
        @foreach($report['attendances'] as $attendance)
            @foreach($attendance['tasks'] as $task)
                <tr>
                    @if($loop->index===0)
                        <td rowspan="{{sizeof($attendance['tasks'])}}">
                            {{strtoupper($date)}}
                        </td>
                        <td rowspan="{{sizeof($attendance['tasks'])}}">
                            {{ $report['user']['first_lastname']}} {{ $report['user']['second_lastname']}}
                            {{ $report['user']['first_name']}} {{ $report['user']['second_name']}}
                        </td>
                    @endif
                    <td>{{$task->type->parent->name}}</td>
                    <td>{{$task->type->name}}</td>
                    <td colspan="3">{{$task->percentage_advance}}</td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>
