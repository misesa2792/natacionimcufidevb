<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pagos extends Model
{
    
	protected $table = 'sl_pagos';
    protected $primaryKey = 'idpagos'; 

    protected $fillable = [
        'iduser',
        'idmes',
        'idanio',
        'idpagos_estatus',
        'recargo',
        'descuento',
        'subtotal',
        'total',
        'idtipo_pago',
        'rg_iduser',
        'rg_fecha',
        'rg_hora',
        'fecha_pago',
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('sl_pagos as p')
			->leftJoin('sl_mes as m', 'm.idmes', '=', 'p.idmes')
			->leftJoin('sl_pagos_estatus as pe', 'pe.idpagos_estatus', '=', 'p.idpagos_estatus')
			->leftJoin('sl_tipos_pago as tp', 'tp.idtipos_pago', '=', 'p.idtipo_pago')
			->select([
				'p.idpagos',
				'm.mes',
				'p.total',
				'p.recargo',
				'p.rg_fecha',
				'p.rg_hora',
				DB::raw('pe.idpagos_estatus as ide'),
				'pe.estatus',
				DB::raw('tp.descripcion as tipo_pago')
			])
			->where('p.iduser', $request['iduser'])
			->where('p.idanio', $request['idyear'])
			->orderBy('p.idmes', 'asc');
		// Filtros opcionales (ejemplo)
		/*
		if (!empty($request['nombre']) && trim($request['nombre']) !== '') {
			$query->where('f.descripcion', 'like', '%'.trim($request['nombre']).'%');
		}
		*/
		return $query->get();
		//return $query->paginate($perPage)->appends($request);
	}
    public static function pagoID($idu, $idpago){
		return DB::table('sl_pagos as p')
		->leftjoin('sl_tipos_pago as tp', 'tp.idtipos_pago', '=', 'p.idtipo_pago')
		->join('sl_mes as m', 'm.idmes', '=', 'p.idmes')
		->join('sl_anio as y', 'y.idanio', '=', 'p.idanio')
		->join('tb_users as u', 'u.id', '=', 'p.iduser')
		->join('sl_clientes as c', 'c.idclientes', '=', 'u.idclientes')
			->leftjoin('sl_tipo_servicio as ts', 'ts.idtipo_servicio', '=', 'c.idtipo_servicio')
		->join('sl_plan as pl', 'pl.idplan', '=', 'c.idplan')
		->select([
			'm.mes',
			'y.anio',
			'c.correo',
			'c.telefono',
			'c.type',
			'c.nc',
			'ts.velocidad',
			'c.nombre_completo as cliente',
			'c.comunidad',
			'pl.cantidad as plan',

			DB::raw('tp.descripcion as tipo_pago'),
			'p.recargo',
			'p.descuento',
			'p.subtotal',
			'p.total'
		])
		->where('p.iduser', $idu)
		->where('p.idpagos', $idpago)
		->first();
	}
	public static function transactionCode($id){
		return DB::table('ses_payments')
			->where('idpagos', $id)
			->where('status', 'completed')
			->value('provider_charge_id'); 
	}
   
}
