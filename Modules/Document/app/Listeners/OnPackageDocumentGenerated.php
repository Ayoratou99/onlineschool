<?php

namespace Modules\Document\Listeners;

use App\Events\DocumentGenerated as AppDocumentGenerated;
use Ayoratoumvone\Documentgeneratorx\Events\DocumentGenerated as PackageDocumentGenerated;
use Modules\Document\Models\GeneratedDocument;

class OnPackageDocumentGenerated
{
    public function handle(PackageDocumentGenerated $event): void
    {
        if (!$event->documentId) {
            return;
        }
        $record = GeneratedDocument::where('job_id', $event->documentId)->first();
        if (!$record) {
            return;
        }
        $record->update([
            'status' => GeneratedDocument::STATUS_COMPLETED,
            'generated_file_path' => $event->outputPath,
        ]);
        event(new AppDocumentGenerated($record->fresh()));
    }
}
