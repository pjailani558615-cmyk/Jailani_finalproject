<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'receiver_email',
        'message',
        'status',
    ];

    // Relationships
    public function staff() {
        return $this->belongsTo(Staff::class);
    }

    // Scopes
    public function scopeRecent($query) {
        return $query->latest()->take(10);
    }

    public function scopeSent($query) {
        return $query->where('status', 'sent');
    }
}

