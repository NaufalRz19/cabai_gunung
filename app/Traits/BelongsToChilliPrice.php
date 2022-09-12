<?php

namespace App\Traits;

use App\Models\ChilliPrice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToChilliPrice
{
    public function chilliPrice(): BelongsTo
    {
        return $this->belongsTo(ChilliPrice::class);
    }
}
