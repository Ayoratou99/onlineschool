<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class EtageSwagger
{
    #[OA\Get(path: '/api/v1/academique/etages', summary: 'Liste des étages', tags: ['Academique - Etages'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/etages/{id}', summary: 'Détail d\'un étage', tags: ['Academique - Etages'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/etages', summary: 'Créer un étage', tags: ['Academique - Etages'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['batiment_id', 'numero', 'libelle'], properties: [
        new OA\Property(property: 'batiment_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'numero', type: 'integer'),
        new OA\Property(property: 'libelle', type: 'string', maxLength: 255),
        new OA\Property(property: 'is_active', type: 'boolean', nullable: true)
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/etages/{id}', summary: 'Modifier un étage', tags: ['Academique - Etages'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'batiment_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'numero', type: 'integer'),
        new OA\Property(property: 'libelle', type: 'string', maxLength: 255),
        new OA\Property(property: 'is_active', type: 'boolean')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/etages/{id}', summary: 'Supprimer un étage', tags: ['Academique - Etages'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
