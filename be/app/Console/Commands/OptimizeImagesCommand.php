<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OptimizeImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize 
                            {--path= : Specific path to optimize (relative to storage/app/public)}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize images in storage using spatie/laravel-image-optimizer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $dryRun = $this->option('dry-run');
        
        $basePath = storage_path('app/public');
        if ($path) {
            $basePath = $basePath . '/' . $path;
        }

        if (!File::exists($basePath)) {
            $this->error("Path does not exist: {$basePath}");
            return 1;
        }

        $this->info("Scanning for images in: {$basePath}");
        
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $files = File::allFiles($basePath);
        $images = array_filter($files, function ($file) use ($extensions) {
            return in_array(strtolower($file->getExtension()), $extensions);
        });

        $this->info("Found " . count($images) . " images to process.");

        if ($dryRun) {
            $this->info("Dry run mode - no changes will be made.");
            foreach ($images as $image) {
                $size = $image->getSize();
                $this->line("Would optimize: {$image->getRelativePathname()} ({$this->formatBytes($size)})");
            }
            return 0;
        }

        $optimizer = OptimizerChainFactory::create();
        $optimized = 0;
        $skipped = 0;
        $errors = 0;
        $totalSaved = 0;

        $progressBar = $this->output->createProgressBar(count($images));
        $progressBar->start();

        foreach ($images as $image) {
            try {
                $originalSize = $image->getSize();
                
                $optimizer->optimize($image->getRealPath());
                
                $newSize = $image->getSize();
                $saved = $originalSize - $newSize;
                
                if ($saved > 0) {
                    $optimized++;
                    $totalSaved += $saved;
                    $this->info("\nOptimized: {$image->getRelativePathname()} - Saved {$this->formatBytes($saved)}");
                } else {
                    $skipped++;
                }
            } catch (\Throwable $e) {
                $errors++;
                $this->error("\nError optimizing {$image->getRelativePathname()}: {$e->getMessage()}");
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("Optimization complete:");
        $this->info("  Optimized: {$optimized}");
        $this->info("  Skipped (no savings): {$skipped}");
        $this->info("  Errors: {$errors}");
        $this->info("  Total space saved: {$this->formatBytes($totalSaved)}");

        return 0;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
