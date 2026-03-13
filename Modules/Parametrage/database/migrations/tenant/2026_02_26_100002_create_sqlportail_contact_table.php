<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. Singleton: 1 row, update only. */
    public function up(): void
    {
        Schema::create('portail_contact', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('adresse')->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('horaires_semaine', 100)->nullable();
            $table->string('horaires_samedi', 100)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->timestamps();
        });

        DB::table('portail_contact')->insert([
            'id' => '00000000-0000-0000-0000-000000000003',
            'adresse' => 'EXAMPLE_ADRESSE',
            'telephone' => 'EXAMPLE_TELEPHONE',
            'email' => 'EXAMPLE_EMAIL',
            'horaires_semaine' => 'EXAMPLE_HORAIRES_SEMAINE',
            'horaires_samedi' => 'EXAMPLE_HORAIRES_SAMEDI',
            'facebook_url' => 'EXAMPLE_FACEBOOK_URL',
            'twitter_url' => 'EXAMPLE_TWITTER_URL',
            'linkedin_url' => 'EXAMPLE_LINKEDIN_URL',
            'instagram_url' => 'EXAMPLE_INSTAGRAM_URL',
            'google_maps_url' => 'EXAMPLE_GOOGLE_MAPS_URL',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_contact');
    }
};
