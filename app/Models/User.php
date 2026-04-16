<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'client_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isCandidate(): bool
    {
        return $this->role === UserRole::CANDIDATE;
    }

    public function isHrAdmin(): bool
    {
        return $this->role === UserRole::HR_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    /**
     * True for either admin tier. Blade templates already call this helper,
     * so it stays as a broad "has admin access" check.
     */
    public function isAdmin(): bool
    {
        return $this->isHrAdmin() || $this->isSuperAdmin();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function candidateProfile(): HasOne
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function authoredNotes(): HasMany
    {
        return $this->hasMany(ApplicationNote::class, 'author_id');
    }

    public function referralsSent(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }
}
