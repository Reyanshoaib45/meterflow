<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

class GlobalSummary extends Model
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
        'meter_no',
        'sim_date',
        'date_on_draft_store',
        'date_received_lm_consumer',
        'customer_mobile_no',
        'customer_sc_no',
        'date_return_sdc_billing',
        'application_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sim_date' => 'date',
        'date_on_draft_store' => 'date',
        'date_received_lm_consumer' => 'date',
        'date_return_sdc_billing' => 'date',
    ];

    /**
     * Get the application that owns the global summary.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}