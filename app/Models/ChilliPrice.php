<?php

namespace App\Models;

use App\Traits\HasManyPurchaseDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChilliPrice extends Model
{
    use HasFactory, SoftDeletes, HasManyPurchaseDetail;

    protected $fillable = [
        'chilli_id',
        'price'
    ];

    public function chilli(): BelongsTo
    {
        return $this->belongsTo(Chilli::class);
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