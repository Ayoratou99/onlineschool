<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. */
    public function up(): void
    {
        Schema::create('baremes_mention', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('annee_academique_id')->constrained('annees_academiques')->cascadeOnDelete();
            $table->string('mention', 50);
            $table->decimal('bareme_min', 5, 2);
            $table->decimal('bareme_max', 5, 2);
            $table->integer('ordre')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baremes_mention');
    }
};
