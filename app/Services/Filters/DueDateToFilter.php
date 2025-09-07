<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

class DueDateToFilter implements TaskFilterStrategy
{
    public function apply(Builder $query, mixed $value): Builder
    {
        if ($value) {
            $query->whereDate('due_date', '<=', $value);
        }

        return $query;
    }
}
