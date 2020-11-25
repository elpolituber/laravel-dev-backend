<?php

namespace App\Traits;

use App\Models\Ignug\State;
use Illuminate\Database\Eloquent\Builder;

trait StatusActiveTrait
{
    protected static function booted()
    {
        static::addGlobalScope('isActive', function (Builder $builder) {
            return $builder->where('state_id', State::firstWhere('code', State::ACTIVE)->id);
        });
    }

}
