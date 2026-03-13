<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Run on tenant DB only. Ordered list, full CRUD. */
    public function up(): void
    {
        Schema::create('portail_menu_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label', 80);
            $table->string('url', 500)->nullable();
            $table->enum('type', ['internal', 'external'])->default('internal');
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->enum('build_in_page', ['home', 'about', 'contact', 'services', 'products', 'blog', 'gallery', 'events', 'faq', 'pricing', 'testimonials', 'team', '404', 'coming-soon','formations'])->nullable();
            $table->integer('ordre');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('portail_menu_items')->insert([
            'id' => '00000000-0000-0000-0000-000000000004',
            'label' => 'EXAMPLE_LABEL',
            'url' => 'EXAMPLE_URL',
            'type' => 'internal',
            'target' => '_self',
            'build_in_page' => 'home',
            'ordre' => 1,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portail_menu_items');
    }
};
