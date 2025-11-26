@extends('layouts.app')

@section('content')

    <main class="container">
        <div class="col-md-12">

            <div class="page-header">
                <div class="page-title">
                    <h4 class="text-blue-900"> <strong>{{ $pageTitle }}</strong> <small class="text-gray-400"><i>{{ $pageNote }}</i></small></h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::to('dashboard') }}"> <i class="fa fa-home"></i> </a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('usuarios.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="sbox mt-3">
                <div class="sbox-title">
                    <h5><i class="fa fa-table"></i> <strong>{{ $pageTitle }}</strong></h5>
                    <div class="sbox-tools">
                        <a href="{{ route($pageModule . '.create') }}"
                            class="btn btn-xs btn-primary ses-text-white">
                            <i class="fa-solid fa-circle-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="sbox-content">

                    <form method="GET" class="mb-2">


                        <div class="row ">
                            <div class="col-9">
                                <div class="ses-text-muted fw-bold">Nombre</div>
                                <input type="text" name="name" class="form-control" placeholder="Ingresa nombre">
                            </div>
                             <div class="col-auto">
                                <div class="ses-text-muted fw-bold">Paginación</div>
                                <select name="nopagina" class="form-control form-select-sm ses-fs-xs"
                                    onchange="this.form.submit()">
                                    @foreach ([10, 25, 50, 100] as $n)
                                        <option value="{{ $n }}" @selected(request('nopagina', 5) == $n)>
                                            {{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <div class="ses-text-muted fw-bold">Buscar</div>
                                <button type="submit" class="btn btn-xs btn-white">
                                    <i class="fa-solid fa-search"></i> Buscar
                                </button>
                            </div>
                            <div class="col-auto">
                                <div class="ses-text-muted fw-bold">limpiar</div>
                                <a href="{{ route($pageModule.'.index') }}" class="btn btn-xs btn-white"><i class="fa-solid fa-eraser"></i> Limpiar</a>
                            </div>
                        </div>
                    </form>


                        @if ($pagination->count())
                        <table class="table table-striped table-hover bg-white">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th class="text-center">Estatus</th>
                                    <th class="text-center">Nivel</th>
                                    <th class="text-center">Nombre Completo</th>
                                    <th class="text-center">Correo Electrónico</th>
                                    <th class="text-center">Último Login</th>
                                    <th class="text-center">Última Actividad</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                             @foreach ($pagination as $v)
                                    <tr>
                                        <td class="text-center">{{ ++$j }}</td>
                                        <td class="text-center">
                                            @if($v->active == 1)
                                                <span class="badge badge-success">Activo</span>
                                            @elseif($v->active == 2)
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>{{ $v->nivel }}</td>
                                        <td>{{ $v->name }}</td>
                                        <td>{{ $v->email }}</td>
                                        <td>{{ $v->last_login }}</td>
                                        <td>{{ $v->last_activity }}</td>
                                        <td class="text-center">
                                             <a href="{{ route($pageModule . '.edit', $v->id) }}"
                                                class="btn btn-xs btn-white">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    @else
                        {{-- Vista alternativa sin tabla cuando no hay registros --}}
                        <div class="border rounded text-center text-muted py-5">
                            <p class="mb-3">No hay registros para mostrar.</p>
                        </div>
                    @endif


                </div>

                 <div class="row mt-3">
                    <div class="col-12 text-right">
                        {{ $pagination->appends(request()->query())->onEachSide(2)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                
            </div>
        </div>
    </main>

   

@stop
