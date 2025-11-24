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
              <li class="breadcrumb-item active"><i class="ses-text-muted">Registrar Asistencia</i></li>
          </ol>
      </nav>
    </div>
  
   
      <div class="container mt-3">
        
          <div class="row">
              <div class="col-6">
              <form action="{{ route($pageModule.'.store',['id' => $id , 'std' => 2, 'page' => request()->page]) }}" method="POST">
              @csrf
                  <div class="sbox">
                    <div class="sbox-title ses-text-muted">
                        <h5><i class="fa fa-table"></i> <strong> Registrar asistencia</strong></h5>
                    </div>
                    <div class="sbox-content"> 

                          <div class="mt-3 text-center">
                            <button type="submit" name="save" class="btn btn-sm btn-success ses-text-white"><i class="bi bi-check-circle"></i> Registrar asistencia</button>
                          </div>
                    </div>
                  </div>
                </form>
            </div>

            <div class="col-6">
              <form action="{{ route($pageModule.'.store',['id' => $id, 'std' => 3, 'page' => request()->page]) }}" method="POST">
              @csrf
                  <div class="sbox">
                    <div class="sbox-title ses-text-muted">
                        <h5><i class="fa fa-table"></i> <strong> No asistio el usuario</strong></h5>
                    </div>
                    <div class="sbox-content"> 

                          <div class="mt-3 text-center">
                            <button type="submit" name="save" class="btn btn-sm btn-danger ses-text-white"><i class="bi bi-x-circle"></i> No asistio el usuario</button>
                          </div>
                    </div>
                  </div>
                </form>
            </div>
          </div>
          
        </div>
  </div>
       

</main>

@stop
