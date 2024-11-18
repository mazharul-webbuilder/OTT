<?php

namespace App\Enums;

enum ContentRawSourceType: string
{
    case TRAILER_RAW_PATH = 'trailer_raw_path';
    case CONTENT_RAW_PATH = 'content_raw_path';
    
    public static function getAllValues(): array
    {
        return array_column(ContentRawSourceType::cases(), 'value');
    }
}
