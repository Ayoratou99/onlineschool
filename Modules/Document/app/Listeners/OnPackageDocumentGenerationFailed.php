<?php

namespace Modules\Document\Listeners;

use Ayoratoumvone\Documentgeneratorx\Events\DocumentGenerationFailed;
use Modules\Document\Models\GeneratedDocument;

class OnPackageDocumentGenerationFailed
{
    public function handle(DocumentGenerationFailed $event): void
    {
        if (!$event->documentId) {
            return;
        }
        GeneratedDocument::where('job_id', $event->documentId)->update([
            'status' => GeneratedDocument::STATUS_FAILED,
            'error_message' => $event->getErrorMessage(),
        ]);
    }
}
