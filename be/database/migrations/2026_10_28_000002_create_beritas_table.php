<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('user_uuid')->nullable();
            $table->uuid('category_uuid');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 255)->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('video')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->string('tagging')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled', 'pending'])->default('draft');
            $table->enum('search_engine', ['index', 'noindex'])->default('index');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->foreign('category_uuid')->references('uuid')->on('categories')->cascadeOnDelete();
            $table->index(['status', 'scheduled_at']);
            $table->index(['category_uuid', 'status']);
        });

        $now = now();
        foreach ($this->routePermissions() as $route => $permission) {
            if (! DB::table('routes')->where('route', $route)->exists()) {
                DB::table('routes')->insert([
                    'route' => $route,
                    'permission_name' => $permission,
                    'status' => true,
                    'description' => 'Akses modul berita.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $agendaMenu = DB::table('menu_items')->where('route', 'agendas.index')->first();
        if ($agendaMenu && ! DB::table('menu_items')->where('route', 'beritas.index')->exists()) {
            DB::table('menu_items')->insert([
                'name' => 'Berita',
                'icon' => null,
                'route' => 'beritas.index',
                'status' => true,
                'permission_name' => 'agendas.index',
                'menu_group_id' => $agendaMenu->menu_group_id,
                'position' => DB::table('menu_items')
                    ->where('menu_group_id', $agendaMenu->menu_group_id)
                    ->max('position') + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'beritas.index')->delete();
        DB::table('routes')->whereIn('route', array_keys($this->routePermissions()))->delete();
        Schema::dropIfExists('beritas');
    }

    private function routePermissions(): array
    {
        return [
            'beritas.index' => 'agendas.index',
            'beritas.create' => 'agendas.create',
            'beritas.store' => 'agendas.store',
            'beritas.show' => 'agendas.index',
            'beritas.edit' => 'agendas.update',
            'beritas.update' => 'agendas.update',
            'beritas.destroy' => 'agendas.destroy',
        ];
    }
};
