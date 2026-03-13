<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batiment_id')->constrained('batiments')->cascadeOnDelete();
            $table->unsignedSmallInteger('numero');
            $table->string('libelle', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etages');
    }
};
