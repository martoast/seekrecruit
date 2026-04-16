<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'industry',
        'logo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        return asset('storage/client-logos/' . $this->logo);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Cascade soft-delete to owned positions so they disappear with the client
     * but applications (historical record) stay intact.
     */
    protected static function booted(): void
    {
        static::deleting(function (Client $client) {
            if (! $client->isForceDeleting()) {
                $client->positions()->delete();
            }
        });
    }
}
