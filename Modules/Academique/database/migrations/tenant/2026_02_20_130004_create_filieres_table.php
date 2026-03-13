<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filieres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cycle_id')->constrained('cycles')->cascadeOnDelete();
            $table->foreignUuid('domaine_id')->constrained('domaines')->cascadeOnDelete();
            $table->uuid('responsable_id')->index();
            $table->string('code', 50)->unique();
            $table->string('libelle', 255);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
