@extends('layouts.app')

@section('content')

    <main class="row">
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

            <div class="row sbox mt-3">
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
                        <table class="table table-bordered table-hover bg-white">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th width="50">Estatus</th>
                                    <th>Nivel</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Precio</th>
                                    <th class="text-center">Duración del plan (días)</th>
                                    <th class="text-center">Vigencia</th>
                                    <th class="text-center" width="100" colspan="2">Acción</th>
                                </tr>
                            </thead>
                             @foreach ($pagination as $n)
                                <tr>
                                    <td class="text-center">{{ ++$j }}</td>
                                    <td class="text-center">
                                        @if($n->active == 1)    
                                            <span class="badge badge-success">Activo</span>
                                        @endif
                                    </td>
                                    <td>{{ $n->plan }}</td>
                                    <td>{{ $n->descripcion }}</td>
                                    <td class="text-center">${{ $n->precio }}</td>
                                    <td class="text-center">{{ $n->max_visitas_mes }}</td>
                                    <td class="text-center">Vigencia de {{ $n->duracion_dias }} días</td>
                                    <td class="text-center">
                                        <a href="{{ route($pageModule . '.horarios', $n->id) }}"
                                            class="btn btn-xs btn-white">
                                            <i class="bi bi-calendar-week"></i> Horario
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
