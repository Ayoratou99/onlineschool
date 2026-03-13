<?php

namespace App\Contracts;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface DocumentManagementInterface
{
    /**
     * Upload a file. Returns ['task_id' => string] for async processing.
     * Use getDocumentIdFromTaskId() to resolve the document ID at download time.
     */
    public function upload(UploadedFile $file, string $folder, array $metadata = []): array;

    public function search(string $query): Collection;


    public function createFolder(string $name): array;

    public function listDocumentTypes(): array;

    public function ensureDocumentTypeExists(string $name): int;

    public function getMetadata(int|string $id): array;

    public function getDocumentIdFromTaskId(string $taskId): ?int;

    /**
     * Get task status. Returns 'success'|'failure'|'pending'|'not_found'.
     */
    public function getTaskStatus(string $taskId): string;

    /**
     * Download a document.
     * @param bool $original If true, downloads original file; otherwise downloads archived (PDF) version.
     * @return array ['content' => string, 'content_type' => string, 'filename' => string]
     */
    public function download(int|string $id, bool $original = true): array;

    public function delete(int $id): bool;
}
