<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\FailedLogin;
use App\Models\LoginActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuditObserver
{
    protected static bool $recording = false;

    public function handle(string $action, Model $model): void
    {
        if (static::$recording) {
            return;
        }

        if (! Str::startsWith($model::class, 'App\\Models\\')) {
            return;
        }

        if ($model instanceof AuditLog || $model instanceof LoginActivity || $model instanceof FailedLogin) {
            return;
        }

        $old = [];
        $new = [];

        if ($action === 'created') {
            $new = $this->filter($model, $model->getAttributes());
        } elseif ($action === 'updated') {
            $original = $model->getOriginal();

            foreach ($model->getChanges() as $key => $value) {
                if (in_array($key, ['updated_at', 'remember_token'], true)) {
                    continue;
                }

                $old[$key] = $original[$key] ?? null;
                $new[$key] = $value;
            }
        } elseif ($action === 'deleted') {
            $old = $this->filter($model, $model->getOriginal() ?: $model->getAttributes());
        } elseif ($action === 'restored') {
            $new = $this->filter($model, $model->getAttributes());
        }

        if (empty($old) && empty($new)) {
            return;
        }

        // Invalidate dashboard/sidebar cache untuk model penting
        if ($model instanceof \App\Models\Aduan || $model instanceof \App\Models\VisitorLog || $model instanceof \App\Models\ManagementAccess\MenuGroup || $model instanceof \App\Models\ManagementAccess\MenuItem) {
            Cache::flush();
        }

        static::$recording = true;

        try {
            $request = app()->runningInConsole() ? null : request();
            $user = auth()->guard('web')->user();

            AuditLog::create([
                'user_uuid' => $user?->getAuthIdentifier(),
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'event' => $action,
                'auditable_type' => $model::class,
                'auditable_id' => (string) $model->getKey(),
                'auditable_label' => $this->label($model),
                'old_values' => $old ?: null,
                'new_values' => $new ?: null,
                'url' => $request?->fullUrl(),
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        } finally {
            static::$recording = false;
        }
    }

    protected function filter(Model $model, array $attributes): array
    {
        $hidden = array_merge($model->getHidden(), [
            'password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'token',
        ]);

        $result = [];

        foreach ($attributes as $key => $value) {
            if (in_array($key, $hidden, true)) {
                continue;
            }

            if (is_null($value) || is_scalar($value)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    protected function label(Model $model): string
    {
        foreach (['title', 'name', 'subject', 'judul', 'email', 'slug'] as $attribute) {
            $value = $model->getAttribute($attribute);

            if (! empty($value) && is_scalar($value)) {
                return Str::limit((string) $value, 100);
            }
        }

        return class_basename($model) . ' #' . $model->getKey();
    }
}
