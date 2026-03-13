<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class SalleSwagger
{
    #[OA\Get(path: '/api/v1/academique/salles', summary: 'Liste des salles', tags: ['Academique - Salles'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/salles/{id}', summary: 'Détail d\'une salle', tags: ['Academique - Salles'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/salles', summary: 'Créer une salle', tags: ['Academique - Salles'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['batiment_id', 'etage_id', 'code', 'libelle'], properties: [
        new OA\Property(property: 'batiment_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'etage_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'code', type: 'string', maxLength: 50),
        new OA\Property(property: 'libelle', type: 'string', maxLength: 255),
        new OA\Property(property: 'type', type: 'string', nullable: true),
        new OA\Property(property: 'capacite', type: 'integer', nullable: true),
        new OA\Property(property: 'has_projecteur', type: 'boolean', nullable: true),
        new OA\Property(property: 'has_climatisation', type: 'boolean', nullable: true),
        new OA\Property(property: 'has_tableau_blanc', type: 'boolean', nullable: true),
        new OA\Property(property: 'has_internet', type: 'boolean', nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean', nullable: true)
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/salles/{id}', summary: 'Modifier une salle', tags: ['Academique - Salles'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'batiment_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'etage_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'code', type: 'string', maxLength: 50),
        new OA\Property(property: 'libelle', type: 'string', maxLength: 255),
        new OA\Property(property: 'type', type: 'string', nullable: true),
        new OA\Property(property: 'capacite', type: 'integer'),
        new OA\Property(property: 'has_projecteur', type: 'boolean'),
        new OA\Property(property: 'has_climatisation', type: 'boolean'),
        new OA\Property(property: 'has_tableau_blanc', type: 'boolean'),
        new OA\Property(property: 'has_internet', type: 'boolean'),
        new OA\Property(property: 'is_active', type: 'boolean')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/salles/{id}', summary: 'Supprimer une salle', tags: ['Academique - Salles'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
