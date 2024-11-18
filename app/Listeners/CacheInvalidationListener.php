<?php

namespace App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CacheInvalidationListener
{
    /**
     * Handle model events to clear cache.
     *
     * @param  string  $event
     * @param  array   $models
     * @return void
     */
    public function handle($event, $models)
    {
        foreach ($models as $model) {
            if ($model instanceof Model) {
                $this->invalidateCache($model);
            }
        }
    }

    /**
     * Invalidate cache based on dynamic keys from the model.
     *
     * @param Model $model
     */
    protected function invalidateCache(Model $model): void
    {
        // Check if the model has a getCacheKeys method
        if (method_exists($model, 'getCacheKeys')) {
            foreach ($model->getCacheKeys() as $cacheKey) {

                $formattedCacheKey = strtoupper(Str::slug($cacheKey, '_'));

                Log::info('Cache Clear Info',
                    [
                        'cache key' => $formattedCacheKey,
                        'slug' => $model->slug
                    ]);


                if (Cache::forget($formattedCacheKey)){
                    Log::info('Key that forget', ['key' => $formattedCacheKey]);
                }
            }
        }
    }
}
