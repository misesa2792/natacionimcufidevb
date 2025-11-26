@extends('layouts.app')

@section('content')

<div style="display: none;">
    
 <form action="{{ route('nadadores.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="archivo" class="form-label">Seleccionar archivo Excel</label>
        <input type="file" 
                name="archivo" 
                id="archivo" 
                class="form-control"
                accept=".xlsx,.xls,.csv">
        <small class="text-muted">Formatos permitidos: .xlsx, .xls, .csv</small>
    </div>

    <button type="submit" class="btn btn-sm btn-primary">
        <i class="fa fa-upload me-1"></i> Subir y procesar
    </button>

    <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary ms-2">
        <i class="fa fa-arrow-left me-1"></i> Regresar
    </a>
</form>

</div>

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
                            <a href="{{ route($pageModule.'.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="row sbox mt-3">
                <div class="sbox-title">
                    <h5><i class="fa fa-table"></i> <strong>{{ $pageTitle }}</strong></h5>
                    <div class="sbox-tools">
                       <a href="{{ route($pageModule . '.create',['page' => request()->page]) }}"
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
                                    <th width="60">Estatus</th>
                                    <th>Nadador</th>
                                    <th class="text-center">CURP</th>
                                    <th colspan="2">Nivel <i>(Plan)</i></th>
                                    <th>Descuento</th>
                                    <th class="text-center">Genero</th>
                                    <th class="text-center">Edad</th>
                                    <th class="text-center">Fecha Nacimiento</th>
                                    <th>Domicilio</th>
                                    <th class="text-center">Acción</th>
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
                                        <td>{{ $n->nadador }}</td>
                                        <td class="text-center">{{ $n->curp }}</td>
                                        <td width="50">
                                            @if(!empty($n->plan))
                                                <span class="badge badge-success">Validado</span>
                                            @else
                                                <span class="badge badge-danger">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>{{ $n->nivel }} <i>({{ $n->plan }})</i></td>
                                        <td>{{ $n->desc_descuento }} <i>({{ $n->descuento }}%)</i></td>
                                        <td class="text-center">{{ $n->genero }}</td>
                                        <td class="text-center">{{ $n->edad }}</td>
                                        <td class="text-center">{{ $n->fecha_nacimiento }}</td>
                                        <td>{{ $n->domicilio }}</td>
                                        <td class="text-center">
                                            <a href="{{ route($pageModule . '.edit', ['id' => $n->id, 'page' => request()->page]) }}"
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
