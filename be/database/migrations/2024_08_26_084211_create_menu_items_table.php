<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ManagementAccess\MenuGroup;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            // $table->uuid('id')->primary();
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('route');
            $table->boolean('status')->default(true);
            $table->string('permission_name');
            $table->foreignIdFor(MenuGroup::class);
            $table->integer('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('menu_items');
    }
};
