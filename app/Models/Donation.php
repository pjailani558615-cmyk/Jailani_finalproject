<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Donation extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'sex',
        'age',
        'phone',
        'email',
        'address',
        'bloodtype',
        'weight',
        'dateoflastdonation',
        'disease',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'dateoflastdonation' => 'date',
        'disease' => 'boolean',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeRecent($query) {
        return $query->latest()->take(10);
    }

    public function scopeEligible($query) {
        return $query->where(function($q) {
            $q->whereNull('dateoflastdonation')
              ->orWhere('dateoflastdonation', '<', now()->subDays(90));
        });
    }
}

