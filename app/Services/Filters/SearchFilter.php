<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements TaskFilterStrategy
{
    public function apply(Builder $query, mixed $value): Builder
    {
        if ($value) {
            $query->where(function ($q) use ($value) {
                $q->where('title', 'like', "%$value%")
                    ->orWhere('description', 'like', "%$value%");
            });
        }

        return $query;
    }
}
