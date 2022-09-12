<?php

namespace App\Models;

use App\Traits\BelongsToChilliPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use HasFactory, SoftDeletes, BelongsToChilliPrice;

    protected $fillable = [
        'purchase_id',
        'chilli_price_id',
        'healthy_amount_of_chilies',
        'number_of_damaged_chilies',
    ];

    public function cilliPrice()
    {
        return $this->belongsTo(ChilliPrice::class);
    }
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}