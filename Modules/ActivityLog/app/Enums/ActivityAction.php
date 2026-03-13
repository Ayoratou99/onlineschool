<?php

namespace Modules\ActivityLog\Enums;

enum ActivityAction: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case RETRIEVED = 'retrieved';
    case VIEWED = 'viewed';
    case LISTED = 'listed';
    case AUTHENTICATED = 'authenticated';
    case LOGOUT = 'logout';
    case FAILED_LOGIN = 'failed_login';
    case PASSWORD_RESET = 'password_reset';
    case PASSWORD_CHANGED = 'password_changed';
    case EMAIL_VERIFIED = 'email_verified';
    case RESTORED = 'restored';
    case FORCE_DELETED = 'force_deleted';
    case EXPORTED = 'exported';
    case IMPORTED = 'imported';
    case ASSIGNED = 'assigned';
    case UNASSIGNED = 'unassigned';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case DOWNLOADED = 'downloaded';
    case GENERATED = 'generated';

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Créé',
            self::UPDATED => 'Modifié',
            self::DELETED => 'Supprimé',
            self::RETRIEVED => 'Récupéré',
            self::VIEWED => 'Consulté',
            self::LISTED => 'Listé',
            self::AUTHENTICATED => 'Connecté',
            self::LOGOUT => 'Déconnecté',
            self::FAILED_LOGIN => 'Tentative de connexion échouée',
            self::PASSWORD_RESET => 'Réinitialisation du mot de passe',
            self::PASSWORD_CHANGED => 'Mot de passe modifié',
            self::EMAIL_VERIFIED => 'Email vérifié',
            self::RESTORED => 'Restauré',
            self::FORCE_DELETED => 'Suppression définitive',
            self::EXPORTED => 'Exporté',
            self::IMPORTED => 'Importé',
            self::ASSIGNED => 'Assigné',
            self::UNASSIGNED => 'Désassigné',
            self::APPROVED => 'Approuvé',
            self::REJECTED => 'Rejeté',
            self::DOWNLOADED => 'Téléchargé',
            self::GENERATED => 'Document généré',
        };
    }
}
