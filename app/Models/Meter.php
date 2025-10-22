<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;

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
        'application_id',
    ];

    /**
     * Get the application that owns the meter.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}