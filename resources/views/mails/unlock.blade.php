@extends('index')
@section('content')
    <div class="row">
        <div class="col-12 text-muted ">
            <h3 class="text-center">Desbloqueo de usuario</h3>
            <br>
            <p>Recibimos una solicitud de desbloqueo de usuario.</p>
            <br>
            <p>Este link tiene tiempo de duración de <b>10 min.</b> y es válido por <b>una sola ocasión</b>.</p>
            <br>
            <div class=" text-center">
                <a class="btn btn-primary text-center"
                   href="{{ env('CLIENT_URL') }}/#/auth/unlock?token={{$token}}&username={{$user->username}}">
                    Desbloquear Usuario
                </a>
            </div>
            <br>
            <br>
            <p class="text-muted">Si no puede acceder, copie la siguiente url:</p>
            <p class="text-muted">{{env('CLIENT_URL')}}/#/auth/unlock?token={{$token}}&username={{$user->username}}</p>
            <br>
            <p>Si no ha solicitado este servicio, repórtelo a su Institución.</p>
        </div>
    </div>
@endsection
