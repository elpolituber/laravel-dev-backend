@extends('index')
@section('content')
    <div class="row">
        <div class="col-12 text-muted">
            <h3 class="text-center">Información código de seguridad</h3>
            <br>
            <p>El código de seguridad para realizar su solicitud es: <b>{{$token}}</b></p>
            <br>
            <p>Este código de seguridad tiene tiempo de duración de
                <b>2 min.</b>
                y es válido por
                <b>una sola ocasión</b>.
            </p>
            <br>
            <p>Si no ha solicitado este servicio, repórtelo a su Institución.</p>
        </div>
    </div>
@endsection
