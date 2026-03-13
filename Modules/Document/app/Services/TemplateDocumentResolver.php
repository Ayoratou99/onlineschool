<?php

namespace Modules\Document\Services;

use App\Contracts\TemplateDocumentResolverInterface;
use Modules\Document\Models\TemplateDocument;

class TemplateDocumentResolver implements TemplateDocumentResolverInterface
{
    public function getTemplateIdByCode(string $code): ?string
    {
        $name = config('document.template_codes.' . $code);
        if (!$name) {
            return null;
        }
        $template = TemplateDocument::where('name', $name)->first();

        return $template?->id;
    }
}
