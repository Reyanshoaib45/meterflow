<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'consumer_id',
        'subdivision_id',
        'company_id',
        'type',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'created_by',
        'resolved_at',
        'resolution_notes',
        'customer_name',
        'phone',
        'consumer_ref',
        'complaint_type',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the consumer that owns the complaint.
     */
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    /**
     * Get the subdivision that owns the complaint.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Get the company that owns the complaint.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user assigned to the complaint.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created the complaint.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the histories for the complaint.
     */
    public function histories()
    {
        return $this->hasMany(ComplaintHistory::class);
    }

    /**
     * Scope a query to only include pending complaints.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in progress complaints.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include resolved complaints.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}
