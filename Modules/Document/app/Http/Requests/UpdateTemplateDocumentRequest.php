<?php

namespace Modules\Document\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Document\Models\TemplateDocument;

class UpdateTemplateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('templateDocument'));
    }

    public function rules(): array
    {
        $id = $this->route('templateDocument')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('template_documents', 'name')->ignore($id)->whereNull('deleted_at')],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => [
                'nullable',
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
            'file.mimetypes' => 'Le fichier doit être au format DOCX.',
            'file.max' => 'Le fichier ne doit pas dépasser 50 Mo.',
        ];
    }
}
