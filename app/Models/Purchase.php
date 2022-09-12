<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use App\Traits\HasManyPurchaseDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes, BelongsToUser, HasManyPurchaseDetail;

    protected $fillable = [
        'user_id',
        'purchase_number',
        'payment_method'
    ];

    public function proofOfPurchase(): HasMany
    {
        return $this->hasMany(ProofOfPurchase::class);
    }
    public function detailPurchase()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
    public function createdAt(): Attribute
    {
        return new Attribute(
            get: fn ($val) => date('Y-m-d', strtotime($val))
        );
    }

    // public function createdAt(): Attribute
    // {
    //     return new Attribute(
    //         get: fn ($val) => Carbon::parse($val)->isoFormat('D MMMM Y')
    //     );
    // }
}