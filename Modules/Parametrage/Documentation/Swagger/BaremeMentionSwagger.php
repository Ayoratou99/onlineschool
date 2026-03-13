<?php

namespace Modules\Parametrage\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class BaremeMentionSwagger
{
    #[OA\Get(
        path: '/api/v1/parametrage/bareme-mention',
        summary: 'Liste des barèmes de mention',
        tags: ['Parametrage - Barèmes mention'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Ex: anneeAcademique'),
            new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(
        path: '/api/v1/parametrage/bareme-mention/{id}',
        summary: 'Détail d\'un barème de mention',
        tags: ['Parametrage - Barèmes mention'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function get(string $id);

    #[OA\Post(
        path: '/api/v1/parametrage/bareme-mention',
        summary: 'Créer un barème de mention',
        tags: ['Parametrage - Barèmes mention'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['annee_academique_id', 'mention', 'bareme_min', 'bareme_max'],
                properties: [
                    new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
                    new OA\Property(property: 'mention', type: 'string', maxLength: 50),
                    new OA\Property(property: 'bareme_min', type: 'number', format: 'float'),
                    new OA\Property(property: 'bareme_max', type: 'number', format: 'float'),
                    new OA\Property(property: 'ordre', type: 'integer', nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function create(Request $request);

    #[OA\Put(
        path: '/api/v1/parametrage/bareme-mention/{id}',
        summary: 'Modifier un barème de mention',
        tags: ['Parametrage - Barèmes mention'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'mention', type: 'string', maxLength: 50),
                    new OA\Property(property: 'bareme_min', type: 'number'),
                    new OA\Property(property: 'bareme_max', type: 'number'),
                    new OA\Property(property: 'ordre', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Mis à jour'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(
        path: '/api/v1/parametrage/bareme-mention/{id}',
        summary: 'Supprimer un barème de mention',
        tags: ['Parametrage - Barèmes mention'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 200, description: 'Supprimé'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function delete(string $id);
}
