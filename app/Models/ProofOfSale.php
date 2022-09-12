<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProofOfSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['full_image_url'];
    protected $fillable = [
        'sale_id',
        'image_url'
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
    public function getFullImageUrlAttribute(): string
    {
        return asset('storage/sale/' . $this->image_url);
    }
}
