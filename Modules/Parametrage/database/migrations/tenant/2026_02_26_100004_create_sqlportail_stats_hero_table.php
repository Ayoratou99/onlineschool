<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Run on tenant DB only. Ordered list, full CRUD. */
    public function up(): void
    {
        Schema::create('portail_stats_hero', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('valeur', 30);
            $table->string('libelle', 80);
            $table->integer('ordre');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('portail_stats_hero')->insert([
            'id' => '00000000-0000-0000-0000-000000000005',
            'valeur' => 'EXAMPLE_VALEUR',
            'libelle' => 'EXAMPLE_LIBELLE',
            'ordre' => 1,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_stats_hero');
    }
};
