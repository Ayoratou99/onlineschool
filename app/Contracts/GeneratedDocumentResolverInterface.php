<?php

namespace App\Contracts;

interface GeneratedDocumentResolverInterface
{
    /**
     * Resolve a generated document by id. Returns null if not found.
     * Returns a plain object with at least: id, status, generated_file_path, template_document_id, created_at.
     *
     * @return object|null
     */
    public function getGeneratedDocument(string $id): ?object;
}
