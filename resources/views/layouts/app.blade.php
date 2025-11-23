{{-- resources/views/dashboard.blade.php --}}
@extends('adminlte::page')

@section('title', 'IMCUFIDE Valle de Bravo')

{{-- Encabezado de contenido (aparece bajo el header) --}}
@section('content_header')

@stop

{{-- Contenido principal --}}
@section('content')
    <div id="appVue">
        {{-- Tu navegación propia si la necesitas además del sidebar (opcional) --}}
        {{-- @include('layouts.navigation') --}}

        <main>
            {{-- aquí va tu contenido --}}
        </main>

        <modal-manager ref="globalModal" id="modalSesmas" size="modal-fullscreen" />
    </div>
@stop

{{-- ... tus otras secciones ... --}}

{{-- CSS extra (se inserta en <head>) --}}
@section('css')
    {{-- Select2 via CDN si no usas el plugin de AdminLTE --}}

    {{-- Vite (tus estilos) --}}
    @vite(['resources/sass/app.scss', 'resources/css/sesmas.css'])
    
    <style>
        .toast-success{background: var(--bs-success)}
        .toast-error{background: var(--bs-danger)}
            /* Cambiar tamaño y peso de letra del sidebar */
        .nav-sidebar .nav-link {
            font-size: 12px; /* tamaño en px o rem */
        }

        /* Cambiar tamaño para los subniveles */
        .nav-sidebar .nav-treeview .nav-link .nav-icon {
            font-size: 12px;
        }
        .nav-icon {
            font-size: 12px !important;
        }
    </style>

@stop

{{-- JS extra (al final del body del layout) --}}
@section('js')
    {{-- Vite (tu JS) --}}
    @vite(['resources/js/app.js'])

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
 
@stop







