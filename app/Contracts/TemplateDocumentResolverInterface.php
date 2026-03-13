<?php

namespace App\Contracts;

interface TemplateDocumentResolverInterface
{
    /**
     * Resolve a template document id by code (e.g. FICHE_COMPLETE_PERSONNE).
     * Returns null if not found.
     */
    public function getTemplateIdByCode(string $code): ?string;
}
