<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Admin model – backed by the `users` table, scoped to role = 'admin'.
 */
class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * Reuse the users table; admin rows are those with role = 'admin'.
     */
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Always scope queries to admin users only.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('role', 'admin');
        });

        // Automatically set role when creating via this model
        static::creating(function (self $model) {
            $model->role = 'admin';
        });
    }
}
