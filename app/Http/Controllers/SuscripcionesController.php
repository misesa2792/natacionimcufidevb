<?php

namespace App\Http\Controllers;

use App\Models\Suscripciones;
use App\Models\Reserva;

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

        $request['nopagina'] = $nopage;
        $request['name'] = $name;

        $rows =  $this->model->listData($request->all());

        $rows->getCollection()->transform(function ($row) {
            return [
                'id'            => $row->id,
                'active'        => $row->active,
                'nombre'        => $row->nombre,
                'plan'          => $row->plan,
                'suscripcion'   => $this->model->suscripcionID($row->id),
            ];
        });
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function view(Request $request, $id = 0): View
    {
        $row = $this->model->nadadorID($id);
        if($row){
            $rows = $this->model->suscripcionesNadador($id);
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

            $this->data['id'] = $id;
            $this->data['tieneActiva'] = $tieneActiva;
            $this->data['rowsSuscripciones'] = $rows;
            $this->data['row'] = $row;
        
            return view($this->module.'.view',$this->data);
        }
    }
    public function create(Request $request, $id = 0): View
    {
        $row = $this->model->nadadorID($id);
        if($row){
            $this->data['id'] = $id;
            $this->data['row'] = $row;
            return view($this->module.'.create',$this->data);

        }
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
    public function pdf(Request $request)
    {
        $this->data['j'] = 1;
        $this->data['id'] = $request->ids;
        $this->data['folio'] = 'VB'.$this->ceros_left($request->ids,11);
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
    public function store(Request $request)
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
}
