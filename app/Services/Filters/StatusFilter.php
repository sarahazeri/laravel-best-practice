<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter implements TaskFilterStrategy
{
    public function apply(Builder $query, mixed $value): Builder
    {
        if ($value) {
            $query->where('status', $value);
        }

        return $query;
    }
}
