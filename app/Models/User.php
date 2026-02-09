<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

public function assignedLeads() {
  return $this->hasMany(\App\Models\Lead::class, 'assigned_to');
}

public function isAdmin(): bool { return $this->role === 'admin'; }
public function isOperation(): bool { return $this->role === 'operation'; }
public function isSalesman(): bool { return $this->role === 'salesman'; }

// "online" = last_seen_at within last 3 minutes
public function scopeOnline($q) {
  return $q->whereNotNull('last_seen_at')
           ->where('last_seen_at', '>=', now()->subMinutes(3));
}


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
