<?php

namespace App\Http\Controllers;

use App\Models\Niveles;
use App\Models\Planhorario;

use Illuminate\Http\Request;
use Illuminate\View\View;

use Carbon\Carbon;

class NivelesController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'niveles';
    public static int $perpage = 10;

    public function __construct(Niveles $model)
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
    public function create(Request $request): View
    {
        return view($this->module.'.create',$this->data);
    }
    public function horarios($id = 0): View
    {
        $this->data['id'] = $id;
        $data = [];
        foreach ($this->model->listHorarioPlan($id) as $v) {
           $data[$v->dia_semana][] = ['start' => $v->time_start,'end' => $v->time_end,'max' => $v->aforo_maximo ];
        }
        $this->data['rowsHorario'] = $data;
        return view($this->module.'.horarios',$this->data);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:255',
            'precio'           => 'required|numeric',
            'duracion_dias'    => 'required|integer',
            'max_visitas_mes'  => 'required|integer',
        ]);

        $data['active'] = 1;

        $plan = Niveles::create($data);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','El plan se registró correctamente.')
            ->with('msgstatus','success');

    }
    public function update(Request $request, $id = 0)
    {
        $minutos = (int) $request->tiempo;

        $inicio = Carbon::parse($request->time_start);
        $fin = $inicio->copy()->addMinutes($minutos);

        $data = [
                    'idplan'        => $id,
                    'dia_semana'    => $request->dia_semana,
                    'aforo_maximo'  => $request->aforo_maximo,
                    'time_start'    => $inicio->format('H:i:s'),
                    'time_end'      => $fin->format('H:i:s'),
                ];
                
        Planhorario::create($data);

       /* $data = $request->validate([
                'dia_semana' => 'required|integer|min:1|max:7',
                'aforo_maximo'      => 'required|integer|min:1|max:100', // según tu negocio
                'time_start' => 'required|date_format:H:i:s',
                'tiempo'     => 'required|integer|min:30|max:300', // minutos
            ], [
                'dia_semana.required' => 'Selecciona un día de la semana.',
                'dia_semana.integer'  => 'El día debe ser un número.',
                'aforo.required'      => 'Ingresa el aforo máximo.',
                'time_start.required' => 'Debes ingresar una hora de inicio.',
                'time_start.date_format' => 'La hora debe tener el formato HH:MM:SS.',
                'tiempo.required'     => 'Debes ingresar el tiempo en minutos.',
            ]);*/

         return redirect()
            ->route($this->module.'.horarios', $id)
            ->with('messagetext','El plan se registró correctamente.')
            ->with('msgstatus','success');

       
   
    }
    
}
