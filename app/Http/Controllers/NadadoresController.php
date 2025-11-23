<?php

namespace App\Http\Controllers;

use App\Models\Nadadores;

use Illuminate\View\View;
use Illuminate\Http\Request;

class NadadoresController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'nadadores';
    public static int $perpage = 10;

    public function __construct(Nadadores $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Nadadores",
                        'pageNote'	    =>  "Lista de nadadores",
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
        /*$rows->getCollection()->transform(function ($row) {
            return [
                'id'            => $row->id,
                'titular'       => $row->titular,
                'rowsNadadores' => $this->model->listaNadadores($row->id),
            ];
        });*/

		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function create(Request $request): View
    {
        $this->data['rowsGenero'] = $this->model->catalogoGenero();
        $this->data['rowsParentesco'] = $this->model->catalogoParentesco();
        $this->data['rowsPlan'] = $this->model->catalogoPlan();
        return view($this->module.'.create',$this->data);
    }
    public function store(Request $request, $id = 0)
    {
        $validated = $request->validate([
            'nombre'            => 'required',
            'fecha_nacimiento'  => 'required',
            'edad'              => 'required|integer|max:99',
            'idgenero'          => 'required',
            'domicilio'         => 'required',
            'curp'              => 'required|size:18',
            'idplan'            => 'required',
            'titular_nombre'     => 'required',
            'titular_telefono'   => 'required',
            'titular_email'      => 'required',
            'titular_domicilio'  => 'required',
            'idparentesco'       => 'required',
        ]);

        $validated['active'] = 1;
        $validated['nombre'] = strtoupper($request->nombre);
        $validated['curp'] = strtoupper($request->curp);

        $existe = $this->model->where('curp', $validated['curp'])->exists();
        if($existe){
             return back()
                        ->withErrors("El nadador con la CURP {$validated['curp']} ya se encuentra registrado en el sistema.")
                        ->withInput();;
        }

        $this->model->create($validated);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','Nadador registrado exitosamente')
            ->with('msgstatus','success');
    }
}
