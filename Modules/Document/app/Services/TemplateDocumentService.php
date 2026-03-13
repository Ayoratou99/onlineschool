<?php

namespace Modules\Document\Services;

use App\Services\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Modules\Document\Models\TemplateDocument;

class TemplateDocumentService extends BaseService
{
    /** Path under disk 'local' (storage/app). Full path: storage/app/private/templates/ */
    public const TEMPLATES_DISK_PATH = 'private/templates';

    public function __construct(TemplateDocument $model)
    {
        parent::__construct($model);
    }

    public static function buildPathFromName(string $name): string
    {
        $filename = Str::upper(Str::slug($name, '_')) . '.docx';
        return self::TEMPLATES_DISK_PATH . '/' . $filename;
    }

    /**
     * Store template file under storage/app (disk 'local').
     * $path is relative to storage/app, e.g. "private/templates/MyTemplate.docx".
     */
    public function storeFile(UploadedFile $file, string $path): string
    {
        return $file->storeAs(
            dirname($path),
            basename($path),
            ['disk' => 'local']
        );
    }

    public function replaceFile(UploadedFile $file, string $existingPath): void
    {
        $this->storeFile($file, $existingPath);
    }

    public function deleteFile(string $path): bool
    {
        return \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
    }
}
