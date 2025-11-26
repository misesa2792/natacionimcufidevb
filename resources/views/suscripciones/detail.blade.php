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
        <a href="{{ route($pageModule.'.index',['page' => request()->page]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
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
                  <div class="row">
                      <div class="col-7">
                          <h5><i class="fa fa-table"></i> <strong> Detalle de la mensualidad</strong></h5>
                      </div>
                      <div class="col-5 text-right">
                        <a href="{{ route($pageModule.'.pdf', ['ids' => $ids]) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                          <i class="bi bi-printer"></i> Reimprimir orden de pago
                        </a>
                     </div>
                  </div>

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

               
              </div>
            </div>


             <div class="sbox">
              <div class="sbox-title ses-text-muted">
                <div class="row">
                  <div class="col-7">
                    <h5><i class="fa fa-table"></i> <strong> Evidencia de pago</strong></h5>
                  </div>
                  <div class="col-5 text-right">
                      <button type="button" class="btn btn-sm btn-outline-success js-open-modal rounded-pill">
                            <i class="bi bi-cloud-arrow-up"></i> Subir evidencia
                      </button>
                  </div>
                </div>
              </div>
              <div class="sbox-content"> 

                  @if($rowsImgs->isEmpty())
                      <div class="alert alert-warning text-center py-4" style="border-radius: 10px;">
                          <i class="fa fa-exclamation-circle fa-2x mb-2"></i>
                          
                          <h5 class="mb-1"><strong>Registro sin evidencia</strong></h5>

                          <p class="mb-0">
                              Todavía no se ha cargado una imagen como comprobante de pago.
                          </p>

                          <p class="mt-1 mb-0">
                              <strong>Importante:</strong> la evidencia debe subirse entre el 
                              <strong>día 1 y el 10 de cada mes</strong>.  
                              Después de esa fecha, el registro será cancelado para 
                              liberar los horarios asignados.
                          </p>
                      </div>

                  @else
                      <div class="d-flex flex-wrap gap-3 mb-3">
                          @foreach ($rowsImgs as $v)
                              <div class="border p-2 rounded" style="width:150px; height:150px; overflow:hidden;">
                                <a href="{{ asset('storage/'.$v->url) }}">
                                  <img src="{{ asset('storage/'.$v->url) }}" alt="Evidencia" class="img-fluid" style="object-fit: cover; width:100%; height:100%;">
                                </a>
                              </div>
                          @endforeach
                      </div>
                  @endif
              </div>
            </div>


        </div>
        <div class="col-6">

            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Fechas registradas</strong></h5>
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

      </div>

    </div>

  </div>
       

</main>

<div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-top modal-lg ">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="modalHorarioLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="ses-modal-body">
           <form action="{{ route('suscripciones.upload',['ids' => $ids,'page' => request()->page]) }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="mb-3">
                  <label class="form-label">Selecciona imagen</label>
                  <input type="file" name="documento" class="form-control" accept="image/*" required>
              </div>

              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-xs btn-outline-primary"><i class="bi bi-cloud-arrow-up"></i> Guardar Evidencia</button>
                </div>
              </div>
          </form>
      </div>

      <div class="modal-footer">
        
      </div>

    </div>
  </div>
</div>

@push('js')
<script>
  $(function () {

      $(document).on('click', '.js-open-modal', function () {

          // Abrir modal (Bootstrap)
          $('#modalHorario').modal('show');
          $('#modalHorarioLabel').html('Subir evidencia ');
        
      });

  });
</script>
@endpush



@stop
