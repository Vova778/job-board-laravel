<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public static array $experience = [
        'entry',
        'intermediate',
        'senior'
    ];
    public static array $category = [
        'IT',
        'Finance',
        'Sales',
        'Marketing'
    ];

    public function scopeFilterBySalaryRange (Builder $query, ?string $from, ?string $to): Builder
    {
        return $query->when($from,
            fn(Builder $query) => $query->where('salary', '>=', $from))
            ->when($to,
                fn(Builder $query) => $query->where('salary', '<=', $to));
    }
}
