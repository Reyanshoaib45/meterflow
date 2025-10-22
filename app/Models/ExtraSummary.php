<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraSummary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'subdivision_id',
        'total_applications',
        'approved_count',
        'rejected_count',
        'pending_count',
        'last_updated',
    ];

    /**
     * Get the company that owns the summary.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the subdivision that owns the summary.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }
}