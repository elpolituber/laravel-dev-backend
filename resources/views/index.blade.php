<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
    <style>

    </style>
</head>
<body>
<div class="content">
    <div class="row">
        <div class="col-6 offset-2 border">
            <h5 class="text-center bg-secondary text-white">{{env('APP_SYSTEM')}}</h5>
            <p class="text-muted">
                Fecha: <b>{{\Carbon\Carbon::now()->toDateString()}}</b>
                <b class="ml-2">{{\Carbon\Carbon::now()->toTimeString()}}</b>
            </p>
            <p class="text-muted">
                Estimado/a: {{$user->first_lastname}} {{$user->second_lastname}} {{$user->first_name}} {{$user->second_name}}</p>
            <br>
            @yield('content')
            <hr size="3">
            <div class="row">
                <div class="col-12">
                    <footer class="text-muted">
                        <p>Saludos cordiales</p>
                        <p>{{env('APP_SYSTEM')}}</p>
                        <small>Nota de descargo:</small>
                        <small>La información contenida en este correo electrónico es confidencial y solo puede ser
                            utilizada por el usuario al cual esta dirigido.</small>
                        <small>Esta información no debe ser distribuida ni copiada total o parcialmente por ningun medio
                            sin la autorización de la Institución.
                        </small>
                        <p class="small">Favor no responder este mensaje que ha sido emitido automáticamente por el
                            sistema SIGA-A.</p>
                    </footer>
                </div>
            </div>
            <h6 class="text-center bg-secondary text-white">&copy; {{\Carbon\Carbon::now()->format('Y')}} Todos los
                derechos reservados. {{env('APP_NAME')}} (V{{env('APP_VERSION')}})</h6>
        </div>
    </div>
</div>
</body>
</html>
