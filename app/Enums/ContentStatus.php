<?php

namespace App\Enums;

enum ContentStatus:string {
    case PUBLISHED = 'Published';
    case HOLD = 'Hold';
    case PENDING = 'Pending'; 
}