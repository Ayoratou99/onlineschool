<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batiment_id')->constrained('batiments')->cascadeOnDelete();
            $table->foreignUuid('etage_id')->constrained('etages')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('libelle', 255);
            $table->string('type', 30); // cours / amphi / tp / informatique / labo / reunion
            $table->unsignedInteger('capacite');
            $table->boolean('has_projecteur')->default(false);
            $table->boolean('has_climatisation')->default(false);
            $table->boolean('has_tableau_blanc')->default(false);
            $table->boolean('has_internet')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
