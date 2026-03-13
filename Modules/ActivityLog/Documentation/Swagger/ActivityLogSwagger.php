<?php

namespace Modules\ActivityLog\Documentation\Swagger;

use App\Http\Requests\IndexQueryRequest;
use OpenApi\Attributes as OA;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Http\Requests\IndexActivityLogRequest;

#[OA\Tag(name: 'Journaux d\'activité', description: 'Consultation des journaux d\'activité et des actions auditées')]
trait ActivityLogSwagger
{
    #[OA\Get(
        path: '/api/v1/activitylog',
        summary: 'Liste des journaux d\'activité',
        description: 'Retourne les entrées du journal d\'activité avec pagination et filtres. **Actions possibles (valeur technique pour le filtre `action`)** : created (Créé), updated (Modifié), deleted (Supprimé), retrieved (Récupéré), viewed (Consulté), listed (Listé), authenticated (Connecté), logout (Déconnecté), failed_login (Tentative de connexion échouée), password_reset (Réinitialisation du mot de passe), password_changed (Mot de passe modifié), email_verified (Email vérifié), restored (Restauré), force_deleted (Suppression définitive), exported (Exporté), imported (Importé), assigned (Assigné), unassigned (Désassigné), approved (Approuvé), rejected (Rejeté), downloaded (Téléchargé).',
        tags: ['Journaux d\'activité'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Numéro de page'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Nombre d\'éléments par page'),
            new OA\Parameter(name: 'user_id', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'Filtrer par utilisateur (UUID)'),
            new OA\Parameter(name: 'entity', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Filtrer par entité (ex: user, permission, declaration)'),
            new OA\Parameter(
                name: 'action',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', enum: [
                    'created', 'updated', 'deleted', 'retrieved', 'viewed', 'listed',
                    'authenticated', 'logout', 'failed_login', 'password_reset', 'password_changed',
                    'email_verified', 'restored', 'force_deleted', 'exported', 'imported',
                    'assigned', 'unassigned', 'approved', 'rejected', 'downloaded'
                ]),
                description: 'Filtrer par action. Valeurs : created (Créé), updated (Modifié), deleted (Supprimé), authenticated (Connecté), logout (Déconnecté), failed_login (Tentative échouée), restored (Restauré), force_deleted (Suppression définitive), exported (Exporté), imported (Importé), assigned (Assigné), unassigned (Désassigné), approved (Approuvé), rejected (Rejeté), etc.'
            ),
            new OA\Parameter(name: 'start_date', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date'), description: 'Date de début (inclus)'),
            new OA\Parameter(name: 'end_date', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date'), description: 'Date de fin (inclus)'),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Relations à charger (ex: user, subject)'),
            new OA\Parameter(name: 'sort', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Tri JSON: {"field":"created_at","direction":"desc"}'),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Recherche JSON sur les champs')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function index(IndexActivityLogRequest $request);

    #[OA\Get(
        path: '/api/v1/activitylog/{id}',
        summary: 'Détail d\'un journal d\'activité',
        description: 'Retourne une entrée du journal (action, entité, utilisateur, date, propriétés). Le champ `action` utilise les valeurs techniques ; les libellés français sont : Créé, Modifié, Supprimé, Connecté, Déconnecté, etc.',
        tags: ['Journaux d\'activité'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'), description: 'UUID du journal'),
            new OA\Parameter(name: 'populate', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Relations à charger (ex: user, subject)')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 403, description: 'Interdit'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 500, description: 'Erreur serveur')
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function show(ActivityLog $activityLog, IndexQueryRequest $request);
}
