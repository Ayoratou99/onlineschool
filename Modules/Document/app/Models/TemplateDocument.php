<?php

namespace Modules\Document\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TemplateDocument extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected static array $ignoreActivityAttributes = ['path'];

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'template_documents';

    protected $fillable = ['name', 'description', 'path'];

    protected $casts = [];

    public function generatedDocuments()
    {
        return $this->hasMany(GeneratedDocument::class, 'template_document_id');
    }

    public function getFullPath(): string
    {
        return storage_path('app/' . ltrim($this->path, '/'));
    }
}
