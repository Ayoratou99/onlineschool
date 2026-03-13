<?php

namespace Modules\Securite\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class RoleSwagger 
{
    #[OA\Get(
        path: '/api/v1/securite/role',
        summary: 'Liste des Rôles',
        tags: ['Rôles'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Numéro de page pour la pagination'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Nombre d\'éléments par page'),
            new OA\Parameter(
                name: 'populate', 
                in: 'query', 
                required: false, 
                schema: new OA\Schema(type: 'string'), 
                description: 'Relations à charger (eager loading). Exemples: "permissions" pour charger les permissions de chaque rôle.'
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
        path: '/api/v1/securite/role/{id}',
        summary: 'Détails d\'un Rôle',
        tags: ['Rôles'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique du rôle (UUID)'),
            new OA\Parameter(
                name: 'populate', 
                in: 'query', 
                required: false, 
                schema: new OA\Schema(type: 'string'), 
                description: 'Relations à charger (eager loading). Exemples: "permissions" pour charger les permissions du rôle.'
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
        path: '/api/v1/securite/role',
        summary: 'Créer un Rôle',
        tags: ['Rôles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'state'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, description: 'Nom unique du rôle'),
                    new OA\Property(property: 'description', type: 'string', maxLength: 500, nullable: true, description: 'Description du rôle'),
                    new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED'], description: 'État du rôle'),
                    new OA\Property(
                        property: 'permissions',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'uuid'),
                        nullable: true,
                        description: 'Liste des identifiants des permissions à assigner au rôle (UUIDs existants dans la table permissions)'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Rôle créé avec ses permissions'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function create(Request $request);

    #[OA\Put(
        path: '/api/v1/securite/role/{id}',
        summary: 'Modifier un Rôle',
        description: 'Met à jour le rôle. Si "permissions" est fourni, les permissions du rôle sont synchronisées (remplacées). La réponse inclut toujours le rôle avec sa relation permissions.',
        tags: ['Rôles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique du rôle (UUID)')],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'state'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, description: 'Nom unique du rôle (unique parmi les rôles, à l\'exception du rôle courant)'),
                    new OA\Property(property: 'description', type: 'string', maxLength: 500, nullable: true, description: 'Description du rôle'),
                    new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED'], description: 'État du rôle'),
                    new OA\Property(
                        property: 'permissions',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'uuid'),
                        nullable: true,
                        description: 'Liste des identifiants des permissions à assigner au rôle (UUIDs existants dans la table permissions). Si fourni, remplace toutes les permissions du rôle. Peut être envoyé en tableau ou en chaîne séparée par des virgules.'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Rôle mis à jour avec ses permissions (relation permissions incluse dans la réponse)'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(
        path: '/api/v1/securite/role/{id}',
        summary: 'Supprimer un Rôle',
        tags: ['Rôles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 204, description: 'Supprimé'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function delete(string $id);

    #[OA\Post(
        path: '/api/v1/securite/role/{id}/assign-permission',
        summary: 'Assigner une Permission à un Rôle',
        tags: ['Rôles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'permission_id', type: 'string', format: 'uuid', description: 'Identifiant de la permission à assigner')
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Permission assignée avec succès'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function assignPermission(string $id, Request $request);

    #[OA\Post(
        path: '/api/v1/securite/role/{id}/unassign-permission',
        summary: 'Retirer une Permission d\'un Rôle',
        tags: ['Rôles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'permission_id', type: 'string', format: 'uuid', description: 'Identifiant de la permission à retirer')
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Permission retirée avec succès'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function unassignPermission(string $id, Request $request);
}

