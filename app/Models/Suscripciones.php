<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Suscripciones extends Model
{
    
	protected $table = 'ses_suscripcion';
    protected $primaryKey = 'idsuscripcion'; 

	protected $fillable = [
        'idnadador',
        'idplan',
        'fecha_inicio',
        'fecha_fin',
        'active',
        'idtipo_pago',
        'monto',
        'max_visitas_mes'
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('ses_nadador as n')
			->join('ses_plan as p', 'p.idplan', '=', 'n.idplan')
			->select([
				'n.idnadador as id',
				'n.active',
				'n.nombre',
				'n.curp',
				'p.nombre as plan'
			])
			->orderBy('n.nombre', 'asc');
		// Filtros opcionales (ejemplo)
		/*
		if (!empty($request['nombre']) && trim($request['nombre']) !== '') {
			$query->where('f.descripcion', 'like', '%'.trim($request['nombre']).'%');
		}
		*/
		return $query->paginate($perPage)->appends($request);
	}
	public static function suscripcionID($id)
	{
		return DB::table('ses_suscripcion as s')
		->join('ses_plan as p', 'p.idplan', '=', 's.idplan')
		->join('ses_tipo_pago as tp', 'tp.idtipo_pago', '=', 's.idtipo_pago')
		->select([
			's.idsuscripcion as id',
			's.fecha_inicio as fi',
			's.fecha_fin as ff',
			's.monto',
			'p.nombre as plan',
			'tp.descripcion as pago',
			's.max_visitas_mes',
			DB::raw("
				CASE 
					WHEN CURDATE() BETWEEN s.fecha_inicio AND s.fecha_fin 
						THEN 'ACTIVA'
					ELSE 'VENCIDA'
				END AS estado
			"),
		])
		->where('s.idnadador', $id)
		->orderBy('s.idsuscripcion', 'DESC')
		->limit(1)
		->first();
	}
	public static function suscripcionesNadador($id, $limit = 3)
	{
		return DB::table('ses_suscripcion as s')
			->join('ses_plan as p', 'p.idplan', '=', 's.idplan')
			->join('ses_tipo_pago as tp', 'tp.idtipo_pago', '=', 's.idtipo_pago')
			->select([
				's.idsuscripcion as id',
				'p.nombre as plan',
				's.fecha_inicio as fi',
				's.fecha_fin as ff',
				'tp.descripcion as pago',
				's.monto',
				's.max_visitas_mes',
				DB::raw("
					CASE 
						WHEN CURDATE() BETWEEN s.fecha_inicio AND s.fecha_fin 
							THEN 'ACTIVA'
						ELSE 'VENCIDA'
					END AS estado
				"),
			])
			->where('s.idnadador', $id)
			->orderBy('s.idsuscripcion', 'desc')
			->limit($limit)
			->get();
	}
	public static function nadadorID($id)
	{
		return DB::table('ses_nadador as n')
				->join('ses_plan as pl', 'pl.idplan', '=', 'n.idplan')
				->join('ses_genero as g', 'g.idgenero', '=', 'n.idgenero')
				->join('ses_parentesco as pa', 'pa.idparentesco', '=', 'n.idparentesco')
				->select([
					'n.active',
					'n.nombre',
					'n.curp',
					'n.fecha_nacimiento',
					'g.descripcion as genero',
					'n.domicilio',
					'n.edad',
					'pl.nombre as plan',
					'pl.idplan',
					'pl.precio',
					'pl.duracion_dias',
					'pl.max_visitas_mes',
					'n.titular_nombre',
					'n.titular_email',
					'n.titular_telefono',
					'n.titular_domicilio',
					'pa.descripcion as parentesco',
				])
				->where('n.idnadador', $id)
				->first();
	}
	public static function nadadorSearchCurpID($curp)
	{
		return DB::table('ses_nadador as n')
				->join('ses_plan as pl', 'pl.idplan', '=', 'n.idplan')
				->join('ses_genero as g', 'g.idgenero', '=', 'n.idgenero')
				->join('ses_parentesco as pa', 'pa.idparentesco', '=', 'n.idparentesco')
				->select([
					'n.idnadador as id',
					'n.active',
					'n.nombre',
					'n.curp',
					'n.fecha_nacimiento',
					'g.descripcion as genero',
					'n.domicilio',
					'n.edad',
					'pl.nombre as plan',
					'pl.idplan',
					'pl.precio',
					'pl.duracion_dias',
					'pl.max_visitas_mes',
					'n.titular_nombre',
					'n.titular_email',
					'n.titular_telefono',
					'n.titular_domicilio',
					'pa.descripcion as parentesco',
				])
				->where('n.curp', $curp)
				->first();
	}
	public static function dataPlanes()
	{
		return DB::table('ses_plan')
			->select([
				'idplan as id',
				'nombre as plan',
				'precio'
			])
			->get();
	}
	public static function totalReservasPorFecha($id, $fecha)
	{
		return DB::table('ses_reserva')
            ->where('idplan_horario', $id)
            ->where('fecha', $fecha)
            ->count();
	}
	public static function validateSubscriptionActive($id)
	{
		return  DB::table('ses_suscripcion')
				->where('idnadador', $id)
				->whereDate('fecha_inicio', '<=', now())
				->whereDate('fecha_fin', '>=', now())
				->exists();
	}
	public static function listHorarioPlan($id, $dia_semana)
	{
        return DB::table('ses_plan_horario')
            ->select([
                'idplan_horario as id',
                'dia_semana',
                'aforo_maximo',
                DB::raw('DATE_FORMAT(time_start, "%h:%i %p") as time_start'),
                DB::raw('DATE_FORMAT(time_end, "%h:%i %p") as time_end'),
            ])
            ->where('idplan', $id)
            ->where('dia_semana', $dia_semana)
            ->get();
	}
	public static function listRegistros($id)
	{
        $rows = DB::table('ses_reserva as r')
			->join('ses_plan_horario as ph', 'ph.idplan_horario', '=', 'r.idplan_horario')
			->select([
				'r.idreserva as id',
				'r.fecha',
				'r.active',
				DB::raw('DATE_FORMAT(ph.time_start, "%h:%i %p") as time_start'),
				DB::raw('DATE_FORMAT(ph.time_end, "%h:%i %p") as time_end'),
			])
			->where('r.idsuscripcion', $id)
			->orderBy('r.fecha')
			->get();
		// Map para formatear fecha
		return $rows->map(function ($row) {
			$row->fecha_formateada = Carbon::parse($row->fecha)->isoFormat('DD [de] MMMM [de] YYYY'); // Ej: 11 de diciembre de 2025
			return $row;
		});
	}
    
}
