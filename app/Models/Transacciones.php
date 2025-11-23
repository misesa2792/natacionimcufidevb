<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transacciones extends Model
{
    
	protected $table = 'ses_payments';
    protected $primaryKey = 'idpayments'; 

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('ses_payments as p')
			->leftJoin('users as u', 'u.id', '=', 'p.iduser')
			->leftJoin('sl_pagos as pa', 'pa.idpagos', '=', 'p.idpagos')
			->select([
				'p.idpayments as id',
				'p.idpagos',
				'p.provider_charge_id',
				'p.status',
				'p.amount',
				'p.currency',
				'p.client',
				'p.description',
				'p.created_at as fecha',
			])
			->orderBy('p.idpayments', 'desc');
		// Filtros opcionales (ejemplo)
		/*
		if (!empty($request['nombre']) && trim($request['nombre']) !== '') {
			$query->where('f.descripcion', 'like', '%'.trim($request['nombre']).'%');
		}
		*/
		return $query->paginate($perPage)->appends($request);
	}
    
}
