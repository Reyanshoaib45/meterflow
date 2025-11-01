<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id',
        'meter_number',
        'name',
        'email',
        'phone_number',
        'subdivision_id',
        'company_id',
        'action_type',
        'remarks',
        'user_id',
        'seo_number',
        'sent_to_ro',
    ];

    /**
     * Get the application that owns the history.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the subdivision that owns the history.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Get the company that owns the history.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the user who made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}