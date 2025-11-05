<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Subdivision;

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
        'name',
        'email',
        'username',
        'subdivision_id',
        'password',
        'role',
        'is_active',
        'permissions',
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
        ];
    }
    
    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if the user is an LS (Login Subdivision).
     *
     * @return bool
     */
    public function isLS()
    {
        return $this->role === 'ls';
    }
    
    /**
     * Check if the user is an SDC (Smart Data Center).
     *
     * @return bool
     */
    public function isSDC()
    {
        return $this->role === 'sdc';
    }
    
    /**
     * Check if the user is an RO (Revenue Officer).
     *
     * @return bool
     */
    public function isRO()
    {
        return $this->role === 'ro';
    }
    
        /**
     * Get the subdivisions assigned to this LS user (via ls_id).
     */
    public function lsSubdivisions()
    {
        return $this->hasMany(Subdivision::class, 'ls_id');
    }

    /**
     * Get the single subdivision assigned to this LS user.
     */
    public function assignedSubdivision()
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_id');
    }

    /**
     * Get subdivisions for SDC/RO users (many-to-many relationship).
     */
    public function subdivisions()
    {
        return $this->belongsToMany(Subdivision::class, 'subdivision_user')
                    ->withTimestamps();
    }
}