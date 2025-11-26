<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Descuento extends Model
{
	protected $table = 'ses_descuento';
    protected $primaryKey = 'iddescuento'; 

    protected $fillable = [
        'descripcion',
        'descuento'
    ];
	
	public $timestamps = false;

}
