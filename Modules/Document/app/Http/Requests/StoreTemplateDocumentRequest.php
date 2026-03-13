<?php

namespace Modules\Document\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Document\Models\TemplateDocument;

class StoreTemplateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', TemplateDocument::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('template_documents', 'name')->whereNull('deleted_at')],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => [
                'required',
                'file',
                'mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max:51200',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du modèle est requis.',
            'name.unique' => 'Un modèle avec ce nom existe déjà.',
            'file.required' => 'Le fichier DOCX est requis.',
            'file.mimetypes' => 'Le fichier doit être au format DOCX.',
            'file.max' => 'Le fichier ne doit pas dépasser 50 Mo.',
        ];
    }
}
