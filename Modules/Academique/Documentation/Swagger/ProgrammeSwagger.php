<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class ProgrammeSwagger
{
    #[OA\Get(path: '/api/v1/academique/programmes', summary: 'Liste des programmes', tags: ['Academique - Programmes'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/programmes/{id}', summary: 'Détail d\'un programme', tags: ['Academique - Programmes'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/programmes', summary: 'Créer un programme', tags: ['Academique - Programmes'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['niveau_id', 'annee_academique_id'], properties: [
        new OA\Property(property: 'niveau_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'version', type: 'integer', nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean', nullable: true),
        new OA\Property(property: 'valide_par', type: 'string', nullable: true),
        new OA\Property(property: 'valide_le', type: 'string', format: 'date-time', nullable: true)
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/programmes/{id}', summary: 'Modifier un programme', tags: ['Academique - Programmes'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'niveau_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'version', type: 'integer'),
        new OA\Property(property: 'is_active', type: 'boolean'),
        new OA\Property(property: 'valide_par', type: 'string', nullable: true),
        new OA\Property(property: 'valide_le', type: 'string', format: 'date-time', nullable: true)
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/programmes/{id}', summary: 'Supprimer un programme', tags: ['Academique - Programmes'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
