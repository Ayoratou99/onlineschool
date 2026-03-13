<?php

namespace Modules\Document\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Document\Http\Requests\StoreTemplateDocumentRequest;
use Modules\Document\Http\Requests\UpdateTemplateDocumentRequest;
use Modules\Document\Models\TemplateDocument;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Modèles de documents', description: 'CRUD des modèles de documents (DOCX) stockés dans storage/app/private/templates')]
trait TemplateDocumentSwagger
{
    #[OA\Get(
        path: '/api/v1/document/template-documents',
        summary: 'Liste des modèles de documents',
        tags: ['Modèles de documents'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function index(IndexQueryRequest $request): JsonResponse;

    #[OA\Get(
        path: '/api/v1/document/template-documents/{id}',
        summary: 'Détail d\'un modèle de document',
        tags: ['Modèles de documents'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function show(TemplateDocument $templateDocument, IndexQueryRequest $request): JsonResponse;

    #[OA\Post(
        path: '/api/v1/document/template-documents',
        summary: 'Créer un modèle de document',
        description: 'Envoi d\'un fichier DOCX. Nom unique. Fichier stocké dans storage/app/private/templates.',
        tags: ['Modèles de documents'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['name', 'file'],
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'FICHE_COMPLETE_PERSONNE_TEMPLATE'),
                        new OA\Property(property: 'description', type: 'string', nullable: true),
                        new OA\Property(property: 'file', type: 'string', format: 'binary', description: 'Fichier DOCX obligatoire')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 422, description: 'Validation (nom déjà existant, fichier non DOCX)'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function store(StoreTemplateDocumentRequest $request): JsonResponse;

    #[OA\Put(
        path: '/api/v1/document/template-documents/{id}',
        summary: 'Modifier un modèle de document',
        description: 'Modification du nom (unique) et/ou de la description. Fichier optionnel : si envoyé (DOCX), remplace le fichier existant.',
        tags: ['Modèles de documents'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'description', type: 'string', nullable: true),
                        new OA\Property(property: 'file', type: 'string', format: 'binary', description: 'Fichier DOCX optionnel')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Validation'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(UpdateTemplateDocumentRequest $request, TemplateDocument $templateDocument): JsonResponse;

    #[OA\Delete(
        path: '/api/v1/document/template-documents/{id}',
        summary: 'Supprimer un modèle de document',
        tags: ['Modèles de documents'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function destroy(TemplateDocument $templateDocument): JsonResponse;
}
