<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. Ordered list, full CRUD. */
    public function up(): void
    {
        Schema::create('portail_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 50); // texte, image, galerie, stats, colonnes, actualites, contact
            $table->string('titre', 200)->nullable();
            $table->json('contenu')->nullable();
            $table->integer('ordre');
            $table->boolean('is_active')->default(true);
            $table->string('bg_couleur', 30)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_sections');
    }
};
