<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class MatiereEnseignantSwagger
{
    #[OA\Get(path: '/api/v1/academique/matiere-enseignants', summary: 'Liste des affectations matière-enseignant', tags: ['Academique - MatiereEnseignants'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/matiere-enseignants/{id}', summary: 'Détail d\'une affectation matière-enseignant', tags: ['Academique - MatiereEnseignants'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/matiere-enseignants', summary: 'Créer une affectation matière-enseignant', tags: ['Academique - MatiereEnseignants'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['matiere_id', 'enseignant_id', 'annee_academique_id'], properties: [
        new OA\Property(property: 'matiere_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'enseignant_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'groupe_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'type_seance', type: 'string', nullable: true),
        new OA\Property(property: 'is_principal', type: 'boolean', nullable: true)
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/matiere-enseignants/{id}', summary: 'Modifier une affectation matière-enseignant', tags: ['Academique - MatiereEnseignants'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'matiere_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'enseignant_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'groupe_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'type_seance', type: 'string', nullable: true),
        new OA\Property(property: 'is_principal', type: 'boolean')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/matiere-enseignants/{id}', summary: 'Supprimer une affectation matière-enseignant', tags: ['Academique - MatiereEnseignants'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
