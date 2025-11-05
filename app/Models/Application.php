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
        'meter_number',
        'load_demand',
        'subdivision_id',
        'company_id',
        'connection_type',
        'status',
        'fee_amount',
        'user_id',
        'assigned_ro_id',
        'assigned_ls_id',
        'assigned_sdc_id',
        'installation_date',
        'gps_latitude',
        'gps_longitude',
        'installation_remarks',
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

    /**
     * Get the assigned RO user.
     */
    public function assignedRO()
    {
        return $this->belongsTo(User::class, 'assigned_ro_id');
    }

    /**
     * Get the assigned LS user.
     */
    public function assignedLS()
    {
        return $this->belongsTo(User::class, 'assigned_ls_id');
    }

    /**
     * Get the assigned SDC user.
     */
    public function assignedSDC()
    {
        return $this->belongsTo(User::class, 'assigned_sdc_id');
    }

    /**
     * Get the GlobalSummary for the application.
     */
    public function globalSummary()
    {
        return $this->hasOne(GlobalSummary::class);
    }

    /**
     * Get the user who created the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}