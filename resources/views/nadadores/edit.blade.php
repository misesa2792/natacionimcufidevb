@extends('layouts.app')

@section('content')



<main class="row">
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
              <li class="breadcrumb-item active"><i class="ses-text-muted">Editar</i></li>
          </ol>
      </nav>
    </div>
  
    <form action="{{ route($pageModule.'.update',$id) }}" method="POST">
       @csrf
    <div class="row mt-3">

        <div class="col-6">
            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Editar usuario</strong></h5>
              </div>
              <div class="sbox-content"> 
                    <div class="mb-3">
                      <label class="form-label fw-bold ses-text-muted">Nombre completo:</label>
                      <input type="text" name="name" value="{{ $row['name'] }}" class="form-control" placeholder="Ingresa nombre completo" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label fw-bold ses-text-muted">Teléfono:</label>
                      <input type="number" name="phone" value="{{ $row['phone'] }}" class="form-control" placeholder="Ingresa teléfono" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label fw-bold ses-text-muted">CURP:</label>
                      <input type="text" name="curp" value="{{ $row['curp'] }}" class="form-control" placeholder="Ingresa CURP - 18 dígitos" required>
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

        <div class="col-6">
          <div class="sbox">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Credenciales de Acceso</strong></h5>
            </div>
            <div class="sbox-content"> 
                  <div class="mb-3">
                    <label class="form-label fw-bold ses-text-muted">Correo electrónico:</label>
                    <input type="email" name="email" value="{{ $row['email'] }}" class="form-control" placeholder="Ingresa email" required>
                  </div>

                  <div class="mb-1">
                    <label class="form-label fw-bold ses-text-muted">Contraseña:</label>
                    <div class="ses-text-danger"><small>Deja en blanco si no quieres cambiar tu contraseña actual</small> </div>
                    <input type="password" name="password" value="" class="form-control" placeholder="****">
                  </div>
            </div>
          </div>
        </div>
    </div>
        </form>
  </div>
       

</main>

@stop
