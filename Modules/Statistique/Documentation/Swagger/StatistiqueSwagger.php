<?php

namespace Modules\Statistique\Documentation\Swagger;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Statistiques",
    description: "Module de statistiques dynamiques."
)]
abstract class StatistiqueSwagger
{
    // ================================================================
    // POST /api/v1/statistique/query
    // ================================================================
    #[OA\Post(
        path: '/api/v1/statistique/query',
        summary: 'Exécuter une requête statistique dynamique',
        description: <<<'DESC'
Endpoint principal du module Statistique. Permet d'exécuter une requête d'agrégation sur n'importe quelle entité.

### Fonctionnalités
- **Agrégation** : count, count_distinct, sum, avg, min, max
- **Filtres** : sur les champs directs (`state`, `type`) ou sur les relations (`province.nom`, `bureau.code`)
- **Période** : filtrer sur une plage de dates avec `with_period` + `period`
- **Regroupement temporel** : grouper par heure/jour/semaine/mois/année avec `period_group_by`
- **Regroupement par champ** : grouper par champ direct (`state`) ou par relation (`departement.nom`)
- **Cache** : les résultats sont mis en cache automatiquement. Utilisez `no_cache: true` pour forcer un recalcul, ou `cache_ttl` pour personnaliser la durée.

### Entités disponibles
`users`, `roles`, `permissions`, `provinces`, `villes`, `quartiers`, `declarations`,
`declaration_disparitions`, `declaration_perte_documents`, `declaration_vol_vehicules`,
`declaration_fichiers`, `personnes`, `personne_fichiers`, `infractions`, `gardevues`,
`bureaux`, `unites`, `type_documents`, `type_infractions`, `type_declarations`,
`type_vehicules`, `modele_vehicules`, `marque_vehicules`, `nationalites`

### Opérations supportées
| Opération       | Description                                  | target_column |
|-----------------|----------------------------------------------|---------------|
| `count`         | Nombre total d'enregistrements               | `*` ou champ  |
| `count_distinct`| Nombre de valeurs uniques d'un champ         | champ requis  |
| `sum`           | Somme des valeurs d'un champ numérique       | champ requis  |
| `avg`           | Moyenne des valeurs d'un champ numérique     | champ requis  |
| `min`           | Valeur minimale d'un champ                   | champ requis  |
| `max`           | Valeur maximale d'un champ                   | champ requis  |

### Intervalles de période (period_group_by)
`hour`, `day`, `week`, `month`, `year`

### Exemples de requêtes

**1. Compter les utilisateurs :**
```json
{ "entity": "users", "target_column": "*", "operation": "count" }
```

**2. Moyenne par relation et période :**
```json
{
  "entity": "declarations",
  "target_column": "*",
  "operation": "count",
  "with_period": true,
  "period": { "start": "2025-01-01", "end": "2025-12-31" },
  "period_group_by": "month",
  "group_by": ["type_declaration.nom"]
}
```

**3. Avec filtres sur relation et regroupement :**
```json
{
  "entity": "infractions",
  "target_column": "*",
  "operation": "count",
  "filters": { "bureau.code": "BUR001" },
  "group_by": ["state"]
}
```
DESC,
        tags: ['Statistiques'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['entity', 'target_column', 'operation'],
                properties: [
                    new OA\Property(
                        property: 'entity',
                        type: 'string',
                        description: "Nom de l'entité à interroger. Doit correspondre à une clé du registre d'entités (config statistique.entities).",
                        example: 'declarations'
                    ),
                    new OA\Property(
                        property: 'target_column',
                        type: 'string',
                        description: "Colonne cible de l'agrégation. Utilisez '*' pour COUNT(*). Pour count_distinct, sum, avg, min, max, indiquez un nom de colonne existant sur la table de l'entité.",
                        example: '*'
                    ),
                    new OA\Property(
                        property: 'operation',
                        type: 'string',
                        enum: ['count', 'count_distinct', 'sum', 'avg', 'min', 'max'],
                        description: "Opération d'agrégation à appliquer.",
                        example: 'count'
                    ),
                    new OA\Property(
                        property: 'with_period',
                        type: 'boolean',
                        nullable: true,
                        description: "Active le filtrage par période. Si true, le champ 'period' devient requis.",
                        example: true
                    ),
                    new OA\Property(
                        property: 'period',
                        type: 'object',
                        nullable: true,
                        description: "Plage de dates pour filtrer les résultats. Requis si with_period=true.",
                        properties: [
                            new OA\Property(property: 'start', type: 'string', format: 'date', description: 'Date de début (YYYY-MM-DD)', example: '2025-01-01'),
                            new OA\Property(property: 'end', type: 'string', format: 'date', description: 'Date de fin (YYYY-MM-DD, >= start)', example: '2025-12-31'),
                        ]
                    ),
                    new OA\Property(
                        property: 'period_column',
                        type: 'string',
                        nullable: true,
                        description: "Colonne de date utilisée pour le filtrage de période et le regroupement temporel. Par défaut 'created_at'. Doit exister sur la table de l'entité.",
                        example: 'created_at'
                    ),
                    new OA\Property(
                        property: 'period_group_by',
                        type: 'string',
                        enum: ['hour', 'day', 'week', 'month', 'year'],
                        nullable: true,
                        description: "Regroupe les résultats par intervalle de temps. Peut être utilisé indépendamment de with_period (ex: compter par mois sur toutes les données).",
                        example: 'month'
                    ),
                    new OA\Property(
                        property: 'filters',
                        type: 'object',
                        nullable: true,
                        description: "Filtres à appliquer. Clé = nom du champ (ou 'relation.champ'), Valeur = valeur exacte ou tableau de valeurs (IN). Exemples: {\"state\": \"ACTIVE\"}, {\"province.nom\": \"Estuaire\"}, {\"state\": [\"ACTIVE\", \"PENDING\"]}",
                        example: '{"state": "ACTIVE"}'
                    ),
                    new OA\Property(
                        property: 'group_by',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        nullable: true,
                        description: "Liste des champs de regroupement. Champ direct ('state') ou relation ('province.nom', 'bureau.code'). Relations supportées : BelongsTo, HasOne, BelongsToMany. HasMany n'est pas supporté pour le groupBy.",
                        example: '["state"]'
                    ),
                    new OA\Property(
                        property: 'no_cache',
                        type: 'boolean',
                        nullable: true,
                        description: "Si true, ignore le cache et force un recalcul. Le résultat ne sera pas non plus mis en cache.",
                        example: false
                    ),
                    new OA\Property(
                        property: 'cache_ttl',
                        type: 'integer',
                        nullable: true,
                        description: "Durée de vie du cache en secondes (1 à 86400). Remplace la valeur par défaut de la config (5 minutes).",
                        example: 600
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistiques récupérées avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'app_code', type: 'string', example: 'FUIP_100'),
                        new OA\Property(property: 'message', type: 'string', example: 'Statistiques récupérées avec succès.'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                description: "Chaque objet contient 'result' (valeur agrégée) et les éventuels champs de regroupement (period_month, province_nom, state, etc.)"
                            ),
                            example: '[{"period_month": "2025-01", "result": 42}, {"period_month": "2025-02", "result": 58}]'
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'entity', type: 'string', example: 'declarations'),
                                new OA\Property(property: 'operation', type: 'string', example: 'count'),
                                new OA\Property(property: 'target_column', type: 'string', example: '*'),
                                new OA\Property(property: 'period_group_by', type: 'string', nullable: true, example: 'month'),
                                new OA\Property(property: 'group_by', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
                                new OA\Property(property: 'with_period', type: 'boolean', example: true),
                                new OA\Property(property: 'executed_at', type: 'string', format: 'date-time'),
                            ]
                        ),
                        new OA\Property(property: 'from_cache', type: 'boolean', example: false, description: 'Indique si le résultat provient du cache'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Erreur de validation. Retourné quand : entité inconnue, colonne inexistante, relation inexistante, opération invalide, format de période invalide, intervalle non supporté, etc.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'app_code', type: 'string', example: 'FUIP_422'),
                        new OA\Property(property: 'message', type: 'string', example: 'Erreur de validation.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            description: "Détails des erreurs. En cas de FormRequest: {\"field\": [\"message\"]}, en cas de service: {\"details\": \"message\"}",
                            example: '{"details": "Le champ \'age\' (paramètre \'target_column\') n\'existe pas sur la table \'declarations\'. Colonnes disponibles : id, reference, type, state, created_at, updated_at."}'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié. Token JWT manquant ou invalide.'
            ),
            new OA\Response(
                response: 500,
                description: "Erreur serveur. Peut être une erreur SQL (agrégation sur un champ non numérique, etc.) ou une erreur interne.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'app_code', type: 'string', example: 'FUIP_500'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    abstract public function query(Request $request);


    // ================================================================
    // DELETE /api/v1/statistique/cache
    // ================================================================
    #[OA\Delete(
        path: '/api/v1/statistique/cache',
        summary: 'Vider le cache des statistiques',
        description: <<<'DESC'
Supprime toutes les entrées en cache du module Statistique (tag Redis 'statistique').
Utile après une mise à jour massive de données pour forcer le recalcul des statistiques.

**Note :** L'opération est idempotente. Si le cache est déjà vide, aucun effet secondaire.
DESC,
        tags: ['Statistiques'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cache vidé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'app_code', type: 'string', example: 'FUIP_200'),
                        new OA\Property(property: 'message', type: 'string', example: 'Cache des statistiques vidé avec succès.'),
                        new OA\Property(property: 'data', type: 'object', nullable: true, example: null),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(
                response: 500,
                description: 'Erreur lors du vidage du cache (ex: Redis indisponible)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'app_code', type: 'string', example: 'FUIP_500'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    abstract public function clearCache();
}
