<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class SalleIndisponibiliteSwagger
{
    #[OA\Get(path: '/api/v1/academique/salle-indisponibilites', summary: 'Liste des indisponibilités de salle', tags: ['Academique - SalleIndisponibilites'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/salle-indisponibilites/{id}', summary: 'Détail d\'une indisponibilité de salle', tags: ['Academique - SalleIndisponibilites'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/salle-indisponibilites', summary: 'Créer une indisponibilité de salle', tags: ['Academique - SalleIndisponibilites'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['salle_id', 'date_debut', 'date_fin', 'created_by'], properties: [
        new OA\Property(property: 'salle_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'date_debut', type: 'string', format: 'date-time'),
        new OA\Property(property: 'date_fin', type: 'string', format: 'date-time'),
        new OA\Property(property: 'motif', type: 'string', nullable: true),
        new OA\Property(property: 'created_by', type: 'string', format: 'uuid')
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/salle-indisponibilites/{id}', summary: 'Modifier une indisponibilité de salle', tags: ['Academique - SalleIndisponibilites'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'salle_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'date_debut', type: 'string', format: 'date-time'),
        new OA\Property(property: 'date_fin', type: 'string', format: 'date-time'),
        new OA\Property(property: 'motif', type: 'string', nullable: true),
        new OA\Property(property: 'created_by', type: 'string', format: 'uuid')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/salle-indisponibilites/{id}', summary: 'Supprimer une indisponibilité de salle', tags: ['Academique - SalleIndisponibilites'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
