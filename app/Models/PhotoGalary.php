<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoGalary extends Model
{
    use HasFactory, Loggable;

    const FAMILY_MEMBERS = 'FAMILY_MEMBERS';
    const GALARY = 'GALARY';
    protected $guarded = ['id'];
}
