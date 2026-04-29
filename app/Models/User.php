<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Donation;
use App\Models\BloodRequest;
use App\Models\Notification;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

   protected $fillable = [
        'name', 'age', 'sex', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helpers
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isUser(): bool  { return $this->role === 'user'; }
    public function isStaff(): bool  { return $this->role === 'staff'; }

    // Relationships
    public function donations() {
        return $this->hasMany(Donation::class);
    }

    public function requests() {
        return $this->hasMany(BloodRequest::class);
    }

    public function notifications() {
    return $this->hasMany(Notification::class, 'recipient_id')->latest();
    }
}
