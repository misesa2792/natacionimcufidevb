<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'idnivel',
        'active',
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at'     => 'datetime',
            'last_activity_at'  => 'datetime',
        ];
    }

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

        $query = DB::table('users as u')
            ->join('ses_nivel as n', 'n.idnivel', '=', 'u.idnivel')
            ->select([
                'u.id',
                'u.active',
                'u.name',
                'u.email',
                'n.name as nivel'
            ])
            ->whereIn('u.idnivel', [2,3])
            ->orderBy('u.idnivel', 'asc');

        if (!empty($request['name']) && trim($request['name']) !== '') {
			$query->where('u.name', 'like', '%'.trim($request['name']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
    public static function listNiveles()
	{
		return DB::table('ses_nivel')
        ->select('idnivel as id', 'name as nivel')
        ->whereIn('idnivel', [2, 3])
        ->get();
	}

   
}
