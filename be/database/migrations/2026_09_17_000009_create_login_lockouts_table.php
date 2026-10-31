<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_lockouts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10);
            $table->string('value')->index();
            $table->unsignedInteger('attempts')->default(0);
            $table->string('reason')->nullable();
            $table->timestamp('blocked_until')->nullable()->index();
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();

            $table->unique(['type', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_lockouts');
    }
};
