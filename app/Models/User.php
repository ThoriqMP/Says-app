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
        'role',
        'permissions',
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
            'permissions' => 'array',
        ];
    }

    /**
     * Relasi ke invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah pimpinan
     */
    public function isPimpinan()
    {
        return $this->role === 'pimpinan';
    }

    /**
     * Cek apakah user memiliki akses ke permission tertentu
     */
    public function hasPermission($permission)
    {
        if ($this->isPimpinan()) {
            return true;
        }

        if ($this->role !== 'admin') {
            return false;
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }
}
