<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Keycloak Unique Identifier (Subject)
            // This is the primary link between Laravel and Keycloak
            $table->string('keycloak_id')->unique()->nullable()->index();
            
            // Basic Info (Mirrored from Keycloak for performance)
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            // School Logic Columns
            $table->enum('user_type', ['admin','academic_staff', 'student'])
                  ->default('student');
            $table->boolean('is_active')->default(true);
            // Housekeeping
            $table->softDeletes(); // Adds 'deleted_at' column
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
