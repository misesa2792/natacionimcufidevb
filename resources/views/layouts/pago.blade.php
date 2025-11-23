<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>IMCUFIDE Valle de Bravo - Pago en línea</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tus estilos (Bootstrap/AdminLTE si quieres mantener el look) --}}
    @vite(['resources/sass/app.scss', 'resources/css/sesmas.css'])
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css') }}">

    <style>
        body {
            background-color: #f5f6fa;
        }
        .pago-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
           
        }
        .pago-card {
            max-width: 480px;
            width: 100%;
        }
        .pago-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .pago-logo {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.05em;
        }
    </style>

    @yield('css')
</head>
<body>

<div class="pago-wrapper">
    <div class="card shadow-sm pago-card" style="padding:5px !important; background: transparent;">
        <div class="card-body" style="padding:0px !important;">

            <div class="pago-header">
                <div class="pago-logo text-uppercase text-muted">
                    <img src="{{ asset('storage/natacion.png') }}" alt="Logo">
                </div>
            </div>

            {{-- aquí va el contenido específico de cada página --}}
            @yield('content')
        </div>
    </div>
</div>


 {{-- Flash messages con SweetAlert --}}
@if(session('msgstatus'))
    <script>
        Swal.fire({
            position: "top-end",
            icon: @json(session('msgstatus')),
            title: @json(session('messagetext')),
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif

@foreach($errors->all() as $error)
    <script>
        Swal.fire({
            position: "top-end",
            icon: "warning",
            title: @json($error),
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endforeach
@yield('js')

</body>
</html>
