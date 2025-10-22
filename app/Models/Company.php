<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\ExtraSummary;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Get the subdivisions for the company.
     */
    public function subdivisions()
    {
        return $this->hasMany(Subdivision::class);
    }

    /**
     * Get the applications for the company.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the extra summaries for the company.
     */
    public function extraSummaries()
    {
        return $this->hasMany(ExtraSummary::class);
    }
}