<?php

namespace Modules\Users\Services;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Users\DTOs\AuthResponse;
use Modules\Users\Exceptions\KeycloakAuthenticationException;
use Modules\Users\Exceptions\KeycloakConnectionException;
use Modules\Users\Interfaces\AuthInterface;
use Modules\Users\Models\User;

class KeycloakAuthService implements AuthInterface
{
    protected $baseUrl;

    protected $realm;

    protected $clientId;

    protected $clientSecret;

    protected $userService;

    public function __construct()
    {
        $this->baseUrl = config('services.keycloak.base_url');
        $this->realm = config('services.keycloak.realm');
        $this->clientId = config('services.keycloak.client_id');
        $this->clientSecret = config('services.keycloak.client_secret');
        $this->userService = new UserService;
    }

    public function login(string $email, string $password): AuthResponse
    {
        // Validate configuration
        $this->validateConfiguration();

        try {
            // 1. Request Token from Keycloak (Password Grant)
            $response = Http::timeout(10)->asForm()->post("{$this->baseUrl}/realms/{$this->realm}/protocol/openid-connect/token", [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $email,
                'password' => $password,
                'scope' => 'openid profile email',
            ]);
        } catch (ConnectionException $e) {
            throw new KeycloakConnectionException('Unable to connect to Keycloak server: '.$e->getMessage());
        } catch (Exception $e) {
            throw new KeycloakConnectionException('Network error occurred: '.$e->getMessage());
        }

        if ($response->failed()) {
            $errorMessage = $response->json()['error_description'] ?? 'Invalid credentials or Keycloak server error.';
            throw new KeycloakAuthenticationException($errorMessage);
        }

        $tokenData = $response->json();

        if (! isset($tokenData['access_token'])) {
            throw new KeycloakConnectionException('Invalid response from Keycloak: access token missing.');
        }

        try {
            // 2. Extract Keycloak ID (sub) from the token or userinfo
            $userInfoResponse = Http::timeout(10)->withToken($tokenData['access_token'])
                ->get("{$this->baseUrl}/realms/{$this->realm}/protocol/openid-connect/userinfo");

            if ($userInfoResponse->failed()) {
                throw new KeycloakConnectionException('Failed to retrieve user information from Keycloak.');
            }

            $userData = $userInfoResponse->json();
        } catch (ConnectionException $e) {
            throw new KeycloakConnectionException('Unable to connect to Keycloak server: '.$e->getMessage());
        } catch (Exception $e) {
            throw new KeycloakConnectionException('Error retrieving user info: '.$e->getMessage());
        }

        if (! isset($userData['sub'])) {
            throw new KeycloakConnectionException('Invalid user data from Keycloak: user ID missing.');
        }

        try {
            $user = $this->userService->updateOrCreate(
                ['keycloak_id' => $userData['sub']],
                [
                    'email' => $userData['email'] ?? $email,
                    'username' => $userData['preferred_username'] ?? $email,
                    'first_name' => $userData['given_name'] ?? null,
                    'last_name' => $userData['family_name'] ?? null,
                    'is_active' => true,
                ]
            );
        } catch (Exception $e) {
            throw new Exception('Failed to create or update user in database: '.$e->getMessage(), 500);
        }

        return new AuthResponse(
            user_uuid: $user->id,
            token: $tokenData['access_token']
        );
    }

    /**
     * Validate Keycloak configuration.
     */
    protected function validateConfiguration(): void
    {
        if (empty($this->baseUrl)) {
            throw new KeycloakConnectionException('Keycloak base URL is not configured.');
        }

        if (empty($this->realm)) {
            throw new KeycloakConnectionException('Keycloak realm is not configured.');
        }

        if (empty($this->clientId)) {
            throw new KeycloakConnectionException('Keycloak client ID is not configured.');
        }

        if (empty($this->clientSecret)) {
            throw new KeycloakConnectionException('Keycloak client secret is not configured.');
        }

        //test if the configuration is valid
        $response = Http::get("{$this->baseUrl}/realms/{$this->realm}");
        if ($response->failed()) {
            throw new KeycloakConnectionException('Invalid Keycloak configuration.');
        }
    }

    public function register(string $email, string $password): User
    {
        // 1. Get Admin Access Token to create user
        $adminToken = $this->getAdminAccessToken();

        // 2. Create User in Keycloak via Admin API
        $response = Http::withToken($adminToken)->post("{$this->baseUrl}/admin/realms/{$this->realm}/users", [
            'email' => $email,
            'username' => $email,
            'enabled' => true,
            'credentials' => [[
                'type' => 'password',
                'value' => $password,
                'temporary' => false,
            ]],
        ]);

        if ($response->status() !== 201) {
            throw new Exception('Failed to register user in Keycloak.');
        }

        // 3. Get the created Keycloak ID from the Location header
        $location = $response->header('Location');
        $keycloakId = Str::afterLast($location, '/');

        // 4. Create local record
        return User::create([
            'id' => Str::uuid(),
            'keycloak_id' => $keycloakId,
            'email' => $email,
            'username' => $email,
            'role' => 'candidate',
        ]);
    }

    public function logout(): void
    {
        // Implement OpenID Connect Logout redirect or Token Revocation
        session()->flush();
    }

    public function forgotPassword(string $email): void
    {
        // Trigger Keycloak "execute-actions-email" Admin API
        // This tells Keycloak to send its own branded recovery email
    }

    public function resetPassword(string $email, string $password): void
    {
        // Admin API call to update user credentials in Keycloak
    }

    private function getAdminAccessToken()
    {
        return Http::asForm()->post("{$this->baseUrl}/realms/{$this->realm}/protocol/openid-connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ])->json()['access_token'];
    }
}
