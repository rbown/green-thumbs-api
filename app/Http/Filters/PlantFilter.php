<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class PlantFilter implements Filterable
{
    public function apply(Builder $builder, $filters)
    {
        $builder->orderBy('created_at', 'DESC');
    }
}
