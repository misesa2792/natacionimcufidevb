<?php

namespace App\Http\Controllers;

use App\Models\Suscripciones;
use App\Models\Reserva;
use App\Models\Descuento;
use App\Models\Reservatemporal;
use App\Models\Year;

use Illuminate\View\View;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Services\SecureTokenService;
use Barryvdh\DomPDF\Facade\Pdf;

class SuscripcionesController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'suscripciones';
    public static int $perpage = 10;

    protected SecureTokenService $secureToken;

    public function __construct(Suscripciones $model, SecureTokenService $secureToken)
    {
        $this->model = $model;
        $this->secureToken = $secureToken;

        $this->data = ['pageTitle'	=> 	"Suscripciones",
                        'pageNote'	    =>  "Lista de suscripciones",
                        'pageModule'    => $this->module
                    ];
    }
    public function index(Request $request)
    {
        $nopage = $request->integer('nopagina', static::$perpage);
        $page = $request->integer('page', 1);
        $name = $request->input('name', '');

        $idyear = 1;

        $request['nopagina'] = $nopage;
        $request['name'] = $name;

        $rows =  $this->model->listData($request->all());

       $rows->getCollection()->transform(function ($row) use ($idyear) {
            return [
                'id'            => $row->id,
                'nombre'        => $row->nombre,
                'nivel'         => $row->nivel,
                'plan'          => $row->plan,
                'rowsPagos'     => $this->dataPagosAlumno($row->id, $idyear),
            ];
        });
        $this->data['meses']= [1 =>'Enero',2 =>'Febrero',3 => 'Marzo',4 => 'Abril',5 => 'Mayo',6 => 'Junio',7 => 'Julio',8 => 'Agosto',9 => 'Septiembre',10 => 'Octubre',11 => 'Noviembre',12 => 'Diciembre'];
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        $this->data['year'] = 2025;
        $this->data['idyear'] = 1;

        return view($this->module.'.index',$this->data);
    }
    private function dataPagosAlumno($id, $idyear){
		$meses = [];
		foreach ($this->model->pagosPorMes($id, $idyear) as $r) {
			$meses[$r->idmes] = ['id' => $r->id, 'idtipo_pago' => $r->idtipo_pago];
		}
		return $meses;
    }
    public function view(Request $request): View
    {
        $row = $this->model->nadadorID($request->id);
        if($row){
            $rows = $this->model->suscripcionesNadador($request->id);
            //Cuando la consulta no es una coleccion se trata como map
            $rows = $rows->map(function ($v) {
                return [
                    'id'          => $v->id,
                    'plan'      => $v->plan,
                    'fi'          => $v->fi,
                    'ff'          => $v->ff,
                    'pago'        => $v->pago,
                    'monto'       => $v->monto,
                    'estado'      => $v->estado,
                    'max_visitas' => $v->max_visitas_mes,
                    'rows_fechas' => $this->model->listRegistros($v->id),
                ];
            });

            
            $tieneActiva = $rows->contains(function ($item) {
                return $item['estado'] === 'ACTIVA';
            });

            $this->data['id'] = $request->id;
            $this->data['tieneActiva'] = $tieneActiva;
            $this->data['rowsSuscripciones'] = $rows;
            $this->data['row'] = $row;

            //$month   = $request->integer('month', now()->month);
            //$year    = $request->integer('year',  now()->year);
            $month   = $request->idm;
            $years = Year::find($request->idy);
            $year = $years->numero;
            // Horarios existentes por día de la semana (1=Lunes ... 7=Domingo)
            $horarios = $this->model->horariosNiveles($row->idniveles);
            // Rango que cubre todas las semanas del mes
            $firstOfMonth = Carbon::create($year, $month, 1);
            $start = $firstOfMonth->copy()->startOfWeek(Carbon::MONDAY);
            $end   = $firstOfMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

            $days = [];
            $current = $start->copy();

            while ($current <= $end) {
                $isoDow = $current->dayOfWeekIso; // 1..7
                if($horarios->get($isoDow)){
                    $collectHorarios = $this->calcularDisponibilidad($horarios->get($isoDow), $current->toDateString(), $row->aforo_maximo);
                }else{
                    $collectHorarios = collect();
                }

                $days[] = [
                    'date'          => $current->copy(),
                    'in_month'      => $current->month == $month,
                    'horarios'      => $collectHorarios,
                    'aforo_maximo'  => $row->aforo_maximo
                ];

                $current->addDay();
            }
            // Partimos en semanas de 7 días
            $weeks = collect($days)->chunk(7);
            $this->data['weeks'] = $weeks;
            $this->data['year'] = $year;
            $this->data['month'] = $month;
            $this->data['id'] = $request->id;
            $this->data['idy'] = $request->idy;
            $this->data['idm'] = $request->idm;
            $this->data['mes'] = $this->nombreMes($request->idm);
        
            return view($this->module.'.view',$this->data);
        }
    }
    private function calcularDisponibilidad($data, $fecha, $aforo_maximo){
         return $data->map(function ($v) use ($fecha, $aforo_maximo) {
                $ocupados = $this->model->totalReservasPorFecha($v->idplan_horario,$fecha);
                $disponibles = max($aforo_maximo - $ocupados, 0);
                return [
                    'idplan_horario' => $v->idplan_horario,
                    'dia_semana'     => $v->dia_semana,
                    'time_start'     => $v->time_start,
                    'ocupados'       => $ocupados,
                    'disponibles'    => $disponibles,
                    'fecha'          => $fecha,
                ];
            });
    }
    private function nombreMes($idm){
        return ucfirst(Carbon::create(null, $idm, 1)->locale('es')->monthName);
    }
    public function horario(Request $request, $id): View
    {
        $suscripcion = $this->model->find($request->ids);
        // Rango de fechas
        $period = CarbonPeriod::create($suscripcion->fecha_inicio, $suscripcion->fecha_fin);
        // Lista de días
        $dias = collect();

        foreach ($period as $date) {
            $no_dia_semana = $date->dayOfWeekIso;
            $fecha = $date->toDateString();
            //Convertimos a collection
            $rows_horario = collect($this->dataHorarioPlan($suscripcion->idplan, $no_dia_semana, $fecha));
            //Validar que la fecha contega registros la coleccion
            if ($rows_horario->isNotEmpty()) {
                $dias->push([
                    'fecha' => $fecha,              // 2025-11-22
                    'dia_nombre' => $date->translatedFormat('l'),  // sábado, domingo, etc.
                    //'dia_corto'  => $date->translatedFormat('D'),  // sáb, dom, etc.
                    'dia_numero' => $date->dayOfWeekIso,
                    'rows_horario' => $rows_horario
                ]);
            }
        }   
        $this->data['id'] = $id;
        $this->data['ids'] = $request->ids;
        $this->data['max_visitas_mes'] = $suscripcion->max_visitas_mes;
        $this->data['rowsHorarios'] = $dias;
        return view($this->module.'.horarios',$this->data);
    }

    public function link(Request $request): View
    {
        $this->data['id'] = $request->id;
        $this->data['link_horario'] = config('app.url')."/acceso/horario?token=".$this->secureToken->encode([ 'ids' => $request->ids, 'time' => time() ]);
        return view($this->module.'.link',$this->data);
    }
    public function ordenpago(Request $request): View
    {
        $this->data['ids'] = $request->ids;
        $this->data['folio'] = $this->folio($request->ids);
        $this->data['row'] = $this->model->suscripcionDataID($request->ids);
        return view($this->module.'.ordenpago',$this->data);
    }
     public function detail(Request $request): View
    {
        $this->data['ids'] = $request->ids;
        $this->data['folio'] = $this->folio($request->ids);
        $this->data['row'] = $this->model->suscripcionDataID($request->ids);
        $this->data['rowsFechas'] = $this->model->listRegistros($request->ids);
        return view($this->module.'.detail',$this->data);
    }
    public function pdf(Request $request)
    {
        $this->data['j'] = 1;
        $this->data['ids'] = $request->ids;
        $this->data['folio'] = $this->folio($request->ids);
        $this->data['row'] = $this->model->suscripcionDataID($request->ids);
        $this->data['rows'] = $this->model->listHorarioSuscripcion($request->ids);
        $pdf = Pdf::loadView($this->module.'.pdf', $this->data)
                ->setPaper('letter', 'portrait');;
        // Descargar el PDF
        return $pdf->stream($this->module.'.pdf');
    }
    function ceros_left($numero, $longitud) {
        return str_pad($numero, $longitud, '0', STR_PAD_LEFT);
    }
    function folio($ids){
        return 'VB'.$this->ceros_left($ids,11);
    }
    private function dataHorarioPlan($idplan, $dias_semana, $fecha){
        $data = [];
        foreach ($this->model->listHorarioPlan($idplan, $dias_semana) as $v) {
           $ocupados = $this->model->totalReservasPorFecha($v->id, $fecha);
           $disponibles = max($v->aforo_maximo - $ocupados,0);
           $data[] = ['id'          => $v->id,
                    'dia_semana'    => $v->dia_semana,
                    'aforo_maximo'  => $v->aforo_maximo,
                    'time_start'    => $v->time_start,
                    'time_end'      => $v->time_end,
                    'ocupados'      => $ocupados,
                    'disponibles'   => $disponibles,
                ];
        }
        return $data;
    }
    public function temporal(Request $request)
    {
        if (empty($request->idplan_horario)) {
            return back()
                    ->withErrors('No seleccionaste ningún horario. Selecciona horarios para completar tu registro.');
        }
        $row = $this->model->nadadorID($request->id);
        //$suscripcion = $this->model->find($request->ids);

        $total = count($request->idplan_horario);

        if ($total > $row->max_visitas_mes) {
           return back()
                    ->withErrors('Has superado el número de visitas permitido por tu plan. Selecciona en total '. $row->max_visitas_mes . ' horarios para completar tu registro.');
        }else if($total < $row->max_visitas_mes){
            return back()
                    ->withErrors(["Seleccionaste {$total} horarios, pero tu plan solo permite {$row->max_visitas_mes} visitas."]);
        }
        // ID que agrupa esta selección de días
        $time = time();
        $fechas = collect($request->idplan_horario)->map(function ($json) {
            return json_decode($json, true); // array con id y fecha
        });

        foreach ($fechas as $fecha) {
            Reservatemporal::create(
                [
                    'time'      => $time,
                    'idnadador' => $request->id,
                    'fecha'     => $fecha['fecha'],
                    'idplan_horario' => $fecha['idplan_horario']
                ]
            );
        }
        return redirect()
            ->route($this->module.'.pagar', ['id' => $request->id, 'idm' => $request->idm, 'idy' => $request->idy, 'time' => $time])
            ->with('messagetext','Horario seleccionado correctamente, procede a realziar el pago.')
            ->with('msgstatus','success');
    }
    public function pagar(Request $request)
    {
        $row = $this->model->nadadorID($request->id);
        $this->data['row'] = $row;
        $this->data['rowsFechas'] = $this->model->fechasTemporales($request->id, $request->time);
        $this->data['rowsDescuento'] = $this->model->descuentos();
        $this->data['id'] = $request->id;
        $this->data['idm'] = $request->idm;
        $this->data['idy'] = $request->idy;
        $this->data['time'] = $request->time;
        $this->data['mes'] = $this->nombreMes($request->idm);
        return view($this->module.'.pagar',$this->data);
    }
    private function calculoDescuento($precio, $descuento){
        return ($precio * ($descuento / 100));
    }
    public function ticket(Request $request){
        $row = $this->model->nadadorID($request->id);
        
        $descuento = Descuento::find($request->iddescuento);
        $fecha = Carbon::now()->toDateString(); 

        $total_descuento = 0;

        if($descuento->descuento > 0){
            $total_descuento = $this->calculoDescuento($row->precio,$descuento->descuento);
        }

        $total = $row->precio - $total_descuento;

        $suscripcion = $this->model->create([
                        'idnadador'             => $request->id,
                        'idplan'                => $row->idplan,
                        'idyear'                => $request->idy,
                        'idmes'                 => $request->idm,
                        'fecha_pago'            => $fecha,
                        'hora_pago'             => Carbon::now()->format('H:i:s'),
                        'fecha_inicio'          => $fecha,
                        'fecha_fin'             => $fecha,
                        'active'                => 1,
                        'idtipo_pago'           => $request->idtipo_pago,
                        'max_visitas_mes'       => $row->max_visitas_mes,
                        'monto_general'         => $row->precio,
                        'monto_pagado'          => $total,
                        'descuento'             => $total_descuento,
                        'desc_descuento'        => $descuento->descripcion,
                        'porc_descuento'        => $descuento->descuento,
                    ]);
        foreach ($this->model->fechasTemporales($request->id, $request->time) as $v) {
            Reserva::create(
                [
                    'idsuscripcion'  => $suscripcion->idsuscripcion,
                    'idplan_horario' => $v->idplan_horario,
                    'fecha'          => $v->fecha,
                    'active'         => 1
                ]
            );
        }
        return redirect()
            ->route($this->module.'.ordenpago', ['ids' => $suscripcion->idsuscripcion])
            ->with('messagetext','Suscripción realizada correctamente.')
            ->with('msgstatus','success');
    }
    public function update(Request $request, $id = 0)
    {
        
        $suscripcion = $this->model->find($request->ids);

        if (empty($request->idplan_horario)) {
            return back()
                    ->withErrors('No seleccionaste ningún horario. Selecciona en total ' . $suscripcion->max_visitas_mes . ' horarios para completar tu registro.');
        }

        $total = count($request->idplan_horario);

        if ($total > $suscripcion->max_visitas_mes) {
           return back()
                    ->withErrors('Has superado el número de visitas permitido por tu plan. Selecciona en total '. $suscripcion->max_visitas_mes . ' horarios para completar tu registro.');
        }else if($total < $suscripcion->max_visitas_mes){
            return back()
                    ->withErrors(["Seleccionaste {$total} horarios, pero tu plan solo permite {$suscripcion->max_visitas_mes} visitas."]);
        }

        $fechas = collect($request->idplan_horario)->map(function ($json) {
            return json_decode($json, true);
        });

        foreach ($fechas as $fecha) {
            Reserva::create(
                [
                    'idsuscripcion'  => $request->ids,
                    'idplan_horario' => $fecha['id'],
                    'fecha'          => $fecha['fecha'],
                    'active'         => 1
                ]
            );
        }

        //Actualizo el campo activo a 2 que es con horario asignado
        $row = $this->model->find($request->ids);
        if($row){
            $row->update(['active' => 2]);
        }
        return redirect()
            ->route($this->module.'.view', $id)
            ->with('messagetext','Información guardada correctamente')
            ->with('msgstatus','success');
    }
    /* public function store(Request $request)
    {
        $row = Suscripciones::nadadorID($request->id);
        if (!$row) {
            return back()->withErrors('ID de nadador no encontrado!');
        }

        //Se crea la suscripción 
        $base = now();      // ya con timezone corregido
        $fecha_inicio = $base->toDateString();
        $fecha_fin    = $base->copy()->addDays($row->duracion_dias)->toDateString();

        $rowSuscripcion = $this->model->create([
                        'idnadador'              => $request->id,
                        'idplan'                 => $row->idplan,
                        'fecha_inicio'           => $fecha_inicio,
                        'fecha_fin'              => $fecha_fin,
                        'active'                 => 1,
                        'idtipo_pago'            => $request->idtipo_pago,
                        'monto'                  => $row->precio,
                        'max_visitas_mes'        => $row->max_visitas_mes
                    ]);

        return redirect()
            ->route($this->module.'.view', $request->id)
            ->with('messagetext','Suscripción asignada correctamente')
            ->with('msgstatus','success');
    }
      public function create(Request $request, $id = 0): View
    {
        $row = $this->model->nadadorID($id);
        if($row){
            $this->data['id'] = $id;
            $this->data['row'] = $row;
            return view($this->module.'.create',$this->data);

        }
    }*/
}
