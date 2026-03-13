<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class MatiereSwagger
{
    #[OA\Get(path: '/api/v1/academique/matieres', summary: 'Liste des matières', tags: ['Academique - Matieres'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/matieres/{id}', summary: 'Détail d\'une matière', tags: ['Academique - Matieres'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/matieres', summary: 'Créer une matière', tags: ['Academique - Matieres'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['ue_id', 'code', 'libelle'], properties: [
        new OA\Property(property: 'ue_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'code', type: 'string', maxLength: 50),
        new OA\Property(property: 'libelle', type: 'string', maxLength: 255),
        new OA\Property(property: 'credits', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'coefficient', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'vh_cm', type: 'integer', nullable: true),
        new OA\Property(property: 'vh_td', type: 'integer', nullable: true),
        new OA\Property(property: 'vh_tp', type: 'integer', nullable: true),
        new OA\Property(property: 'est_compensable', type: 'boolean', nullable: true),
        new OA\Property(property: 'note_eliminatoire', type: 'number', nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean', nullable: true)
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/matieres/{id}', summary: 'Modifier une matière', tags: ['Academique - Matieres'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'ue_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'code', type: 'string', maxLength: 50),
        new OA\Property(property: 'libelle', type: 'string', maxLength: 255),
        new OA\Property(property: 'credits', type: 'number', nullable: true),
        new OA\Property(property: 'coefficient', type: 'number', nullable: true),
        new OA\Property(property: 'vh_cm', type: 'integer'),
        new OA\Property(property: 'vh_td', type: 'integer'),
        new OA\Property(property: 'vh_tp', type: 'integer'),
        new OA\Property(property: 'est_compensable', type: 'boolean'),
        new OA\Property(property: 'note_eliminatoire', type: 'number', nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/matieres/{id}', summary: 'Supprimer une matière', tags: ['Academique - Matieres'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
