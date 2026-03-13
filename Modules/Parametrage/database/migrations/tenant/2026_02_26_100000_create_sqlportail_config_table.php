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
        Schema::create('portail_config', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom_etablissement', 200);
            $table->string('slogan', 300)->nullable();
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('couleur_primaire', 7)->default('#0B3D6E');
            $table->string('couleur_secondaire', 7)->default('#C8A84B');
            $table->string('couleur_texte', 7)->default('#18182E');
            $table->timestamps();
        });

        DB::table('portail_config')->insert([
            'id' => '00000000-0000-0000-0000-000000000001',
            'nom_etablissement' => 'EXAMPLE_ETABLISSEMENT',
            'slogan' => 'EXAMPLE_SLOGAN',
            'couleur_primaire' => '#0B3D6E',
            'couleur_secondaire' => '#C8A84B',
            'couleur_texte' => '#18182E',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_config');
    }
};
