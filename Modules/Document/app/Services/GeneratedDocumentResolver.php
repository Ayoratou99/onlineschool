<?php

namespace Modules\Document\Services;

use App\Contracts\GeneratedDocumentResolverInterface;
use Modules\Document\Models\GeneratedDocument;

class GeneratedDocumentResolver implements GeneratedDocumentResolverInterface
{
    public function getGeneratedDocument(string $id): ?object
    {
        $doc = GeneratedDocument::find($id);
        if (!$doc) {
            return null;
        }
        return (object) [
            'id'                    => $doc->id,
            'status'                => $doc->status,
            'error_message'        => $doc->error_message,
            'generated_file_path'   => $doc->generated_file_path,
            'template_document_id'  => $doc->template_document_id,
            'document_paperless_id' => $doc->document_paperless_id,
            'created_at'            => $doc->created_at?->toIso8601String(),
            'updated_at'            => $doc->updated_at?->toIso8601String(),
        ];
    }
}
