<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Application;

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
}