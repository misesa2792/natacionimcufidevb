<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Nadadores extends Model
{
	protected $table = 'ses_nadador';
    protected $primaryKey = 'idnadador'; 

    protected $fillable = [
        'active',
        'idplan',
        'idniveles',
        'nombre',
        'curp',
        'fecha_nacimiento',
        'idgenero',
        'domicilio',
        'edad',
        'titular_nombre',
        'titular_telefono',
        'titular_email',
        'titular_domicilio',
        'idparentesco',
        'comentarios',
        'telefono_emergencia'
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

        $query = DB::table('ses_nadador as n')
            ->leftjoin('ses_genero as g', 'g.idgenero', '=', 'n.idgenero')
            ->leftjoin('ses_plan as pa', 'pa.idplan', '=', 'n.idplan')
            ->leftjoin('ses_niveles as ni', 'ni.idniveles', '=', 'n.idniveles')
            ->select([
                'n.idnadador as id',
                'n.active',
                'pa.nombre as plan',
                'n.nombre as nadador',
                'n.curp',
                'n.fecha_nacimiento',
                'g.descripcion as genero',
                'n.domicilio',
                'n.edad',
                'ni.descripcion as nivel'
            ])
            ->orderBy('n.idnadador', 'asc');

        if (!empty($request['name']) && trim($request['name']) !== '') {
			$query->where('n.nombre', 'like', '%'.trim($request['name']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
    public static function catalogoGenero()
	{
        return DB::table('ses_genero')
            ->select([
                'idgenero as id',
                'descripcion as genero',
            ])
            ->get();
	}
    public static function catalogoPlan()
	{
        return DB::table('ses_plan as p')
            ->join('ses_niveles as s', 's.idniveles', '=', 'p.idniveles')
            ->select([
                'p.idplan as id',
                'p.nombre as plan',
                'p.precio',
                's.descripcion as nivel',
                'p.max_visitas_mes',
            ])
            ->get();
	}
    public static function catalogoParentesco()
	{
        return DB::table('ses_parentesco')
            ->select([
                'idparentesco as id',
                'descripcion as parentesco',
            ])
            ->get();
	}
}
