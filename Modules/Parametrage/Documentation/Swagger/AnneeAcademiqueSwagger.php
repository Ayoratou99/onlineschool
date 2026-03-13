<?php

namespace Modules\Parametrage\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class AnneeAcademiqueSwagger
{
    #[OA\Get(
        path: '/api/v1/parametrage/annee-academique',
        summary: 'Liste des années académiques',
        tags: ['Parametrage - Années académiques'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Numéro de page pour la pagination'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Nombre d\'éléments par page'),
            new OA\Parameter(
                name: 'populate',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Relations à charger (eager loading). Exemples: "baremesMention", "reglesValidation".'
            ),
            new OA\Parameter(
                name: 'sort',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Tri des résultats (JSON encodé). Format: {"field": "code", "direction": "asc"} ou {"field": "date_debut", "direction": "desc"}.'
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Recherche dans les champs (JSON encodé). Format: {"code": "2024", "libelle": "Licence"}.'
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
        path: '/api/v1/parametrage/annee-academique/{id}',
        summary: 'Détail d\'une année académique',
        tags: ['Parametrage - Années académiques'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique de l\'année académique (UUID)'),
            new OA\Parameter(
                name: 'populate',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Relations à charger (eager loading). Exemples: "baremesMention", "reglesValidation".'
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
        path: '/api/v1/parametrage/annee-academique',
        summary: 'Créer une année académique',
        tags: ['Parametrage - Années académiques'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'libelle', 'date_debut', 'date_fin'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', maxLength: 20, description: 'Code unique de l\'année académique'),
                    new OA\Property(property: 'libelle', type: 'string', maxLength: 100, description: 'Libellé de l\'année académique'),
                    new OA\Property(property: 'date_debut', type: 'string', format: 'date', description: 'Date de début (Y-m-d)'),
                    new OA\Property(property: 'date_fin', type: 'string', format: 'date', description: 'Date de fin (Y-m-d), doit être >= date_debut'),
                    new OA\Property(property: 'is_active', type: 'boolean', nullable: true, description: 'Année active (défaut: true)'),
                    new OA\Property(property: 'created_by', type: 'string', format: 'uuid', nullable: true, description: 'UUID de l\'utilisateur créateur (optionnel si authentifié)')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Année académique créée'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function create(Request $request);

    #[OA\Put(
        path: '/api/v1/parametrage/annee-academique/{id}',
        summary: 'Modifier une année académique',
        tags: ['Parametrage - Années académiques'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique de l\'année académique (UUID)')],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'code', type: 'string', maxLength: 20, description: 'Code unique'),
                    new OA\Property(property: 'libelle', type: 'string', maxLength: 100),
                    new OA\Property(property: 'date_debut', type: 'string', format: 'date'),
                    new OA\Property(property: 'date_fin', type: 'string', format: 'date'),
                    new OA\Property(property: 'is_active', type: 'boolean')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Année académique mise à jour'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(
        path: '/api/v1/parametrage/annee-academique/{id}',
        summary: 'Supprimer une année académique',
        tags: ['Parametrage - Années académiques'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Identifiant unique de l\'année académique (UUID)')],
        responses: [
            new OA\Response(response: 200, description: 'Supprimé'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function delete(string $id);
}
