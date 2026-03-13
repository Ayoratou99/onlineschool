<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class EmploiDuTempsExceptionSwagger
{
    #[OA\Get(path: '/api/v1/academique/emploi-du-temps-exceptions', summary: 'Liste des exceptions d\'emploi du temps', tags: ['Academique - EmploiDuTempsExceptions'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/emploi-du-temps-exceptions/{id}', summary: 'Détail d\'une exception d\'emploi du temps', tags: ['Academique - EmploiDuTempsExceptions'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/emploi-du-temps-exceptions', summary: 'Créer une exception d\'emploi du temps', tags: ['Academique - EmploiDuTempsExceptions'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['emploi_du_temps_id', 'date_concernee', 'created_by'], properties: [
        new OA\Property(property: 'emploi_du_temps_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'date_concernee', type: 'string', format: 'date'),
        new OA\Property(property: 'type', type: 'string', nullable: true),
        new OA\Property(property: 'nouvelle_salle_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'nouvel_enseignant_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'nouvelle_heure_debut', type: 'string', nullable: true),
        new OA\Property(property: 'nouvelle_heure_fin', type: 'string', nullable: true),
        new OA\Property(property: 'motif', type: 'string', nullable: true),
        new OA\Property(property: 'created_by', type: 'string', format: 'uuid')
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/emploi-du-temps-exceptions/{id}', summary: 'Modifier une exception d\'emploi du temps', tags: ['Academique - EmploiDuTempsExceptions'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'emploi_du_temps_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'date_concernee', type: 'string', format: 'date'),
        new OA\Property(property: 'type', type: 'string', nullable: true),
        new OA\Property(property: 'nouvelle_salle_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'nouvel_enseignant_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'nouvelle_heure_debut', type: 'string', nullable: true),
        new OA\Property(property: 'nouvelle_heure_fin', type: 'string', nullable: true),
        new OA\Property(property: 'motif', type: 'string', nullable: true),
        new OA\Property(property: 'created_by', type: 'string', format: 'uuid')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/emploi-du-temps-exceptions/{id}', summary: 'Supprimer une exception d\'emploi du temps', tags: ['Academique - EmploiDuTempsExceptions'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
