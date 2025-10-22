<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_id',
        'name',
        'cnic',
        'address',
        'phone',
        'email',
        'subdivision_id',
        'connection_type',
        'status',
    ];

    /**
     * Get the subdivision that owns the consumer.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Get the meters for the consumer.
     */
    public function meters()
    {
        return $this->hasMany(Meter::class);
    }

    /**
     * Get the bills for the consumer.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Get the complaints for the consumer.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
