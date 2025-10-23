<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Application;
use App\Models\User;

class Subdivision extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'ls_id',
        'is_active',
        'subdivision_message',
    ];

    /**
     * Get the company that owns the subdivision.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the applications for the subdivision.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    
    /**
     * Get the LS user assigned to this subdivision.
     */
    public function lsUser()
    {
        return $this->belongsTo(User::class, 'ls_id');
    }

    /**
     * Get the consumers for the subdivision.
     */
    public function consumers()
    {
        return $this->hasMany(Consumer::class);
    }

    /**
     * Get the meters for the subdivision.
     */
    public function meters()
    {
        return $this->hasMany(Meter::class);
    }

    /**
     * Get the bills for the subdivision.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Get the complaints for the subdivision.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}