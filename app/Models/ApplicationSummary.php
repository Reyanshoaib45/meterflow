<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSummary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id',
        'total_meters',
        'total_load',
        'avg_reading',
        'remarks',
    ];

    /**
     * Get the application that owns the summary.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}