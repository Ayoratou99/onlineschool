<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. */
    public function up(): void
    {
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('template_document_id');
            $table->foreign('template_document_id')->references('id')->on('template_documents')->onDelete('cascade');
            $table->json('variables')->comment('Les variables du document');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('job_id')->nullable()->index();
            $table->string('generated_file_path')->comment('Le chemin du document généré');
            $table->string('document_paperless_id', 500)->nullable()->comment('ID du document dans Paperless après upload');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};
