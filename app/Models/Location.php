<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes, BelongsToUser;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude'
    ];

    public function createdAt(): Attribute
    {
        return new Attribute(
            get: fn($val) => Carbon::parse($val)->timezone('Asia/Jakarta')->isoFormat('D MMMM Y H:m:s')
        );
    }
}
