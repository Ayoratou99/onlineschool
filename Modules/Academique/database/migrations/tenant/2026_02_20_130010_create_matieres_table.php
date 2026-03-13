<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matieres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ue_id')->constrained('unites_enseignement')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('libelle', 255);
            $table->decimal('credits', 8, 2)->nullable();
            $table->decimal('coefficient', 8, 2);
            $table->unsignedInteger('vh_cm')->default(0);
            $table->unsignedInteger('vh_td')->default(0);
            $table->unsignedInteger('vh_tp')->default(0);
            $table->unsignedInteger('vh_total')->storedAs('vh_cm + vh_td + vh_tp');
            $table->boolean('est_compensable')->default(true);
            $table->decimal('note_eliminatoire', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matieres');
    }
};
