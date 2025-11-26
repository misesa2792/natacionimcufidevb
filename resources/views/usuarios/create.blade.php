@extends('layouts.app')

@section('content')

<main class="container">
  <div class="col-12">
    <div class="page-header">
      <div class="page-title">
          <h4 class="text-blue-900"> <strong>{{ $pageTitle }}</strong> <small class="text-gray-400"><i>{{ $pageNote }}</i></small></h4>
      </div>
      
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ URL::to('dashboard') }}"> <i class="fa fa-home s-18"></i> </a></li>
              <li class="breadcrumb-item">
                  <a href="{{ route($pageModule.'.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>    
              </li>
              <li class="breadcrumb-item active"><i class="ses-text-muted">Agregar</i></li>
          </ol>
      </nav>
    </div>
  
    <form action="{{ route($pageModule.'.store') }}" method="POST">
       @csrf
      <div class="row mt-3">
          <div class="col-12">
              <div class="sbox">
                <div class="sbox-title ses-text-muted">
                    <h5><i class="fa fa-table"></i> <strong> Agregar nuevo registro</strong></h5>
                </div>
                <div class="sbox-content"> 

                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Nivel:</label>
                        <select class="form-control js-select2 " name="idnivel" required>
                          <option value="">--Selecciona nivel--</option>
                          @foreach($rowsNivel as $v)
                            <option value="{{ $v->id }}" @selected(old('idnivel') == $v->id)>{{ $v->nivel }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Nombre completo:</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Ingresa nombre completo" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Correo electrónico:</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Ingresa email" required>
                      </div>

                      <div class="mb-1">
                        <label class="form-label fw-bold ses-text-muted">Contraseña:</label>
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control" placeholder="****" required>
                      </div>

                      <div class="mt-3 text-center">
                        <button type="button"
                            onclick="location.href='{{ route($pageModule.'.index') }}'" class="btn btn-sm btn-white">
                            <i class="fa fa-arrow-circle-left"></i> Cancelar
                          </button>
                        <button type="submit" name="save" class="btn btn-sm btn-primary ses-text-white"><i class="fa fa-save"></i> Guardar</button>
                      </div>
                </div>
              </div>
            </div>
        </div>
      </form>
  </div>
       

</main>

@stop
