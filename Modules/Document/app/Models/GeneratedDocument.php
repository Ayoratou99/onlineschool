<?php

namespace Modules\Document\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;
use App\Traits\SoftDeletesWithUniqueFields;

#[OA\Schema(
    schema: 'GeneratedDocument',
    title: 'GeneratedDocument Model',
    description: 'Document généré à partir d\'un modèle (template).',
    required: ['id', 'template_document_id', 'status'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'template_document_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'variables', type: 'object', description: 'Variables utilisées pour la génération'),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'completed', 'failed']),
        new OA\Property(property: 'error_message', type: 'string', nullable: true, description: 'Error message when status is failed'),
        new OA\Property(property: 'job_id', type: 'string', nullable: true),
        new OA\Property(property: 'generated_file_path', type: 'string', nullable: true),
        new OA\Property(property: 'document_paperless_id', type: 'string', nullable: true),
    ],
    additionalProperties: false
)]
class GeneratedDocument extends Model
{
    use HasUuids, LogsActivity, SoftDeletes, SoftDeletesWithUniqueFields;

    protected static array $ignoreActivityAttributes = ['variables', 'generated_file_path'];

    protected $table = 'generated_documents';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'template_document_id',
        'variables',
        'status',
        'error_message',
        'job_id',
        'generated_file_path',
        'document_paperless_id',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public function templateDocument()
    {
        return $this->belongsTo(TemplateDocument::class, 'template_document_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    protected function getUniqueFields(): array
    {
        return ['template_document_id', 'generated_file_path'];
    }
}
