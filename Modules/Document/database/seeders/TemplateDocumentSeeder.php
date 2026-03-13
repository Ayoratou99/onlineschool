<?php

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\TemplateDocument;
use Modules\Document\Services\TemplateDocumentService;

class TemplateDocumentSeeder extends Seeder
{
    private const FICHE_COMPLETE_NAME = 'Fiche complète de la personne';
    private const FICHE_COMPLETE_PATH = 'private/templates/FICHE_COMPLETE_PERSONNE_TEMPLATE.docx';
    private const FICHER_MESSAGE_NAME = 'Ficher message';
    private const FICHER_MESSAGE_PATH = 'private/templates/FICHER_MESSAGE_TEMPLATE.docx';
    private const ATTESTATION_PERTE_NAME = 'Attestation perte document';
    private const ATTESTATION_PERTE_PATH = 'private/templates/ATTESTATION_PERTE_DOCUMENT_TEMPLATE.docx';
    private const ATTESTATION_DISPARITION_NAME = 'Attestation disparition';
    private const ATTESTATION_DISPARITION_PATH = 'private/templates/ATTESTATION_DISPARITION_TEMPLATE.docx';
    private const FICHE_IDENTIFICATION_VEHICULE_NAME = 'Fiche identification véhicule';
    private const FICHE_IDENTIFICATION_VEHICULE_PATH = 'private/templates/FICHE_IDENTIFICATION_VEHICULE_TEMPLATE.docx';

    public function run(): void
    {
        Storage::disk('local')->makeDirectory(TemplateDocumentService::TEMPLATES_DISK_PATH);

        TemplateDocument::updateOrCreate(
            ['name' => self::FICHE_COMPLETE_NAME],
            [
                'description' => self::FICHE_COMPLETE_NAME,
                'path' => self::FICHE_COMPLETE_PATH,
            ]
        );

        TemplateDocument::updateOrCreate(
            ['name' => self::FICHER_MESSAGE_NAME],
            [
                'description' => self::FICHER_MESSAGE_NAME,
                'path' => self::FICHER_MESSAGE_PATH,
            ]
        );

        TemplateDocument::updateOrCreate(
            ['name' => self::ATTESTATION_PERTE_NAME],
            [
                'description' => self::ATTESTATION_PERTE_NAME,
                'path' => self::ATTESTATION_PERTE_PATH,
            ]
        );

        TemplateDocument::updateOrCreate(
            ['name' => self::ATTESTATION_DISPARITION_NAME],
            [
                'description' => self::ATTESTATION_DISPARITION_NAME,
                'path' => self::ATTESTATION_DISPARITION_PATH,
            ]
        );

        TemplateDocument::updateOrCreate(
            ['name' => self::FICHE_IDENTIFICATION_VEHICULE_NAME],
            [
                'description' => self::FICHE_IDENTIFICATION_VEHICULE_NAME,
                'path' => self::FICHE_IDENTIFICATION_VEHICULE_PATH,
            ]
        );
    }
}
