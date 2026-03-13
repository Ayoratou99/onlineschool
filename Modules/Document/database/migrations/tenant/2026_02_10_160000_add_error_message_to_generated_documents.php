<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. */
    public function up(): void
    {
        Schema::table('generated_documents', function (Blueprint $table) {
            $table->text('error_message')->nullable()->after('status')->comment('Error message when status is failed');
        });
    }

    public function down(): void
    {
        Schema::table('generated_documents', function (Blueprint $table) {
            $table->dropColumn('error_message');
        });
    }
};
