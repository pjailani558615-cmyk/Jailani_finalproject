<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BloodRequest extends Model {
    use HasFactory;

    protected $table = 'requests';  // Matches migration table name

    protected $fillable = [
        'user_id',
        'requester_type',
        'patient_name',
        'patient_age',
        'patient_sex',
        'phone',
        'email',
        'address',
        'required_blood_type',
        'units',
        'urgency',
        'request_datetime',
    ];

    protected $casts = [
        'patient_age' => 'integer',
        'units' => 'integer',
        'request_datetime' => 'datetime',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Scopes

    public function scopeEmergency($query) {
        return $query->where('urgency', 'emergency');
    }

    public function scopeRecent($query) {
        return $query->latest()->take(10);
    }
}

