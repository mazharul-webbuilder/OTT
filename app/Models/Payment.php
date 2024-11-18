<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];

    public function userSubcription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class);
    }
}
