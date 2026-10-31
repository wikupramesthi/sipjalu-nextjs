<?php

namespace App\Traits;

use App\Jobs\OptimizeImageJob;
use Illuminate\Support\Facades\Storage;

trait ImageOptimizable
{
    /**
     * Get the paths of images to optimize when the model is saved.
     * 
     * @return array<string, string> Array of [attribute => disk]
     */
    abstract protected function getImageAttributes(): array;

    /**
     * Boot the trait.
     */
    protected static function bootImageOptimizable(): void
    {
        static::saved(function ($model) {
            $model->queueImageOptimization();
        });
    }

    /**
     * Queue optimization for all image attributes that have changed.
     */
    protected function queueImageOptimization(): void
    {
        $attributes = $this->getImageAttributes();

        foreach ($attributes as $attribute => $disk) {
            $value = $this->getAttribute($attribute);

            if ($value && $this->isImageAttributeDirty($attribute)) {
                $path = $this->resolveImagePath($value, $disk);
                
                if ($path) {
                    OptimizeImageJob::dispatch($path, $disk)
                        ->onQueue('image-optimization');
                }
            }
        }
    }

    /**
     * Check if the image attribute was changed.
     */
    protected function isImageAttributeDirty(string $attribute): bool
    {
        return $this->isDirty($attribute) || $this->wasChanged($attribute);
    }

    /**
     * Resolve the full path from the stored value.
     */
    protected function resolveImagePath(string $value, string $disk): ?string
    {
        if (str_starts_with($value, 'http')) {
            return null;
        }

        return ltrim($value, '/');
    }
}