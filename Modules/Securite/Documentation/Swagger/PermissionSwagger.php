<?php

namespace Modules\Securite\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class PermissionSwagger 
{
    #[OA\Get(
        path: '/api/v1/securite/permission',
        summary: 'Liste des Permissions',
        tags: ['Permissions'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Numéro de page pour la pagination'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Nombre d\'éléments par page'),
            new OA\Parameter(
                name: 'populate', 
                in: 'query', 
                required: false, 
                schema: new OA\Schema(type: 'string'), 
                description: 'Relations à charger (eager loading). Exemples: "roles" pour charger les rôles associés à chaque permission.'
            ),
            new OA\Parameter(
                name: 'sort',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Tri des résultats (JSON encodé). Format: {"field": "nom", "direction": "asc"} ou {"field": "nom", "direction": "desc"}. La direction par défaut est "asc" si non spécifiée. Exemple: ?sort={"field":"nom","direction":"asc"}'
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Recherche dans les champs (JSON encodé). Effectue une recherche LIKE (contient) sur les champs spécifiés. Format: {"nom": "bureau", "code": "BRU"}. Chaque champ est recherché avec LIKE \'%valeur%\'. Exemple: ?search={"nom":"bureau","code":"001"}'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(
        path: '/api/v1/securite/permission/{id}',
        summary: 'Détails d\'une Permission',
        tags: ['Permissions'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique de la permission (UUID)'),
            new OA\Parameter(
                name: 'populate', 
                in: 'query', 
                required: false, 
                schema: new OA\Schema(type: 'string'), 
                description: 'Relations à charger (eager loading). Exemples: "roles" pour charger les rôles associés à la permission.'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function get(string $id);

    #[OA\Post(
        path: '/api/v1/securite/permission',
        summary: 'Créer une Permission',
        tags: ['Permissions'],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'description', type: 'string'),
            new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED'])
        ])),
        responses: [
            new OA\Response(response: 201, description: 'Créé'),
            new OA\Response(response: 422, description: 'Erreur validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function create(Request $request);

    #[OA\Put(
        path: '/api/v1/securite/permission/{id}',
        summary: 'Modifier une Permission',
        tags: ['Permissions'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'description', type: 'string'),
            new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED'])
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Mis à jour'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(
        path: '/api/v1/securite/permission/{id}',
        summary: 'Supprimer une Permission',
        tags: ['Permissions'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 204, description: 'Supprimé'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function delete(string $id);
}

