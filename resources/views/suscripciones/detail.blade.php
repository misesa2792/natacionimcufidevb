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
              <li class="breadcrumb-item active"><i class="ses-text-muted">Detalle del pago</i></li>
          </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12">
        <a href="{{ route($pageModule.'.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
      </div>
    </div>
  

    <div class="row mt-2">
      <div class="row">
        <div class="col-6">
            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Alumno</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Nombre completo:</div>
                      <div class="col-9 ses-text-blue">{{ $row->nombre }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">CURP:</div>
                      <div class="col-9">{{ $row->curp }}</div>
                    </div>
                  </div>
              </div>
            </div>


            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Detalle de la mensualidad</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Plan:</div>
                      <div class="col-3"><strong>{{ $row->nivel }}</strong> <i>({{ $row->plan }})</i></div>
                      <div class="col-3 text-right ses-text-muted">Precio:</div>
                      <div class="col-3"><strong>${{ $row->monto }}</strong></div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Mes pagado:</div>
                      <div class="col-3">{{ $row->mes }}</div>
                      <div class="col-3 text-right ses-text-muted">Máximo de visitas al mes:</div>
                      <div class="col-3">{{ $row->max_visitas_mes }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Tipo de pago:</div>
                      <div class="col-9">{{ $row->pago }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Subtotal:</div>
                      <div class="col-9">{{ $row->monto_general }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Descuento:</div>
                      <div class="col-9">{{ $row->descuento }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Total pagado:</div>
                      <div class="col-9">{{ $row->monto_pagado }}</div>
                    </div>
                  </div>

                  <div class="row">
                     <div class="col-12 text-center">
                        <a href="{{ route($pageModule.'.pdf', ['ids' => $ids]) }}" target="_blank" class="btn btn-outline-primary btn-sm px-4 rounded-pill">
                          <i class="bi bi-cash-coin"></i> Reimprimir Orden de Pago
                      </a>
                     </div>
                  </div>

              </div>
            </div>

       

        </div>
        <div class="col-6">

            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Fechas seleccionadas</strong></h5>
              </div>
              <div class="sbox-content"> 

                  @foreach($rowsFechas as $key => $v)
                    <div class="mb-3">
                      <div class="row">
                        <div class="col-1 text-right ses-text-muted">{{ ++$key }}.-</div>
                        <div class="col-2 text-center">
                          @if($v->active == 1)
                              <span class="badge badge-primary"><i class="bi bi-calendar-check"></i> Reservado</span>
                          @elseif($v->active == 2)
                              <span class="badge badge-success"><i class="bi bi-calendar-check"></i> Visitado</span>
                          @elseif($v->active == 3)
                              <span class="badge badge-danger"><i class="bi bi-calendar-check"></i> No Asistio</span>
                          @endif
                        </div>
                        <div class="col-9">
                          {{ \Carbon\Carbon::parse($v->fecha)->translatedFormat('j \d\e F \d\e Y') }}
                        </div>
                      </div>
                    </div>
                  @endforeach

                  
              </div>
            </div>

          
          </div>

        <div class="col-12">
          


        </div>

      </div>

    </div>

  </div>
       

</main>

@push('js')

@endpush

@stop
