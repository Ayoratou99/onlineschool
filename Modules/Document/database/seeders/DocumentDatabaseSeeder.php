<?php

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Document\Database\Seeders\TemplateDocumentSeeder;

class DocumentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TemplateDocumentSeeder::class,
        ]);
    }
}
