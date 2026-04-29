<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donation;
use App\Models\BloodRequest;

class BloodUnit extends Model {
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'blood_type',
        'request_id',
        'volume',
        'expiry_date',
    ];

    protected $casts = [
        'volume' => 'integer',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function donation() {
        return $this->belongsTo(Donation::class);
    }

    public function request() {
        return $this->belongsTo(BloodRequest::class);
    }

    public function scopeExpiringSoon($query) {
        return $query->where('expiry_date', '<=', now()->addDays(7));
    }
}

