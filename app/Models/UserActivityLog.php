<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'ses_activity_logs';
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'iduser',
        'method',
        'url',
        'route_name',
        'ip',
        'user_agent',
        'query',
        'payload',
        'status_code',
        'query',
        'payload'
    ];
}
