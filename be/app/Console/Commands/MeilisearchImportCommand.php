<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Document;
use App\Models\Category;
use App\Models\Aduan;
use Illuminate\Console\Command;

class MeilisearchImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:import 
                             {model? : Model to import (agenda, page, faq, document, category, aduan, all)}
                            {--fresh : Flush index before importing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import models to Meilisearch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $fresh = $this->option('fresh');

        $models = [
            'agenda' => Agenda::class,
            'page' => Page::class,
            'faq' => Faq::class,
            'document' => Document::class,
            'category' => Category::class,
            'aduan' => Aduan::class,
        ];

        if ($model === 'all' || !$model) {
            $selectedModels = $models;
        } elseif (isset($models[$model])) {
            $selectedModels = [$model => $models[$model]];
        } else {
            $this->error("Unknown model: {$model}");
            $this->info("Available: " . implode(', ', array_keys($models)) . ", all");
            return 1;
        }

        foreach ($selectedModels as $name => $class) {
            $this->importModel($name, $class, $fresh);
        }

        return 0;
    }

    private function importModel(string $name, string $class, bool $fresh): void
    {
        $this->info("Importing {$name}...");

        try {
            if ($fresh) {
                $this->info("  Flushing index...");
                $class::search('')->delete();
            }

            $count = $class::query()
                ->where(function ($q) use ($class) {
                    // Only import searchable records
                    if (method_exists($class, 'shouldBeSearchable')) {
                        $instance = new $class();
                        // We can't easily query shouldBeSearchable, so import all and let Scout filter
                    }
                })
                ->chunkById(100, function ($records) use ($name) {
                    foreach ($records as $record) {
                        if ($record->shouldBeSearchable()) {
                            $record->searchable();
                        }
                    }
                    $this->info("  Processed chunk for {$name}");
                });

            $this->info("  {$name} imported successfully!");
        } catch (\Throwable $e) {
            $this->error("  Failed to import {$name}: {$e->getMessage()}");
        }
    }
}
