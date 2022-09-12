<?php

namespace App\Traits;

use App\Models\PurchaseDetail;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPurchaseDetail
{
    public function purchaseDetail(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
