<div class="row">
    <div class="col-12">
        <table class="table bordered">
            <tr>
                <th>#</th>
                <th>Estatus</th>
                <th>Alumno</th>
                <th colspan="2" class="text-center">Asistencia</th>
            </tr>
            @foreach ($rowsregistros as $key => $v)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                        @if($v->active == 1)
                            <span class="badge badge-primary"><i class="bi bi-calendar-check"></i> Reservado</span>
                        @elseif($v->active == 2)
                            <span class="badge badge-success"><i class="bi bi-calendar-check"></i> Visitado</span>
                        @elseif($v->active == 3)
                            <span class="badge badge-danger"><i class="bi bi-calendar-check"></i> No Asistio</span>
                        @endif
                    </td>
                    <td>{{ $v->alumno }}</td>
                    <td class="text-center">
                        @if($v->active == 1)
                            <button type="button" name="save" class="btn btn-xs btn-outline-success js-asistencia" 
                                data-id="{{ $v->id }}" 
                                data-idplan="{{ $idplan_horario }}" 
                                data-fecha="{{ $fecha }}" 
                                data-alumno="{{ $v->alumno }}" 
                                data-estado="2"><i class="bi bi-check-circle"></i> Asitió</button>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($v->active == 1)
                            <button type="button" name="save" class="btn btn-xs btn-outline-danger js-asistencia" 
                                data-id="{{ $v->id }}" 
                                data-idplan="{{ $idplan_horario }}" 
                                data-fecha="{{ $fecha }}" 
                                data-alumno="{{ $v->alumno }}" 
                                data-estado="3"><i class="bi bi-x-circle"></i> No Asistió</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
