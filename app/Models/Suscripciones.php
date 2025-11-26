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
        'idyear',
        'idmes',
        'idplan',
        'fecha_pago',
        'hora_pago',
        'fecha_inicio',
        'fecha_fin',
        'active',
        'idtipo_pago',
        'max_visitas_mes',
        'monto_general',
        'monto_pagado',
        'descuento',
        'desc_descuento',
        'porc_descuento',
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('ses_nadador as n')
			->leftjoin('ses_plan as p', 'p.idplan', '=', 'n.idplan')
				->leftjoin('ses_niveles as s', 's.idniveles', '=', 'p.idniveles')
			->leftjoin('ses_descuento as de', 'de.iddescuento', '=', 'n.iddescuento')
			->select([
				'n.idnadador as id',
				'n.active',
				'n.nombre',
				'n.curp',
				'p.nombre as plan',
				's.descripcion as nivel',
				'de.descripcion as desc_descuento',
				'de.descuento',
			])
			->orderBy('n.nombre', 'asc');
		// Filtros opcionales (ejemplo)
		
		if (!empty($request['nombre']) && trim($request['nombre']) !== '') {
			$query->where('n.nombre', 'like', '%'.trim($request['nombre']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
	public static function listHistorialPagos($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('ses_suscripcion as s')
			->join('ses_mes as m', 'm.idmes', '=', 's.idmes')
			->leftjoin('ses_plan as p', 'p.idplan', '=', 's.idplan')
				->leftjoin('ses_niveles as ni', 'ni.idniveles', '=', 'p.idniveles')
			->join('ses_nadador as n', 'n.idnadador', '=', 's.idnadador')
			->join('ses_tipo_pago as tp', 'tp.idtipo_pago', '=', 's.idtipo_pago')
			->select([
				's.idsuscripcion as id',
				's.active',
				'm.mes',
				 DB::raw("DATE_FORMAT(s.fecha_pago, '%e de %M del %Y') as fecha_pago"),
				'n.nombre as alumno',
				'p.nombre as plan',
				'ni.descripcion as nivel',
				'tp.descripcion as tipo_pago',
				's.monto_general',
				's.monto_pagado',
				's.descuento',
				's.porc_descuento',
				's.desc_descuento'
			])
			->where('s.idyear', $request['idyear'])
			->orderBy('s.fecha_pago', 'desc');
		if (!empty($request['nombre']) && trim($request['nombre']) !== '') {
			$query->where('n.nombre', 'like', '%'.trim($request['nombre']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
	public function pagosPorMes($idalumno, $idyear)
	{
		return DB::table('ses_suscripcion')
			->where('idnadador', $idalumno)
			->where('idyear', $idyear)
			->select('idsuscripcion as id','idmes', 'idtipo_pago','active')
			->get();
	}
	public function validarSuscripcion($idn, $idy, $idm)
	{
		return DB::table('ses_suscripcion')
				->where('idnadador', $idn)
				->where('idyear', $idy)
				->where('idmes', $idm)
				->count();
	}
	/*public static function suscripcionID($id)
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
	}*/
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
				's.monto_pagado as monto',
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
					->join('ses_niveles as ni', 'ni.idniveles', '=', 'pl.idniveles')
				->join('ses_genero as g', 'g.idgenero', '=', 'n.idgenero')
				->join('ses_parentesco as pa', 'pa.idparentesco', '=', 'n.idparentesco')
				->leftjoin('ses_descuento as de', 'de.iddescuento', '=', 'n.iddescuento')
				->select([
					'n.active',
					'n.nombre',
					'n.curp',
					'n.fecha_nacimiento',
					'g.descripcion as genero',
					'n.domicilio',
					'n.edad',
					'n.iddescuento',
					'de.descripcion as desc_descuento',
					'de.descuento',
					'pl.nombre as plan',
					'pl.idplan',
					'pl.idniveles',
					'ni.descripcion as nivel',
					'ni.aforo_maximo',
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
	/*public static function dataPlanes()
	{
		return DB::table('ses_plan')
			->select([
				'idplan as id',
				'nombre as plan',
				'precio'
			])
			->get();
	}*/
	public static function horariosNiveles($id)
	{
		return DB::table('ses_plan_horario')
            ->select('idplan_horario','dia_semana', 'time_start')
            ->where('idniveles', $id)
            ->orderBy('time_start')
            ->get()
            ->groupBy('dia_semana');
	}
	public static function fechasTemporales($id, $time)
	{
		return DB::table('ses_reserva_alt')
            ->select('fecha','idplan_horario')
            ->where('idnadador', $id)
            ->where('time', $time)
            ->get();
	}
	public static function descuentos()
	{
		return DB::table('ses_descuento')
            ->select('iddescuento as id','descripcion','descuento')
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

	public static function listHorarioSuscripcion($id)
	{
        $rows = DB::table('ses_suscripcion as s')
				->join('ses_reserva as r', 'r.idsuscripcion', '=', 's.idsuscripcion')
				->join('ses_plan_horario as ph', 'ph.idplan_horario', '=', 'r.idplan_horario')
				->where('s.idsuscripcion', $id)
				->orderBy('r.fecha', 'asc')
				->select([
					'r.fecha',
					DB::raw('DATE_FORMAT(ph.time_start, "%h:%i %p") as time_start'),
					DB::raw('DATE_FORMAT(ph.time_end, "%h:%i %p") as time_end'),
				])
				->get();
		return $rows->map(function ($row) {
			$row->fecha_formateada = Carbon::parse($row->fecha)->isoFormat('DD [de] MMMM [de] YYYY'); // Ej: 11 de diciembre de 2025
			return $row;
		});
	}
	public static function suscripcionDataID($id)
	{
		$row = DB::table('ses_suscripcion as s')
			->join('ses_nadador as n', 'n.idnadador', '=', 's.idnadador')
			->join('ses_plan as p', 'p.idplan', '=', 's.idplan')
				->join('ses_niveles as ni', 'ni.idniveles', '=', 'p.idniveles')
			->leftjoin('ses_year as y', 'y.idyear', '=', 's.idyear')
			->leftjoin('ses_mes as me', 'me.idmes', '=', 's.idmes')
			->leftjoin('ses_tipo_pago as tp', 'tp.idtipo_pago', '=', 's.idtipo_pago')
			->select([
				's.idsuscripcion as id',
				'n.nombre',
				'n.curp',
				's.fecha_inicio as fi',
				's.fecha_fin as ff',
				's.monto_pagado as monto',
				'p.nombre as plan',
				'tp.descripcion as pago',
				's.max_visitas_mes',
				'ni.descripcion as nivel',
				's.idmes',
				'me.mes',
				'y.numero as year',
				's.monto_general',
				's.monto_pagado',
				's.descuento',
				DB::raw("
					CASE 
						WHEN CURDATE() BETWEEN s.fecha_inicio AND s.fecha_fin 
							THEN 'ACTIVA'
						ELSE 'VENCIDA'
					END AS estado
				"),
			])
			->where('s.idsuscripcion', $id)
			->first();
		if ($row) {
			$row->fi_formateada = Carbon::parse($row->fi)->isoFormat('DD [de] MMMM [de] YYYY');
			$row->ff_formateada = Carbon::parse($row->ff)->isoFormat('DD [de] MMMM [de] YYYY');
		}
		return $row;
	}
	public static function listSuscripcionesDia($id, $fecha)
	{
		return DB::table('ses_reserva as r')
		->join('ses_suscripcion as s', 's.idsuscripcion', '=', 'r.idsuscripcion')
		->join('ses_nadador as n', 'n.idnadador', '=', 's.idnadador')
		->where('r.idplan_horario', $id)
		->where('r.fecha', $fecha)
		->select(
			'r.idreserva as id',
			'n.nombre as alumno',
			'r.fecha',
			'r.active'
		)
		->get();
	}
	public static function evidenciaIMGs($id)
	{
		return DB::table('ses_suscripcion_img')
		->where('idsuscripcion', $id)
		->select(
			'url'
		)
		->get();
	}

	//dashboard
	public function listaMesPagados($idy, $idm, $idtp, $active = 1)
	{
		return DB::table('ses_suscripcion')
			->where('idyear', $idy)
			->where('idmes', $idm)
			->where('idtipo_pago', $idtp)
			->where('active', $active)
			->sum('monto_pagado');
	}
	public function listaMesPendientes($idy, $idm, $active = 1)
	{
		return DB::table('ses_suscripcion')
			->where('idyear', $idy)
			->where('idmes', $idm)
			->where('active', $active)
			->sum('monto_pagado');
	}
	public function listaMesCalendario($idy, $idm)
	{
		return DB::table('ses_suscripcion')
			->select(
				DB::raw('DAY(fecha_pago) as dia'),
				DB::raw('SUM(monto_pagado) as total')
			)
			->where('idyear', $idy)
			->where('idmes', $idm)
			->groupBy(DB::raw('DAY(fecha_pago)'))
			->orderBy(DB::raw('DAY(fecha_pago)'))
			->get();
	}
    
    
}
