<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('niveau_id')->constrained('niveaux')->cascadeOnDelete();
            $table->uuid('annee_academique_id')->index();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->uuid('valide_par')->nullable()->index();
            $table->timestamp('valide_le')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
