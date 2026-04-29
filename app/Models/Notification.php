<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model {
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'sender_id',
        'message',
    ];

    // Relationships
   public function recipient() {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeRecent($query) {
        return $query->latest()->take(10);
    }
}

