<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Logic bisnis modul Layanan (dipakai controller web & API).
 * Menangani transaksi DB + upload/hapus file gambar secara atomik.
 */
class ServiceManager
{
    public const IMAGE_DISK = 'public';

    public const IMAGE_DIRECTORY = 'services';

    public function create(array $data, ?UploadedFile $image = null): Service
    {
        $path = null;

        DB::beginTransaction();

        try {
            if ($image) {
                $path = $image->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
            }

            $service = Service::create([
                'name' => $data['name'],
                'category' => $data['category'],
                'url' => $data['url'] ?? null,
                'image' => $path,
                'description' => HtmlSanitizer::clean($data['description'] ?? null),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            DB::commit();

            return $service;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->deleteFile($path);

            throw $e;
        }
    }

    public function update(Service $service, array $data, ?UploadedFile $image = null, bool $removeImage = false): Service
    {
        $oldPath = $service->image;
        $newPath = null;

        DB::beginTransaction();

        try {
            if ($image) {
                $newPath = $image->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
                $service->image = $newPath;
            } elseif ($removeImage) {
                $service->image = null;
            }

            $service->fill([
                'name' => $data['name'],
                'category' => $data['category'],
                'url' => $data['url'] ?? null,
                'description' => HtmlSanitizer::clean($data['description'] ?? null),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ])->save();

            DB::commit();

            if (($newPath || $removeImage) && $oldPath && $oldPath !== $newPath) {
                $this->deleteFile($oldPath);
            }

            return $service->refresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->deleteFile($newPath);

            throw $e;
        }
    }

    public function delete(Service $service): void
    {
        $path = $service->image;

        DB::beginTransaction();

        try {
            $service->delete();

            DB::commit();
            $this->deleteFile($path);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param  array<int, string>  $ids
     */
    public function bulkDelete(array $ids): int
    {
        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);
        $items = Service::whereIn('uuid', $ids)->get();

        if ($items->isEmpty()) {
            return 0;
        }

        $paths = $items->pluck('image')->filter()->all();

        DB::beginTransaction();

        try {
            Service::whereIn('uuid', $items->pluck('uuid')->all())->delete();

            DB::commit();

            foreach ($paths as $path) {
                $this->deleteFile($path);
            }

            return $items->count();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function deleteFile(?string $path): void
    {
        if ($path && Storage::disk(self::IMAGE_DISK)->exists($path)) {
            Storage::disk(self::IMAGE_DISK)->delete($path);
        }
    }
}
