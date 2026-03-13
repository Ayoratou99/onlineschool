<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();

            $table->string('action');
            $table->string('entity')->nullable();

            $table->string('subject_type')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->string('subject_name')->nullable();

            $table->json('properties')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method')->nullable();
            $table->text('url')->nullable();
            $table->json('request_data')->nullable();

            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('batch_id')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['entity', 'action']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('batch_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
