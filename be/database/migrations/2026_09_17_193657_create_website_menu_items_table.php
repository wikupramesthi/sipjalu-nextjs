<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_menu_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('website_menu_id')
                ->constrained('website_menus')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('name');
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->json('route_params')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('target_blank')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('position')->default(0);
            $table->text('description')->nullable();

            $table->timestamps();

            // Self-referencing foreign key
            $table->foreign('parent_id')
                ->references('id')
                ->on('website_menu_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_menu_items');
    }
};
