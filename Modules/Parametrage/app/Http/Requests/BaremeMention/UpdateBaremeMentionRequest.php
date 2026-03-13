<?php

namespace Modules\Parametrage\Http\Requests\BaremeMention;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Parametrage\Models\BaremeMention;

class UpdateBaremeMentionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('bareme_mention'));
    }

    public function rules(): array
    {
        $id = $this->route('bareme_mention')?->id;
        return [
            'mention' => ['sometimes', 'string', 'max:50'],
            'bareme_min' => ['sometimes', 'numeric', 'min:0', 'max:999.99'],
            'bareme_max' => ['sometimes', 'numeric', 'min:0', 'max:999.99', 'gte:bareme_min'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
