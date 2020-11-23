@extends('index')
@section('content')
    <div class="row">
        <div class="col-12 text-muted ">
            <h3 class="text-center">Recuperación de contraseña</h3>
            <br>
            <p>Recibimos una solicitud de restablecimiento de contraseña para su cuenta.
            </p>
            <br>
            <p>Este link tiene tiempo de duración de <b>10 min.</b> y es válido por <b>una sola ocasión</b>.</p>
            <br>
            <div class=" text-center">
                <a class="btn btn-primary"
                   href="{{ env('CLIENT_URL') }}/#/auth/password-reset?token={{$token}}&username={{$user->username}}">
                    Restablecer Contraseña
                </a>
            </div>
            <br>
            <br>
            <p class="text-muted">
                Si no puede acceder, copie la siguiente url:
            </p>
            <p class="text-muted">
                {{env('CLIENT_URL')}}/#/auth/password-reset?token={{$token}}&username={{$user->username}}
            </p>
            <br>
            <p>Si no ha solicitado este servicio, repórtelo a su Institución.</p>
        </div>
    </div>
@endsection
