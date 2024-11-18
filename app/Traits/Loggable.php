<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait Loggable
{
    use LogsActivity;

    // use this name for logging
    public function getLogNameToUse(string $eventName = ''): string
    {
        return class_basename($this);
    }
    // the filed that will add in activity properties
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }


}
