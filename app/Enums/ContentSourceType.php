<?php

namespace App\Enums;

enum ContentSourceType:string {
    case TRAILER_RAW_PATH = 'trailer_raw_path';
    case TRAILER_PATH = 'trailer_path';
    case CONTENT_RAW_PATH = 'content_raw_path';
    case CONTENT_PATH = 'content_path';
    case AUDIO_PATH = 'audio_path';
    case SUBTITLE = 'subtitle';

    public static function getAllValues(): array
    {
        return array_column(ContentSourceType::cases(), 'value');
    }
}

