<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emploi_du_temps_exceptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('emploi_du_temps_id')->constrained('emplois_du_temps')->cascadeOnDelete();
            $table->date('date_concernee');
            $table->string('type', 20); // annule / deplace / remplace
            $table->foreignUuid('nouvelle_salle_id')->nullable()->constrained('salles')->nullOnDelete();
            $table->uuid('nouvel_enseignant_id')->nullable()->index();
            $table->time('nouvelle_heure_debut')->nullable();
            $table->time('nouvelle_heure_fin')->nullable();
            $table->string('motif', 500)->nullable();
            $table->uuid('created_by')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps_exceptions');
    }
};
