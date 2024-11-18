<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionSourceFormat extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];
}
