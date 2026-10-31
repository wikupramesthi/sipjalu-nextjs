<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Log;

class OptimizeImageJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $path,
        public string $disk = 'public'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fullPath = storage_path("app/{$this->disk}/{$this->path}");

        if (!file_exists($fullPath)) {
            Log::warning("Image not found for optimization: {$fullPath}");
            return;
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowedExtensions)) {
            Log::info("Skipping non-image file: {$fullPath}");
            return;
        }

        try {
            $originalSize = filesize($fullPath);
            
            $optimizer = OptimizerChainFactory::create();
            $optimizer->optimize($fullPath);
            
            $newSize = filesize($fullPath);
            $saved = $originalSize - $newSize;
            $savedPercent = $originalSize > 0 ? round(($saved / $originalSize) * 100, 1) : 0;

            Log::info("Image optimized: {$this->path}", [
                'original_size' => $originalSize,
                'new_size' => $newSize,
                'saved_bytes' => $saved,
                'saved_percent' => $savedPercent,
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed to optimize image: {$this->path}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Re-throw to trigger retry
            throw $e;
        }
    }
}
