<?php

namespace Modules\Securite\Documentation\Swagger;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Authentification",
    description: "Endpoints pour la gestion des sessions et de la sécurité (JWT)"
)]
abstract class AuthControllerSwagger
{
    #[OA\Post(
        path: "/api/v1/auth/reset-password",
        summary: "Demande de réinitialisation du mot de passe",
        description: "Envoie un email (via aninfpush, même template que la vérification email / création utilisateur) avec un lien contenant token et email. L'utilisateur ouvre le lien et appelle POST /auth/confirm-email avec token, email et nouveau mot de passe. Si l'email n'existe pas, aucune erreur n'est retournée.",
        tags: ["Authentification"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Demande acceptée (email envoyé si le compte existe)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "app_code", type: "string", example: "FUIP_200"),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items())
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Erreur de validation (email invalide)")
        ]
    )]
    abstract public function resetPassword(): JsonResponse;

    #[OA\Post(
        path: "/api/v1/auth/login",
        summary: "Authentification de l'utilisateur",
        tags: ["Authentification"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@onlineschool.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Token généré avec succès, ou requires_2fa si l'utilisateur a la 2FA activée",
                content: new OA\JsonContent(
                    description: "Si 2FA activée: success, requires_2fa=true, user_id, temp_2fa_token (code 6 chiffres envoyé par email). Sinon: success, access_token, token_type, expires_in, user",
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "requires_2fa", type: "boolean", nullable: true, description: "Présent et true si un code OTP doit être fourni via POST /auth/2fa/verify"),
                        new OA\Property(property: "user_id", type: "string", format: "uuid", nullable: true, description: "À envoyer à POST /auth/2fa/verify (pour Google Authenticator) ou pour référence"),
                        new OA\Property(property: "temp_2fa_token", type: "string", nullable: true, description: "Token temporaire pour vérifier le code envoyé par email ; envoyer avec otp à POST /auth/2fa/verify (alternative à user_id + Google TOTP)"),
                        new OA\Property(property: "access_token", type: "string", nullable: true),
                        new OA\Property(property: "token_type", type: "string", example: "bearer", nullable: true),
                        new OA\Property(property: "expires_in", type: "integer", example: 3600, nullable: true),
                        new OA\Property(property: "user", type: "object", ref: "#/components/schemas/User", nullable: true)
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Identifiants invalides"),
            new OA\Response(response: 403, description: "Compte bloqué")
        ]
    )]
    abstract public function login();

    #[OA\Post(
        path: "/api/v1/auth/confirm-email",
        summary: "Confirmer l'email et définir le mot de passe",
        description: "Valide le compte avec le token reçu par email, définit email_verified_at et met à jour le mot de passe.",
        tags: ["Authentification"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "token", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "token", type: "string", description: "Token reçu par email de vérification"),
                    new OA\Property(property: "password", type: "string", format: "password", minLength: 8, example: "password123"),
                    new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "password123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Compte confirmé et mot de passe mis à jour",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "app_code", type: "string", example: "FUIP_200"),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "object", ref: "#/components/schemas/User")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Token invalide ou expiré, ou email ne correspond pas au token"),
            new OA\Response(response: 422, description: "Erreur de validation")
        ]
    )]
    abstract public function confirmEmail();

    #[OA\Post(
        path: "/api/v1/auth/2fa/verify",
        summary: "Vérifier le code OTP et obtenir le token (après login avec 2FA)",
        description: "Après un login qui a retourné requires_2fa, utiliser soit (A) temp_2fa_token + otp pour le code envoyé par email, soit (B) user_id + otp pour Google Authenticator (TOTP). Un seul des deux (temp_2fa_token ou user_id) est requis.",
        tags: ["Authentification", "2FA"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["otp"],
                properties: [
                    new OA\Property(property: "user_id", type: "string", format: "uuid", nullable: true, description: "ID utilisateur (pour vérification Google Authenticator)"),
                    new OA\Property(property: "temp_2fa_token", type: "string", nullable: true, description: "Token temporaire retourné par login (pour vérifier le code envoyé par email)"),
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Code à 6 chiffres (app authentificatrice ou email)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Token généré avec succès",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "access_token", type: "string"),
                        new OA\Property(property: "token_type", type: "string", example: "bearer"),
                        new OA\Property(property: "expires_in", type: "integer"),
                        new OA\Property(property: "user", type: "object", ref: "#/components/schemas/User")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Code OTP invalide"),
            new OA\Response(response: 422, description: "Validation échouée ou 2FA non activée pour ce compte")
        ]
    )]
    abstract public function verify2fa();

    #[OA\Post(
        path: "/api/v1/auth/logout",
        summary: "Déconnexion (Invalidation du token)",
        security: [["bearerAuth" => []]],
        tags: ["Authentification"],
        responses: [
            new OA\Response(response: 200, description: "Déconnexion réussie"),
            new OA\Response(response: 401, description: "Non authentifié")
        ]
    )]
    abstract public function logout();

    #[OA\Post(
        path: "/api/v1/auth/refresh",
        summary: "Rafraîchir le token expiré",
        description: "Permet d'obtenir un nouveau token avant la fin de la période de rafraîchissement (Refresh TTL)",
        tags: ["Authentification"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Nouveau token généré"),
            new OA\Response(response: 401, description: "Token invalide ou période de rafraîchissement expirée")
        ]
    )]
    abstract public function refresh();

    #[OA\Get(
        path: "/api/v1/auth/me",
        summary: "Récupérer les informations de l'utilisateur connecté",
        security: [["bearerAuth" => []]],
        tags: ["Authentification"],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Profil utilisateur",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            )
        ]
    )]
    abstract public function me();
}