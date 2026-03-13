<?php

namespace Modules\Document\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Modules\Document\Models\GeneratedDocument;

#[OA\Tag(name: 'Documents générés', description: 'Consultation des documents générés (index, détail)')]
trait GeneratedDocumentSwagger
{
    #[OA\Get(
        path: '/api/v1/document/generated-documents',
        summary: 'Liste des documents générés',
        description: 'Retourne les documents générés avec pagination.',
        tags: ['Documents générés'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Relations (ex: template_document)'),
            new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'JSON: {"field":"created_at","direction":"desc"}'),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Recherche JSON sur les champs')
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
        path: '/api/v1/document/generated-documents/{id}',
        summary: 'Détail d\'un document généré',
        description: 'Retourne un document généré par son identifiant.',
        tags: ['Documents générés'],
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
    abstract public function show(GeneratedDocument $generatedDocument, IndexQueryRequest $request): JsonResponse;
}
