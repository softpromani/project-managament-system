<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CommonQueryScopes
{
    public function scopeFilterByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeSearchByTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', "%{$title}%");
    }
}
