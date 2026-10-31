<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_identities', function (Blueprint $table) {
            $table->dropColumn([
                'hero_card_image',
                'hero_card_kicker',
                'hero_card_title',
                'hero_card_venue',
                'hero_card_date',
                'hero_card_chip',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('website_identities', function (Blueprint $table) {
            $table->string('hero_card_image')->nullable();
            $table->string('hero_card_kicker')->nullable();
            $table->string('hero_card_title')->nullable();
            $table->string('hero_card_venue')->nullable();
            $table->string('hero_card_date')->nullable();
            $table->string('hero_card_chip')->nullable();
        });
    }
};
