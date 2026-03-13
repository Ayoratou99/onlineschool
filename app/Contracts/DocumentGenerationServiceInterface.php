<?php

namespace App\Contracts;

interface DocumentGenerationServiceInterface
{
    public function generate(string $template_document_id, string $generated_file_path, array $variables): string;
}
