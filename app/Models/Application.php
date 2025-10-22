<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subdivision;
use App\Models\Company;
use App\Models\Meter;
use App\Models\ApplicationHistory;
use App\Models\ApplicationSummary;

class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_no',
        'customer_name',
        'address',
        'phone',
        'cnic',
        'meter_type',
        'load_demand',
        'subdivision_id',
        'company_id',
        'status',
    ];

    /**
     * Get the subdivision that owns the application.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Get the company that owns the application.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the meter for the application.
     */
    public function meter()
    {
        return $this->hasOne(Meter::class);
    }

    /**
     * Get the histories for the application.
     */
    public function histories()
    {
        return $this->hasMany(ApplicationHistory::class);
    }

    /**
     * Get the summary for the application.
     */
    public function summary()
    {
        return $this->hasOne(ApplicationSummary::class);
    }
}