<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_identities', function (Blueprint $table) {
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_highlight')->nullable();
            $table->text('hero_lead')->nullable();
            $table->string('hero_cta_label')->nullable();
            $table->string('hero_cta_url', 500)->nullable();
            $table->string('hero_countdown_label')->nullable();
            $table->dateTime('hero_countdown_target')->nullable();
            $table->string('hero_card_image')->nullable();
            $table->string('hero_card_kicker')->nullable();
            $table->string('hero_card_title')->nullable();
            $table->string('hero_card_venue')->nullable();
            $table->string('hero_card_date')->nullable();
            $table->string('hero_card_chip')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('website_identities', function (Blueprint $table) {
            $table->dropColumn([
                'hero_badge',
                'hero_title',
                'hero_title_highlight',
                'hero_lead',
                'hero_cta_label',
                'hero_cta_url',
                'hero_countdown_label',
                'hero_countdown_target',
                'hero_card_image',
                'hero_card_kicker',
                'hero_card_title',
                'hero_card_venue',
                'hero_card_date',
                'hero_card_chip',
            ]);
        });
    }
};
