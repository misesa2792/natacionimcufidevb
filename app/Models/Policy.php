<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = ['iduser', 'module', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function user()
    {
        /*
        significa que el modelo actual (por ejemplo, Policy) pertenece a un usuario, es decir, está relacionado con el modelo User mediante una relación de tipo "pertenece a" (belongs to).
        */
        return $this->belongsTo(User::class, 'iduser');
    }
}
