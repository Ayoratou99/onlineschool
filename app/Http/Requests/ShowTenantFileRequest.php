<?php

namespace App\Http\Requests;

use App\Services\TenantStorageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ShowTenantFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'path' => ['required', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $path = $this->query('path');
            if ($path && ! TenantStorageService::isTenantBucketPath($path)) {
                $validator->errors()->add('path', 'Invalid or missing path.');
            }
        });
    }
}
