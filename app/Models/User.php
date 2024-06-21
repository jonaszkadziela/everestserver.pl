<?php

namespace App\Models;

use App\Models\Traits\CustomCanResetPassword;
use App\Models\Traits\CustomMustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use CustomCanResetPassword;
    use CustomMustVerifyEmail;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
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
        'is_admin' => 'boolean',
        'is_enabled' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Filter users that have the is_admin attribute equal to the given value.
     */
    public function scopeAdmin(Builder $query, bool $value = true): Builder
    {
        return $query->where('is_admin', '=', $value);
    }

    /**
     * Filter users that have the is_enabled attribute equal to the given value.
     */
    public function scopeEnabled(Builder $query, bool $value = true): Builder
    {
        return $query->where('is_enabled', '=', $value);
    }

    /**
     * Relation to services.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'services_users')->withPivot('identifier');
    }
}
