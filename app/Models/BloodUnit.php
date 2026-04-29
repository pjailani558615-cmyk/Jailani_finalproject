<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodUnit extends Model {
    use HasFactory;

    protected $fillable = [
        'unit_code',
        'donation_id',
        'blood_type',
        'volume',
        'collection_date',
        'expiry_date',
        'location',
        'status',
        'request_id',
        'staff_id',
    ];

    protected $casts = [
        'volume' => 'decimal:1',
        'collection_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function donation() {
        return $this->belongsTo(Donation::class);
    }

    public function request() {
        return $this->belongsTo(BloodRequest::class);
    }

    public function staff() {
        return $this->belongsTo(Staff::class);
    }

    // Scopes
    public function scopeAvailable($query) {
        return $query->where('status', 'available');
    }

    public function scopeExpiringSoon($query) {
        return $query->where('expiry_date', '<=', now()->addDays(7));
    }

    public function getStatusBadgeAttribute() {
        $badges = [
            'available' => 'success',
            'reserved' => 'warning', 
            'issued' => 'info',
            'expired' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }
}

