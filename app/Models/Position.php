<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\Modality;
use App\Enums\PositionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'location',
        'salary_min',
        'salary_max',
        'salary_currency',
        'employment_type',
        'modality',
        'status',
        'image',
        'company_name',
        'company_logo',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return asset('storage/position-images/' . $this->image);
    }

    public function getCompanyLogoUrlAttribute(): ?string
    {
        if (!$this->company_logo) {
            return null;
        }
        return asset('storage/company-logos/' . $this->company_logo);
    }

    public function getSalaryRangeAttribute(): ?string
    {
        if (is_null($this->salary_min) && is_null($this->salary_max)) {
            return null;
        }

        $currency = $this->salary_currency ?? 'USD';

        if ($this->salary_min && $this->salary_max) {
            return '$' . number_format($this->salary_min) . ' – $' . number_format($this->salary_max) . ' ' . $currency;
        }

        $single = $this->salary_min ?? $this->salary_max;

        return '$' . number_format($single) . ' ' . $currency;
    }

    public function isOpen(): bool
    {
        return $this->status === PositionStatus::OPEN;
    }

    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
            'modality' => Modality::class,
            'status' => PositionStatus::class,
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
