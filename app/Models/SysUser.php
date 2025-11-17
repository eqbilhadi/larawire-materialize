<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SysUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'birthplace',
        'birthdate',
        'gender',
        'avatar',
        'phone',
        'address',
        'email_verified_at',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    protected function mainRole(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::ucfirst($this->roles()->first()?->name)
        );
    }

    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {

                $name = $this->name;

                if (!$name) return null;

                $clean = trim(preg_replace('/\b(Prof|Dr|Mr|Mrs|Ms|Ir|H|Hj)\.?|Sr\.?|Jr\.?|\bII\b|\bIII\b/i', '', $name));

                return collect(explode(' ', $clean))
                    ->filter()
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->join('');
            }
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar) {
                    return route('stream.file', $this->avatar);
                }

                return null;
            }
        );
    }
}
