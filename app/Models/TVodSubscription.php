<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TVodSubscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ottContent() :BelongsTo
    {
        return $this->belongsTo(OttContent::class);
    }
}
