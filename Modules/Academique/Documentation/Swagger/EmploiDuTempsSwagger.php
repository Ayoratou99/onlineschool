<?php

namespace Modules\Academique\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class EmploiDuTempsSwagger
{
    #[OA\Get(path: '/api/v1/academique/emplois-du-temps', summary: 'Liste des emplois du temps', tags: ['Academique - EmploisDuTemps'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 500, description: 'Erreur serveur')], security: [['bearerAuth' => []]])]
    abstract public function index(IndexQueryRequest $request);

    #[OA\Get(path: '/api/v1/academique/emplois-du-temps/{id}', summary: 'Détail d\'un emploi du temps', tags: ['Academique - EmploisDuTemps'], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'))
    ], responses: [new OA\Response(response: 200, description: 'Succès'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function get(string $id);

    #[OA\Post(path: '/api/v1/academique/emplois-du-temps', summary: 'Créer un emploi du temps', tags: ['Academique - EmploisDuTemps'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['semestre_id', 'niveau_id', 'groupe_id', 'matiere_id', 'salle_id', 'enseignant_id', 'annee_academique_id'], properties: [
        new OA\Property(property: 'semestre_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'niveau_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'groupe_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'matiere_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'salle_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'enseignant_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'type_seance', type: 'string', nullable: true),
        new OA\Property(property: 'jour', type: 'string', nullable: true),
        new OA\Property(property: 'heure_debut', type: 'string', nullable: true),
        new OA\Property(property: 'heure_fin', type: 'string', nullable: true),
        new OA\Property(property: 'frequence', type: 'string', nullable: true),
        new OA\Property(property: 'date_specifique', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'date_debut_effectif', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'date_fin_effectif', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean', nullable: true)
    ])), responses: [new OA\Response(response: 201, description: 'Créé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function create(Request $request);

    #[OA\Put(path: '/api/v1/academique/emplois-du-temps/{id}', summary: 'Modifier un emploi du temps', tags: ['Academique - EmploisDuTemps'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'semestre_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'niveau_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'groupe_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'matiere_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'salle_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'enseignant_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'annee_academique_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'type_seance', type: 'string', nullable: true),
        new OA\Property(property: 'jour', type: 'string', nullable: true),
        new OA\Property(property: 'heure_debut', type: 'string', nullable: true),
        new OA\Property(property: 'heure_fin', type: 'string', nullable: true),
        new OA\Property(property: 'frequence', type: 'string', nullable: true),
        new OA\Property(property: 'date_specifique', type: 'string', nullable: true),
        new OA\Property(property: 'date_debut_effectif', type: 'string', nullable: true),
        new OA\Property(property: 'date_fin_effectif', type: 'string', nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean')
    ])), responses: [new OA\Response(response: 200, description: 'Mis à jour'), new OA\Response(response: 403, description: 'Interdit'), new OA\Response(response: 404, description: 'Non trouvé'), new OA\Response(response: 422, description: 'Erreur de validation')], security: [['bearerAuth' => []]])]
    abstract public function update(string $id, Request $request);

    #[OA\Delete(path: '/api/v1/academique/emplois-du-temps/{id}', summary: 'Supprimer un emploi du temps', tags: ['Academique - EmploisDuTemps'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé'), new OA\Response(response: 404, description: 'Non trouvé')], security: [['bearerAuth' => []]])]
    abstract public function delete(string $id);
}
