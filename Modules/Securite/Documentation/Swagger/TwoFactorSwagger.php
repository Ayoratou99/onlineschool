<?php

namespace Modules\Securite\Documentation\Swagger;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "2FA",
    description: "Authentification à deux facteurs (2FA) – activer, confirmer, désactiver"
)]
abstract class TwoFactorSwagger
{
    #[OA\Post(
        path: "/api/v1/auth/2fa/enable",
        summary: "Activer la 2FA (générer le secret et l'URL QR)",
        description: "Génère un secret et une URL QR. L'utilisateur scanne le QR avec une app (Google Authenticator, etc.) puis appelle POST /auth/2fa/confirm avec le code à 6 chiffres.",
        security: [["bearerAuth" => []]],
        tags: ["Authentification"],
        responses: [
            new OA\Response(
                response: 200,
                description: "QR code URL et secret générés",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "qr_code", type: "string", description: "URL pour afficher le QR code"),
                        new OA\Property(property: "secret", type: "string", description: "Secret clé (pour saisie manuelle si besoin)")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Non authentifié")
        ]
    )]
    abstract public function enable(Request $request);

    #[OA\Post(
        path: "/api/v1/auth/2fa/confirm",
        summary: "Confirmer et activer la 2FA",
        description: "Après avoir scanné le QR (POST /auth/2fa/enable), envoyer le code OTP à 6 chiffres pour activer définitivement la 2FA.",
        security: [["bearerAuth" => []]],
        tags: ["Authentification"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["otp"],
                properties: [
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Code à 6 chiffres de l'app authentificatrice")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "2FA activée avec succès"),
            new OA\Response(response: 401, description: "Non authentifié"),
            new OA\Response(response: 422, description: "Code OTP invalide")
        ]
    )]
    abstract public function confirm(Request $request);

    #[OA\Post(
        path: "/api/v1/auth/2fa/disable",
        summary: "Désactiver la 2FA pour l'utilisateur connecté",
        description: "Requiert le code OTP actuel pour confirmer la désactivation.",
        security: [["bearerAuth" => []]],
        tags: ["Authentification"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["otp"],
                properties: [
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Code à 6 chiffres actuel")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "2FA désactivée avec succès"),
            new OA\Response(response: 401, description: "Non authentifié"),
            new OA\Response(response: 422, description: "Code OTP invalide ou 2FA non activée")
        ]
    )]
    abstract public function disable(Request $request);

    #[OA\Post(
        path: "/api/v1/securite/user/{user}/reset-2fa",
        summary: "Réinitialiser la 2FA d'un utilisateur (admin)",
        description: "Désactive la 2FA pour l'utilisateur cible sans code OTP. Réservé aux rôles ADMIN ou permission RESET_2FA_UTILISATEUR.",
        security: [["bearerAuth" => []]],
        tags: ["Authentification", "Utilisateurs"],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "string", format: "uuid"), description: "ID de l'utilisateur")
        ],
        responses: [
            new OA\Response(response: 200, description: "2FA réinitialisée pour cet utilisateur"),
            new OA\Response(response: 401, description: "Non authentifié"),
            new OA\Response(response: 403, description: "Pas le droit de réinitialiser la 2FA"),
            new OA\Response(response: 404, description: "Utilisateur non trouvé")
        ]
    )]
    abstract public function reset(Request $request);
}
