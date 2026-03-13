<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batiments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('etablissement_id')->constrained('etablissements')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('libelle', 255);
            $table->string('adresse', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batiments');
    }
};
