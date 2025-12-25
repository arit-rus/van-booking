<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticatesWithLdap;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'phone',
        'guid',
        'domain',
        'idcard',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin (super admin or department admin)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->isDepartmentAdmin();
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a department admin
     */
    public function isDepartmentAdmin(): bool
    {
        return str_starts_with($this->role, 'admin_');
    }

    /**
     * Get the department code if user is a department admin
     */
    public function getAdminDepartment(): ?string
    {
        if ($this->isDepartmentAdmin()) {
            return str_replace('admin_', '', $this->role);
        }
        return null;
    }

    /**
     * Check if user can manage a specific department
     */
    public function canManageDepartment(string $department): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->getAdminDepartment() === $department;
    }

    /**
     * Check if user is a driver
     */
    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    /**
     * Get user's bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get bookings assigned to this driver
     */
    public function drivingAssignments()
    {
        return $this->hasMany(Booking::class, 'driver_id');
    }
}
