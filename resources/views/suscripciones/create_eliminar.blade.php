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
              <li class="breadcrumb-item active"><i class="ses-text-muted">Agregar</i></li>
          </ol>
      </nav>
    </div>

     <div class="row">
      <div class="col-12">
        <div class="mt-3">
          <button type="button"
              onclick="location.href='{{ route($pageModule.'.view',$id) }}'" class="btn btn-sm btn-white">
              <i class="fa fa-arrow-circle-left"></i> Regresar
            </button>
        </div>
      </div>
    </div>

    <div class="container mt-3">
      <div class="row">
        <div class="col-12">

          <form action="{{ route($pageModule.'.store',['id' => $id ]) }}" method="POST">
          @csrf
            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Nueva suscripción</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Nadador:</div>
                      <div class="col-9 ses-text-blue">{{ $row->nombre }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Plan del nadador:</div>
                      <div class="col-9 ses-text-blue">{{ $row->plan }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Monto a pagar:</div>
                      <div class="col-9 ses-text-danger">${{ $row->precio }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Tipo pago:</div>
                      <div class="col-9">
                        <select name="idtipo_pago" class="form-control" required>
                          <option value="2">Efectivo</option>
                          <option value="2">Deposito</option>
                          <option value="1">Transferencia</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="mt-3 text-center">
                    <button type="submit" name="save" class="btn btn-sm btn-primary ses-text-white"><i class="fa fa-save"></i> Confirmar Suscripción</button>
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
