<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * The attributes that are translatable.
     *
     * @var array<int, string>
     */
    public array $translatable = ['description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'link',
        'is_public',
        'is_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'is_enabled' => 'boolean',
    ];

    /**
     * Filter services that have the is_enabled attribute equal to the given value.
     */
    public function scopeEnabled(Builder $query, bool $value = true): Builder
    {
        return $query->where('is_enabled', '=', $value);
    }

    /**
     * Filter services that have the is_public attribute equal to the given value.
     */
    public function scopePublic(Builder $query, bool $value = true): Builder
    {
        return $query->where('is_public', '=', $value);
    }
}
