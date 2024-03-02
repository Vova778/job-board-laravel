<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;


class Job extends Model
{
    use HasFactory;

    public static array $experience = [
        'junior',
        'middle',
        'senior'
    ];
    public static array $category = [
        'IT',
        'Finance',
        'Sales',
        'Marketing'
    ];

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function scopeFilter(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        $filterable = [
            'search' => fn($query, $search) => $query->where(
                fn($query) => $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('employer', function ($query) use ($search) {
                        $query->where('company_name', 'like', '%' . $search . '%');
                    })
            ),
            'min_salary' => fn($query, $minSalary) => $query->where('salary', '>=', $minSalary),
            'max_salary' => fn($query, $maxSalary) => $query->where('salary', '<=', $maxSalary),
            'experience' => fn($query, $experience) => $query->where('experience', $experience),
            'category' => fn($query, $category) => $query->where('category', $category),
        ];

        foreach ($filters as $key => $value) {
            if (array_key_exists($key, $filterable) && $value !== null) {
                $query->when($value, $filterable[$key]);
            }
        }

        return $query;
    }

}
