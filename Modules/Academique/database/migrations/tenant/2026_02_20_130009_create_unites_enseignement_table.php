<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unites_enseignement', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('semestre_id')->constrained('semestres')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('libelle', 255);
            $table->string('type', 30); // fondamentale / transversale / optionnelle / stage
            $table->decimal('credits', 8, 2);
            $table->decimal('coefficient', 8, 2);
            $table->boolean('est_capitalisable')->default(true);
            $table->boolean('est_compensable')->default(true);
            $table->decimal('note_minimale', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unites_enseignement');
    }
};
