<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'chilli_id',
        'selling_price',
        'total'
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function chilli(): BelongsTo
    {
        return $this->belongsTo(Chilli::class);
    }
}
