<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Run on tenant DB only. Singleton: 1 row, update only. */
    public function up(): void
    {
        Schema::create('portail_hero', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image_url')->nullable();
            $table->string('badge_texte', 100)->nullable();
            $table->string('titre', 300);
            $table->text('sous_titre')->nullable();
            $table->string('bouton_principal', 100)->nullable();
            $table->string('bouton_secondaire', 100)->nullable();
            $table->timestamps();
        });

        DB::table('portail_hero')->insert([
            'id' => '00000000-0000-0000-0000-000000000002',
            'titre' => 'EXAMPLE_TITRE',
            'sous_titre' => 'EXAMPLE_SOUS_TITRE',
            'bouton_principal' => 'EXAMPLE_BOUTON_PRINCIPAL',
            'bouton_secondaire' => 'EXAMPLE_BOUTON_SECONDAIRE',
            'badge_texte' => 'EXAMPLE_BADGE_TEXTE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_hero');
    }
};
