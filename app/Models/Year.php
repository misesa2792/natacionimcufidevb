<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Year extends Model
{
	protected $table = 'ses_year';
    protected $primaryKey = 'idyear'; 

    protected $fillable = [
        'numero'
    ];

	public $timestamps = false;

 
}
