<?php

return [
    'enabled' => env('ACTIVITY_LOG_ENABLED', true),

    'user_model' => \Modules\Securite\Models\User::class,

    'exclude_models' => [
        \Modules\ActivityLog\Models\ActivityLog::class,
    ],

    'audited_models' => [
        \Modules\Securite\Models\User::class,
        \Modules\Securite\Models\Role::class,
        \Modules\Securite\Models\Permission::class,
        \Modules\Geographie\Models\Province::class,
        \Modules\Geographie\Models\Ville::class,
        \Modules\Geographie\Models\Quartier::class,
        \Modules\Declaration\Models\Declaration::class,
        \Modules\Declaration\Models\DeclarationDisparition::class,
        \Modules\Declaration\Models\DeclarationVolVehicule::class,
        \Modules\Declaration\Models\DeclarationPerteDocument::class,
        \Modules\Declaration\Models\DeclarationFichier::class,
        \Modules\Personne\Models\Personne::class,
        \Modules\Personne\Models\PersonneFichier::class,
        \Modules\Procedure\Models\Gardevue::class,
        \Modules\Procedure\Models\Infraction::class,
        \Modules\Referentiel\Models\Bureau::class,
        \Modules\Referentiel\Models\Unite::class,
        \Modules\Referentiel\Models\Nationalite::class,
        \Modules\Referentiel\Models\TypeDocument::class,
        \Modules\Referentiel\Models\TypeDeclaration::class,
        \Modules\Referentiel\Models\TypeInfraction::class,
        \Modules\Referentiel\Models\MarqueVehicule::class,
        \Modules\Referentiel\Models\ModeleVehicule::class,
        \Modules\Referentiel\Models\TypeVehicule::class,
        \Modules\Document\Models\TemplateDocument::class,
        \Modules\Document\Models\GeneratedDocument::class,
        \Modules\Procedure\Models\ProcedureFichier::class,
        \Modules\Message\Models\Message::class,
        \Modules\Message\Models\MessageFichier::class
    ],

    'default_actions' => ['created', 'updated', 'deleted'],

    'ignored_attributes' => ['updated_at', 'remember_token', 'password'],

    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'token',
        'secret',
        'api_key',
    ],

    'cleanup' => [
        'enabled' => env('ACTIVITY_LOG_CLEANUP_ENABLED', false),
        'older_than_days' => 90,
    ],
];
