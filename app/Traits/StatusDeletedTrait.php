<?php

namespace App\Traits;

use App\Models\Ignug\State;

trait StatusDeletedTrait
{
    public function scopeIsDeleted($query)
    {
        return $query->where('state_id', State::firstWhere('code', State::DELETED)->id);
    }
}
