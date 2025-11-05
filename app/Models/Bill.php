<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no',  // Changed from 'bill_number' to match migration
        'consumer_id',
        'meter_id',
        'subdivision_id',
        'billing_month',
        'billing_year',
        'previous_reading',
        'current_reading',
        'units_consumed',
        'rate_per_unit',
        'energy_charges',
        'fixed_charges',
        'gst_amount',
        'tv_fee',
        'meter_rent',
        'late_payment_surcharge',
        'total_amount',
        'amount_paid',
        'payment_status',
        'due_date',
        'issue_date',
        'payment_date',
        'is_verified',
        'verified_by',
        'remarks',
    ];
    
    /**
     * Get the bill number (backward compatibility accessor).
     */
    public function getBillNumberAttribute()
    {
        return $this->bill_no;
    }
    
    /**
     * Set the bill number (backward compatibility mutator).
     */
    public function setBillNumberAttribute($value)
    {
        $this->attributes['bill_no'] = $value;
    }

    protected $casts = [
        'due_date' => 'date',
        'issue_date' => 'date',
        'payment_date' => 'date',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the consumer that owns the bill.
     */
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    /**
     * Get the meter that owns the bill.
     */
    public function meter()
    {
        return $this->belongsTo(Meter::class);
    }

    /**
     * Get the subdivision that owns the bill.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Get the user who verified the bill.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
