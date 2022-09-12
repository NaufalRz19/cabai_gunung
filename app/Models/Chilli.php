<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chilli extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type_of_chilli',
        'fee'
    ];

    public function chilliPrice(): HasMany
    {
        return $this->hasMany(ChilliPrice::class)->orderBy('created_at', 'desc');
    }
}
