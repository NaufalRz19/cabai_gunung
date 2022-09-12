<?php

namespace App\Models;

use App\Traits\BelongsToChilliPrice;
use App\Traits\BelongsToUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes, BelongsToUser;

    protected $fillable = [
        'user_id',
        'sales_total',
        'sales_number',
        'is_success'
    ];

    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function proofOfSale(): HasMany
    {
        return $this->hasMany(ProofOfSale::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function createdAt(): Attribute
    {
        return new Attribute(
            get: fn ($val) => date('Y-m-d', strtotime($val))
        );
    }
}