<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function parent()
    {
        return $this->hasOne(Parents::class);
    }

    /**
     * Check if user possesses a specific profile/role in the system.
     */
    public function hasRole(string $role): bool
    {
        if ($role === 'admin') {
            return $this->role === 'admin';
        }
        if ($role === 'headmaster') {
            return $this->role === 'headmaster';
        }
        if ($role === 'teacher') {
            return $this->teacher()->exists();
        }
        if ($role === 'parent') {
            return $this->parent()->exists();
        }
        return false;
    }
}
