<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
class UsuariosController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'usuarios';
    public static int $perpage = 10;

    public function __construct(User $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Usuarios",
                        'pageNote'	    =>  "Lista de usuarios",
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
        $this->data['rowsNivel'] = $this->model->listNiveles();
        return view($this->module.'.create',$this->data);
    }
    public function edit(Request $request, $id = 0): View
    {
        $row = $this->model->find($id,['name','email','idnivel']);
        if($row){
            $this->data['rowsNivel'] = $this->model->listNiveles();
            $this->data['id'] = $id;
            $this->data['row'] = $row;
            return view($this->module.'.edit',$this->data);
        }
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required',
            'idnivel' => 'required',
        ]);

        $validated['active'] = 1;
        $validated['idnivel'] = 3;
        $validated['name'] = strtoupper($request->name);
        $validated['password'] = Hash::make($request->password);
        $this->model->create($validated);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','Información guardada correctamente')
            ->with('msgstatus','success');
    }
    public function update(Request $request,$id = 0)
    {
        $validated = $request->validate([
            'name' => 'required',
            'idnivel' => 'required',
        ]);

        $validated['name'] = strtoupper($request->name);

        if(!empty($request->password)){
            $validated['password'] = Hash::make($request->password);
        }
        $row = $this->model->find($id);
        if($row){
            $row->update($validated);
        }

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','Información guardada correctamente')
            ->with('msgstatus','success');
    }
}
