<?php

namespace App\Services;

use App\Contracts\DocumentManagementInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PaperlessService implements DocumentManagementInterface
{
    protected $http;

    public function __construct()
    {
        $this->http = Http::withHeaders([
                'Authorization' => 'Token ' . config('services.paperless.token')
            ])
            ->baseUrl(config('services.paperless.url'));
    }

    public function upload(UploadedFile $file, string $folder = '', array $metadata = []): array
    {
        $filename = $metadata['title'] ?? $file->getClientOriginalName();
        $contents = fopen($file->getRealPath(), 'r');

        $request = $this->http
            ->asMultipart();

        $request = $request->attach('document', $contents, $filename);

        if ($folder && is_numeric($folder)) {
            $request = $request->attach('document_type', $folder);
        }

        $response = $request->post("documents/post_document/");

        $status = $response->status();
        $body = $response->body();
        $result = $response->json();

        if (is_string($result)) {
            return ['task_id' => $result];
        }

        if (is_array($result) && (isset($result['id']) || isset($result['task_id']))) {
            return $result;
        }

        $bodyTrimmed = is_string($body) ? trim($body) : '';
        if ($bodyTrimmed !== '' && preg_match('/^[0-9a-fA-F\-]{36}$/', $bodyTrimmed)) {
            return ['task_id' => $bodyTrimmed];
        }
        return $result ?? [];
    }

    public function search(string $query): Collection
    {
        $response = $this->http->get('documents/', ['query' => $query]);
        return collect($response->json()['results'] ?? []);
    }

    public function createFolder(string $name): array
    {
        $response = $this->http->post('document_types/', [
            'name' => $name,
            'matching_algorithm' => 0,
            'is_insensitive' => true
        ]);

        if ($response->failed()) {
            throw new \Exception("Erreur lors de la création du dossier dans Paperless: " . $response->body());
        }

        return $response->json();
    }

    public function listDocumentTypes(): array
    {
        $all = [];
        $next = 'document_types/';
        while ($next) {
            $response = $this->http->get($next);
            if ($response->failed()) {
                return [];
            }
            $data = $response->json();
            $results = $data['results'] ?? [];
            foreach ($results as $item) {
                $all[] = ['id' => (int) $item['id'], 'name' => (string) ($item['name'] ?? '')];
            }
            $next = $data['next'] ?? null;
        }
        return $all;
    }

    public function ensureDocumentTypeExists(string $name): int
    {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('Document type name cannot be empty.');
        }
        $list = $this->listDocumentTypes();
        foreach ($list as $item) {
            if (strcasecmp($item['name'], $name) === 0) {
                return $item['id'];
            }
        }
        $created = $this->createFolder($name);
        return (int) ($created['id'] ?? 0);
    }

    public function getDocumentIdFromTaskId(string $taskId): ?int
    {
        $response = $this->http->get('tasks/', ['task_id' => $taskId]);
        if ($response->failed()) {
            return null;
        }
        $data = $response->json();
        $tasks = isset($data['results']) ? $data['results'] : (isset($data[0]) ? $data : []);
        $task = $tasks[0] ?? null;
        if (!$task) {
            return null;
        }
        $status = strtoupper((string) ($task['status'] ?? ''));
        if ($status !== 'SUCCESS' && $status !== 'SUCCESSFUL') {
            return null;
        }
        // Priority: related_document (integer document id) > parse result string > other fields
        if (isset($task['related_document']) && is_numeric($task['related_document'])) {
            return (int) $task['related_document'];
        }
        $result = $task['result'] ?? $task['output'] ?? $task['document_id'] ?? null;
        if ($result === null) {
            return null;
        }
        if (is_numeric($result)) {
            return (int) $result;
        }
        if (is_array($result) && isset($result['document_id'])) {
            return (int) $result['document_id'];
        }
        if (is_array($result) && isset($result['id'])) {
            return (int) $result['id'];
        }
        if (is_string($result)) {
            // Parse "Success. New document id 123 created" or similar
            if (preg_match('/document\s+id\s+(\d+)/i', $result, $m)) {
                return (int) $m[1];
            }
            $trimmed = trim($result);
            if (is_numeric($trimmed)) {
                return (int) $trimmed;
            }
            // Paperless may return document URL: /api/documents/123/ or full URL
            if (preg_match('#/documents/(\d+)/?#', $result, $m)) {
                return (int) $m[1];
            }
        }
        return null;
    }

    /**
     * Get task status. Returns 'success'|'failure'|'pending'|'not_found'.
     */
    public function getTaskStatus(string $taskId): string
    {
        $response = $this->http->get('tasks/', ['task_id' => $taskId]);
        if ($response->failed()) {
            return 'not_found';
        }
        $data = $response->json();
        $results = $data['results'] ?? $data;
        $task = is_array($results) && isset($results[0]) ? $results[0] : (is_array($results) ? null : $results);
        if (!$task || !is_array($task)) {
            return 'not_found';
        }
        $status = strtoupper((string) ($task['status'] ?? ''));
        if ($status === 'SUCCESS' || $status === 'SUCCESSFUL') {
            return 'success';
        }
        if ($status === 'FAILURE' || $status === 'FAILED') {
            return 'failure';
        }
        return 'pending';
    }

    public function getMetadata(int|string $id): array
    {
        return $this->http->get("documents/{$id}/")->json();
    }

    /**
     * Download a document from Paperless.
     * @param bool $original If true, downloads original file; otherwise downloads archived (PDF) version.
     * @return array ['content' => string, 'content_type' => string, 'filename' => string]
     */
    public function download(int|string $id, bool $original = true): array
    {
        $params = $original ? ['original' => 'true'] : [];
        $response = $this->http->get("documents/{$id}/download/", $params);

        $contentType = $response->header('Content-Type') ?? 'application/octet-stream';
        $disposition = $response->header('Content-Disposition') ?? '';
        $filename = 'document';

        // Parse filename from Content-Disposition: attachment; filename="name.ext"
        if (preg_match('/filename="([^"]+)"/', $disposition, $m)) {
            $filename = $m[1];
        }

        return [
            'content' => $response->body(),
            'content_type' => $contentType,
            'filename' => $filename,
        ];
    }

    public function delete(int|string $id): bool
    {
        return $this->http->delete("documents/{$id}/")->successful();
    }

}
