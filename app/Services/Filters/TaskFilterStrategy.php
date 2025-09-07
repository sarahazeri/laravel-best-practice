<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

interface TaskFilterStrategy
{
    public function apply(Builder $query, mixed $value): Builder;
}
