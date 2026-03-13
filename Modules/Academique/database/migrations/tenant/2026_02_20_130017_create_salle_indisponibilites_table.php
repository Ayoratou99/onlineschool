<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salle_indisponibilites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('motif', 500);
            $table->uuid('created_by')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salle_indisponibilites');
    }
};
