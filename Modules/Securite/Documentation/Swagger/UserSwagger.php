<?php

namespace Modules\Securite\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class UserSwagger 
{
    #[OA\Get(
        path: '/api/v1/securite/user',
        summary: 'Liste des Utilisateurs',
        tags: ['Utilisateurs'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Numéro de page pour la pagination'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Nombre d\'éléments par page'),
            new OA\Parameter(
                name: 'populate', 
                in: 'query', 
                required: false, 
                schema: new OA\Schema(type: 'string'), 
                description: 'Relations à charger (eager loading). Exemples: "roles" pour charger les rôles de chaque utilisateur, ou "roles.permissions" pour charger les rôles et leurs permissions.'
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
        path: '/api/v1/securite/user/{id}',
        summary: 'Détails d\'un Utilisateur',
        tags: ['Utilisateurs'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique de l\'utilisateur (UUID)'),
            new OA\Parameter(
                name: 'populate', 
                in: 'query', 
                required: false, 
                schema: new OA\Schema(type: 'string'), 
                description: 'Relations à charger (eager loading). Exemples: "roles" pour charger les rôles, ou "roles.permissions" pour charger les rôles et leurs permissions.'
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
        path: '/api/v1/securite/user',
        summary: 'Créer un Utilisateur',
        description: 'Crée un utilisateur sans mot de passe. Un mot de passe temporaire aléatoire est défini ; l\'utilisateur reçoit un email de vérification et définit son mot de passe via POST /api/v1/auth/confirm-email.',
        tags: ['Utilisateurs'],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(
            required: ['nom', 'email'],
            properties: [
                new OA\Property(property: 'nom', type: 'string'),
                new OA\Property(property: 'prenom', type: 'string', nullable: true),
                new OA\Property(property: 'email', type: 'string', format: 'email'),
                new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED'], nullable: true),
                new OA\Property(property: 'two_factor_enabled', type: 'boolean', nullable: true, description: 'Exiger la 2FA pour cet utilisateur (code email et/ou Google Authenticator)'),
                new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string', format: 'uuid'), nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Créé'),
            new OA\Response(response: 422, description: 'Erreur validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function create(Request $request);

    #[OA\Put(
        path: '/api/v1/securite/user/{id}',
        summary: 'Modifier un Utilisateur',
        description: 'Tous les champs sont optionnels (sometimes) ; seuls les champs envoyés sont mis à jour.',
        tags: ['Utilisateurs'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'nom', type: 'string', nullable: true),
            new OA\Property(property: 'prenom', type: 'string', nullable: true),
            new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true),
            new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED'], nullable: true),
            new OA\Property(property: 'two_factor_enabled', type: 'boolean', nullable: true, description: 'Exiger la 2FA pour cet utilisateur'),
            new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string', format: 'uuid'), nullable: true, description: 'Liste des IDs de rôles ; si fourni, remplace les rôles de l\'utilisateur'),
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Mis à jour (utilisateur avec relation roles)'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(
        path: '/api/v1/securite/user/{id}',
        summary: 'Supprimer un Utilisateur',
        tags: ['Utilisateurs'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 204, description: 'Supprimé'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function delete(string $id);

    #[OA\Post(
        path: '/api/v1/securite/user/{id}/assign-role',
        summary: 'Assigner un Rôle à un Utilisateur',
        tags: ['Utilisateurs'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'role_id', type: 'string', format: 'uuid', description: 'Identifiant du rôle à assigner')
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Rôle assigné avec succès'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function assignRole(string $id, Request $request);

    #[OA\Post(
        path: '/api/v1/securite/user/{id}/unassign-role',
        summary: 'Retirer un Rôle d\'un Utilisateur',
        tags: ['Utilisateurs'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: 'role_id', type: 'string', format: 'uuid', description: 'Identifiant du rôle à retirer')
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Rôle retiré avec succès'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function unassignRole(string $id, Request $request);
}

