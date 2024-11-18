<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait Cacheable
{

    /**
     * Remember the cache for a given query.
     *
     * @param string $key The cache key.
     * @param callable $query The query callback that returns the data to be cached.
     * @param int|\DateTimeInterface|null $duration The duration for which the cache should be stored.
     *        Default value is fetched from config('constants.APP_CACHING_TIME').
     * @return mixed The result of the query or cached data.
     */
    protected function rememberCache(string $key, callable $query, int|\DateTimeInterface|null $duration = null): mixed
    {
        $duration = $duration ?? config('constants.APP_CACHING_TIME_IN_MINUTE', 60);

        return Cache::remember(strtoupper($key), Carbon::now()->addMinutes($duration), function () use ($query) {
            return $query();
        });
    }

}
