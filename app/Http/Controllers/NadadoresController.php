<?php

namespace App\Http\Controllers;

use App\Models\Nadadores;
use App\Models\Nivel;
use App\Models\Niveles;

use Illuminate\View\View;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

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
        $idplan = $request->integer('idplan', 0);
        $idnivel = $request->integer('idnivel', 0);
        $name = $request->input('name', '');

        $request['nopagina'] = $nopage;
        $request['name'] = $name;
        $request['idplan'] = $idplan;
        $request['idnivel'] = $idnivel;

        $rows =  $this->model->listData($request->all());
        /*$rows->getCollection()->transform(function ($row) {
            return [
                'id'            => $row->id,
                'titular'       => $row->titular,
                'rowsNadadores' => $this->model->listaNadadores($row->id),
            ];
        });*/
        $this->data['rowsPlan'] = $this->model->catalogoPlan();
        $this->data['rowsNiveles'] = Nivel::all();
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function create(Request $request): View
    {
        $this->data['rowsGenero'] = $this->model->catalogoGenero();
        $this->data['rowsParentesco'] = $this->model->catalogoParentesco();
        $this->data['rowsPlan'] = $this->model->catalogoPlan();
        $this->data['rowsDescuentos'] = $this->model->catalogoDescuentos();
        return view($this->module.'.create',$this->data);
    }
    public function edit(Request $request): View
    {
        $row = $this->model->find($request->id);
        $this->data['rowsGenero'] = $this->model->catalogoGenero();
        $this->data['rowsParentesco'] = $this->model->catalogoParentesco();
        $this->data['rowsPlan'] = $this->model->catalogoPlan();
        $this->data['id'] = $request->id;
        $this->data['row'] = $row;
        $this->data['rowsDescuentos'] = $this->model->catalogoDescuentos();
        $nivel = "";
        if($row->idniveles != 0){
            $nivel = Nivel::find($row->idniveles);
            $this->data['nivel'] = $nivel->descripcion;
          //  $this->data['rowsPlan'] = $this->model->catalogoPlanNivel($row->idniveles);
        }else{
            $this->data['nivel'] = '';
        }
            $this->data['rowsPlan'] = $this->model->catalogoPlan();
        return view($this->module.'.edit',$this->data);
    }
    public function store(Request $request)
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
            'idparentesco'       => 'required',
            'telefono_emergencia'  => 'required',
        ]);

        $validated['active'] = 1;
        $validated['nombre'] = strtoupper($request->nombre);
        $validated['curp'] = strtoupper($request->curp);
        $validated['comentarios'] = $request->comentarios;
        $validated['titular_domicilio'] = '.';
        $validated['iddescuento'] = $request->iddescuento;

        $plan = Niveles::find($validated['idplan']);
        $validated['idniveles'] = $plan['idniveles'];

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
    public function update(Request $request)
    {
        /* $validated = $request->validate([
            'nombre'            => 'required',
            'fecha_nacimiento'  => 'required',
            'edad'              => 'required|integer|max:99',
            'idgenero'          => 'required',
            'domicilio'         => 'required',
            'idplan'            => 'required',
            'titular_nombre'     => 'required',
            'titular_telefono'   => 'required',
            'titular_email'      => 'required',
            'idparentesco'       => 'required',
            'telefono_emergencia'  => 'required',
        ]);*/
        $validated = $request->validate([
            'nombre'            => 'required',
            'idgenero'          => 'required',
            'idplan'            => 'required',
            'idparentesco'       => 'required',
        ]);

        $validated['nombre'] = strtoupper($request->nombre);
        $validated['comentarios'] = $request->comentarios;
        $validated['telefono_emergencia'] = $request->telefono_emergencia;
        $validated['curp'] = strtoupper($request->curp);
        $validated['iddescuento'] = $request->iddescuento;

        $plan = Niveles::find($validated['idplan']);
        $validated['idniveles'] = $plan['idniveles'];

        $row = $this->model->find($request->id);
        if($row){
            $row->update($validated);
        }

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','Nadador editado exitosamente')
            ->with('msgstatus','success');

    }
    public function upload(Request $request)
    {
        // Validar que venga un archivo y que sea Excel / CSV
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('archivo');

        // Cargar el archivo con PhpSpreadsheet
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true); // A, B, C, etc.

        // Ejemplo: mostrar lo leído
        // dd($rows);

        // Ejemplo: recorrer filas (saltando encabezado)
        foreach ($rows as $index => $row) {
            if ($index === 1) {
                // Suponiendo que la fila 1 es encabezado
                continue;
            }
                 
            if($row['I'] == 'Femenino'){
                $genero = 2;
            }elseif($row['I'] == 'Masculino'){
                $genero = 1;
            }else{
                $genero = 0;
            }

            if($row['K'] == 'Nado Libre Adulto'){
                $idniveles = 1;
            }elseif($row['K'] == 'Básico Adultos'){
                $idniveles = 2;
            }elseif($row['K'] == 'Intermedio Adultos'){
                $idniveles = 3;
            }elseif($row['K'] == 'Avanzado Adultos'){
                $idniveles = 4;
            }elseif($row['K'] == 'Básico Niños'){
                $idniveles = 5;
            }elseif($row['K'] == 'Intermedio Niños'){
                $idniveles = 6;
            }elseif($row['K'] == 'Avanzado Niños'){
                $idniveles = 7;
            }elseif($row['K'] == 'Chapoteadero'){
                $idniveles = 8;
            }elseif($row['K'] == 'ESDEP'){
                $idniveles = 9;
            }elseif($row['K'] == 'Marina'){
                $idniveles = 10;
            }elseif($row['K'] == 'Mauricio Jackson'){
                $idniveles = 11;
            }elseif($row['K'] == 'Jóvenes 18:00-19:00PM'){
                $idniveles = 12;
            }else{
                $idniveles = 0;
            }

            // Si viene vacía, devuelve null o lo que quieras
            $fechaFormateada = empty(trim($row['G'])) ? null : Carbon::createFromFormat('d/m/Y', $row['G'])->format('Y-m-d');


            $validated['active'] = 1;
            $validated['nombre'] = strtoupper($row['B']);
            $validated['fecha_nacimiento'] = $fechaFormateada;
            $validated['edad'] = (int) trim($row['H']);
            $validated['idgenero'] = $genero;
            $validated['domicilio'] = $row['F'];
            $validated['idplan'] = 0;
            $validated['idniveles'] = $idniveles ;
            $validated['titular_nombre'] = $row['D'];
            $validated['titular_telefono'] = $row['E'];
            $validated['titular_email'] = $row['L'];
            $validated['idparentesco'] = 6;
            $validated['telefono_emergencia'] = $row['C'];
            $validated['curp'] = '';
            $validated['comentarios'] = $row['J'];
            $validated['titular_domicilio'] = '';
        $this->model->create($validated);
        }

        return back()->with('success', 'Archivo procesado correctamente.');
    }
}
