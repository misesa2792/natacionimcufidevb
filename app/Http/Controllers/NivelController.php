<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;
use App\Models\Planhorario;
use Carbon\Carbon;

class NivelController extends Controller
{
     protected $data = [];	
    protected $model;	
	public $module = 'nivel';
    public static int $perpage = 25;

    public function __construct(Nivel $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Niveles",
                        'pageNote'	    =>  "Lista de niveles",
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

		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function create(Request $request)
    {
        return view($this->module.'.create',$this->data);
    }
    public function edit(Request $request)
    {
        $this->data['row'] = $this->model->find($request->id);
        $this->data['id'] = $request->id;
        return view($this->module.'.edit',$this->data);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'descripcion' => 'required|max:45',
            'aforo_maximo' => 'required'
        ]);
        $data['active'] = 1;

        $this->model->create($data);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','El nivel se registró correctamente.')
            ->with('msgstatus','success');
    }
    public function guardar(Request $request)
    {
        $data = $request->validate([
            'descripcion'  => 'required|max:45',
            'aforo_maximo' => 'required'
        ]);

        $row =  $this->model->find($request->id);
        
        if($row){
            $row->update($data);
        }

        return redirect()
            ->route($this->module.'.index', [ 'page' => $request->page])
            ->with('messagetext','El nivel se actualizó correctamente.')
            ->with('msgstatus','success');

    }
    public function horarios(Request $request)
    {
        $this->data['id'] = $request->id;


        $registros = \DB::table('ses_plan_horario')
        ->select('idplan_horario','dia_semana', 'time_start as horario')
        ->where('idniveles', $request->id)
        ->orderBy('time_start')
        ->get();

        // Construimos una matriz [hora][dia] = true
        $matrix = [];

        foreach ($registros as $r) {
            $hora = $r->horario;           // 07:00:00
            $dia  = (int) $r->dia_semana;  // 1..7

            if (! isset($matrix[$hora])) {
                $matrix[$hora] = [];
            }

            $matrix[$hora][$dia] = $r->idplan_horario;
        }
        // Opcional: ordenar por hora (por si acaso)
        ksort($matrix);

        $this->data['diasNombres'] = [
                                        1 => 'Lunes',
                                        2 => 'Martes',
                                        3 => 'Miércoles',
                                        4 => 'Jueves',
                                        5 => 'Viernes',
                                        6 => 'Sábado',
                                        7 => 'Domingo',
                                    ];

        $data = [];
        foreach ($this->model->listHorarioPlan($request->id) as $v) {
           $data[$v->dia_semana][] = ['start' => $v->time_start,'end' => $v->time_end];
        }
        $this->data['rowsHorario'] = $data;
        $this->data['matrix'] = $matrix;
        return view($this->module.'.horarios',$this->data);
    }
    public function update(Request $request)
    {
        if(!isset($request->horarios)){
            return back()->withErrors('Selecciona días.');
        }

        $inicio = Carbon::parse($request->tiempo);
        $fin = $inicio->copy()->addMinutes(60);

        foreach ($request->horarios as $dia_semana => $v) {
            $data = [
                    'idniveles'     => $request->id,
                    'dia_semana'    => $dia_semana,
                    'time_start'    => $inicio->format('H:i:s'),
                    'time_end'      => $fin->format('H:i:s'),
                ];
            Planhorario::create($data);
        }
        return redirect()
            ->route($this->module.'.horarios', ['id' => $request->id])
            ->with('messagetext','El plan se registró correctamente.')
            ->with('msgstatus','success');
    }
}
