<?php

namespace Modules\Workflow\Documentation\Swagger;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

abstract class InstanceWorkflowSwagger
{
    #[OA\Get(
        path: '/api/v1/workflow/instance-workflows',
        summary: 'Rechercher des instances de workflow',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'statut', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Filtrer par statut'),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Numéro de page'),
            new OA\Parameter(name: 'size', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Taille de page'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Liste des instances'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 403, description: 'Non autorisé'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function searchInstances(Request $request);

    #[OA\Get(
        path: '/api/v1/workflow/instance-workflows/{instanceId}',
        summary: 'Détails d\'une instance de workflow',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Détails de l\'instance'),
            new OA\Response(response: 404, description: 'Instance non trouvée'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function getInstance(string $instanceId);

    #[OA\Get(
        path: '/api/v1/workflow/instance-workflows/{instanceId}/svg',
        summary: 'Diagramme SVG de l\'instance de workflow',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Image SVG (Content-Type: image/svg+xml)'),
            new OA\Response(response: 404, description: 'Instance non trouvée'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function getInstanceSvg(string $instanceId);

    #[OA\Get(
        path: '/api/v1/workflow/instance-workflows/{instanceId}/current-step-actions',
        summary: 'Actions disponibles pour l\'étape courante',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
            new OA\Parameter(name: 'userId', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'ID utilisateur (optionnel si authentifié)'),
            new OA\Parameter(name: 'roleFilter', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'functionFilter', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Liste des actions possibles'),
            new OA\Response(response: 422, description: 'Contexte utilisateur manquant'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function getCurrentStepActions(Request $request, string $instanceId);

    #[OA\Post(
        path: '/api/v1/workflow/instance-workflows/{instanceId}/transition',
        summary: 'Exécuter une transition',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['action', 'userId'],
                properties: [
                    new OA\Property(property: 'action', type: 'string', description: 'Identifiant de l\'action à exécuter'),
                    new OA\Property(property: 'userId', type: 'string', description: 'ID de l\'utilisateur qui exécute'),
                    new OA\Property(property: 'commentaire', type: 'string', nullable: true, description: 'Commentaire optionnel'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Transition exécutée'),
            new OA\Response(response: 422, description: 'Données invalides'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function executeTransition(Request $request, string $instanceId);

    #[OA\Get(
        path: '/api/v1/workflow/instance-workflows/{instanceId}/history',
        summary: 'Historique de l\'instance de workflow',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Historique des étapes/transitions'),
            new OA\Response(response: 404, description: 'Instance non trouvée'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function getHistory(string $instanceId);

    #[OA\Post(
        path: '/api/v1/workflow/instance-workflows/{instanceId}/suspend',
        summary: 'Suspendre une instance de workflow',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Workflow suspendu'),
            new OA\Response(response: 404, description: 'Instance non trouvée'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function suspendWorkflow(string $instanceId);

    #[OA\Post(
        path: '/api/v1/workflow/instance-workflows/{instanceId}/resume',
        summary: 'Reprendre une instance de workflow suspendue',
        tags: ['Workflow - Instances'],
        parameters: [
            new OA\Parameter(name: 'instanceId', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Identifiant de l\'instance'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Workflow repris'),
            new OA\Response(response: 404, description: 'Instance non trouvée'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function resumeWorkflow(string $instanceId);
}
