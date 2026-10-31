<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

/**
 * Ringkas boilerplate DB::beginTransaction / commit / rollback
 * yang berulang di setiap Admin CRUD controller.
 */
trait HandlesTransactions
{
    /**
     * Jalankan $callback dalam transaction. Return redirect dengan flash message.
     */
    protected function transactional(callable $callback, string $successMessage, ?string $errorMessage = null): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $callback();
            DB::commit();
            return redirect()->back()->with('success', $successMessage);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $errorMessage ?? $e->getMessage());
        }
    }

    /**
     * Varian untuk redirect ke route tertentu (mis. pages.index)
     */
    protected function transactionalToRoute(string $route, callable $callback, string $successMessage, ?string $errorMessage = null): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $callback();
            DB::commit();
            return redirect()->route($route)->with('success', $successMessage);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $errorMessage ?? $e->getMessage());
        }
    }
}
