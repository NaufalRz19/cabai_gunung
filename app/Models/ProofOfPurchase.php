<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProofOfPurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['full_image_url'];
    protected $fillable = [
        'purchase_id',
        'image_url'
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function getFullImageUrlAttribute(): string
    {
        return asset('storage/purchase/' . $this->image_url);
    }
}
