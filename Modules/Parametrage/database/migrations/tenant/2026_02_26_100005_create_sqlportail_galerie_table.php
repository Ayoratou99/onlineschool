<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. Ordered list, full CRUD. url = tenant_bucket/... path. */
    public function up(): void
    {
        Schema::create('portail_galerie', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url');
            $table->string('legende', 200)->nullable();
            $table->string('alt_text', 200)->nullable();
            $table->integer('ordre');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_galerie');
    }
};
