<?php

namespace Modules\Document\Services;

use App\Contracts\DocumentGenerationServiceInterface;
use Ayoratoumvone\Documentgeneratorx\Jobs\GenerateDocument;
use Modules\Document\Models\GeneratedDocument;
use Modules\Document\Models\TemplateDocument;

class DocumentGenerationService implements DocumentGenerationServiceInterface
{
    public function generate(string $template_document_id, string $generated_file_path, array $variables): string
    {
        $template = TemplateDocument::findOrFail($template_document_id);
        $outputPath = $this->resolveOutputPath($generated_file_path);

        $record = GeneratedDocument::create([
            'template_document_id' => $template->id,
            'variables' => $variables,
            'status' => GeneratedDocument::STATUS_PENDING,
            'generated_file_path' => $generated_file_path,
        ]);

        $job = new GenerateDocument(
            templatePath: $template->getFullPath(),
            variables: $variables,
            outputPath: $outputPath
        );

        $record->update(['job_id' => $job->documentId]);
        dispatch($job);

        return $record->id;
    }

    private function resolveOutputPath(string $generated_file_path): string
    {
        $path = ltrim(str_replace('\\', '/', $generated_file_path), '/');
        if ($path === '' || preg_match('#^[a-zA-Z]:/#', $path)) {
            return $generated_file_path;
        }
        return storage_path('app/' . $path);
    }
}
