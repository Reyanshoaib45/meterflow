<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meter extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meter_no',
        'meter_make',
        'reading',
        'remarks',
        'sim_number',
        'seo_number',
        'application_id',
        'consumer_id',
        'subdivision_id',
        'status',
        'installed_on',
        'last_reading',
        'last_reading_date',
        'meter_image',
        'in_store',
    ];

    protected $casts = [
        'installed_on' => 'date',
        'last_reading_date' => 'date',
        'in_store' => 'boolean',
    ];

    /**
     * Get the application that owns the meter.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the consumer that owns the meter.
     */
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    /**
     * Get the subdivision that owns the meter.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Get the bills for the meter.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}