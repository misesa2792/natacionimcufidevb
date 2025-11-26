<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Suscripcionesfiles extends Model
{
	protected $table = 'ses_suscripcion_img';
    protected $primaryKey = 'idsuscripcion_img'; 

    protected $fillable = [
        'idsuscripcion',
        'url'
    ];
}
