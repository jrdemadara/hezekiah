<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'code',
        'avatar',
        'lastname',
        'firstname',
        'middlename',
        'email',
        'password',
        'points',
        'province',
        'city',
        'barnagay',
        'street',
        'house_number',
        'zipcode',
        'landmark',
        'coordinates',
        'referred_by',
        'email_verified_at',
        'auth_provider',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

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

    public function bags()
    {
        return $this->hasMany(Bag::class, 'user_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function getReferralsCountAttribute()
    {
        return $this->referrals()->count();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }
}
