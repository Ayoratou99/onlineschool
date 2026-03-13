<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->foreignUuid('ue_id')->constrained('unites_enseignement')->cascadeOnDelete();
            $table->foreignUuid('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->unsignedInteger('ordre')->default(0);
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_details');
    }
};
