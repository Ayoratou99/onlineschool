<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. Posts: CRUD, epingler, cibler. image_url = tenant_bucket/... path. */
    public function up(): void
    {
        Schema::create('portail_actualites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('auteur_id')->index();
            $table->string('titre', 300);
            $table->text('contenu');
            $table->string('image_url')->nullable();
            $table->string('categorie', 30); // info, urgent, evenement, resultat
            $table->string('ciblage', 30)->default('tous'); // tous, etudiants, staff
            $table->boolean('is_epingle')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('publie_le')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_actualites');
    }
};
