<?php

namespace Modules\Workflow\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WorkflowService
{
    private PendingRequest $client;
    private string $baseUrl;
    private string $appToken;
    public function __construct()
    {
        $this->baseUrl  = config('workflow.api.base_url');
        $this->appToken = config('workflow.api.app_token');
        $this->jwtToken = $this->authenticate();
    
        $this->client = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Authorization' => "Bearer {$this->jwtToken}",
                'X-API-TOKEN'   => $this->appToken,
                'Accept'        => 'application/json',
            ]);
    }
    
    private function authenticate(): string
    {
        $response = Http::baseUrl(config('workflow.api.base_url'))
            ->withHeaders(['Accept' => '*/*'])
            ->post('/api/authenticate', [
                'username'   => config('workflow.api.username'),
                'password'   => config('workflow.api.password'),
                'rememberMe' => true,
            ]);
    
        if (!$response->successful()) {
            throw new RuntimeException('Workflow API authentication failed: ' . $response->body());
        }
    
        $token = $response->json('id_token');
    
        if (empty($token)) {
            throw new RuntimeException('Workflow API authentication returned no token.');
        }
    
        return $token;
    }

    public function startWorkflow(array $data): array
    {
        $required = ['objectId', 'objectType', 'circuitId', 'userId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Field '{$field}' is required and cannot be empty.");
            }
        }

        $response = $this->client
            ->post('/api/instance-workflows/start', $data);

        return $this->handleResponse($response);
    }

    public function getInstanceSvg(string $instanceId): string
    {
        $response = $this->client
            ->withHeaders(['Accept' => 'image/svg+xml'])
            ->get("/api/instance-workflows/{$instanceId}/svg");
    
        if ($response->successful()) {
            return $response->body();
        }
    
        $detail = $response->body();
        $status = $response->status();
    
        throw new RuntimeException("Failed to fetch SVG [{$status}]: {$detail}", $status);
    }


    // -------------------------------------------------------------------------
    // 2. Get a single instance by ID
    // GET /api/instance-workflows/{instanceId}
    // -------------------------------------------------------------------------

    public function getInstance(string $instanceId): array
    {
        $response = $this->client
            ->get("/api/instance-workflows/{$instanceId}");

        return $this->handleResponse($response);
    }
    // -------------------------------------------------------------------------
    // 2. Get a single instance by business object ID
    // GET /api/instance-workflows/by-object/{objectId}
    // -------------------------------------------------------------------------

    public function getInstanceByObject(string $objectId): array
    {
        $response = $this->client
            ->get("/api/instance-workflows/by-object/{$objectId}");

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 3. Get all instances for a business object (history)
    // GET /api/instance-workflows/list-by-object/{objectId}
    // -------------------------------------------------------------------------

    public function listInstancesByObject(string $objectId): array
    {
        $response = $this->client
            ->get("/api/instance-workflows/list-by-object/{$objectId}");

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 4. Get available actions for the current step
    // GET /api/instance-workflows/{instanceId}/current-step-actions
    // -------------------------------------------------------------------------

    public function getCurrentStepActions(
        string  $instanceId,
        string  $userId,
        ?string $roleFilter     = null,
        ?string $functionFilter = null,
    ): array {
        $query = array_filter([
            'userId'         => $userId,
            'roleFilter'     => $roleFilter,
            'functionFilter' => $functionFilter,
        ]);

        $response = $this->client
            ->get("/api/instance-workflows/{$instanceId}/current-step-actions", $query);

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 5. Execute a transition
    // POST /api/instance-workflows/{instanceId}/transition
    // -------------------------------------------------------------------------

    /**
     * @param  array{
     *     action: string,
     *     userId: string,
     *     commentaire?: string
     * } $data
     */
    public function executeTransition(string $instanceId, array $data): array
    {
        foreach (['action', 'userId'] as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Field '{$field}' is required and cannot be empty.");
            }
        }

        $response = $this->client
            ->post("/api/instance-workflows/{$instanceId}/transition", $data);

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 6. Get workflow history
    // GET /api/instance-workflows/{instanceId}/history
    // -------------------------------------------------------------------------

    public function getHistory(string $instanceId): array
    {
        $response = $this->client
            ->get("/api/instance-workflows/{$instanceId}/history");

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 7. Suspend a workflow
    // POST /api/instance-workflows/{instanceId}/suspend
    // -------------------------------------------------------------------------

    public function suspendWorkflow(string $instanceId): array
    {
        $response = $this->client
            ->post("/api/instance-workflows/{$instanceId}/suspend");

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 8. Resume a suspended workflow
    // POST /api/instance-workflows/{instanceId}/resume
    // -------------------------------------------------------------------------

    public function resumeWorkflow(string $instanceId): array
    {
        $response = $this->client
            ->post("/api/instance-workflows/{$instanceId}/resume");

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // 9. Search instances by criteria
    // GET /api/instance-workflows
    // -------------------------------------------------------------------------

    /**
     * @param  array{statut?: string, page?: int, size?: int} $filters
     */
    public function searchInstances(array $filters = []): array
    {
        $response = $this->client
            ->get('/api/instance-workflows', $filters);

        return $this->handleResponse($response);
    }

    // -------------------------------------------------------------------------
    // Response handler
    // -------------------------------------------------------------------------

    private function handleResponse(Response $response): array
    {
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        $status = $response->status();
        $body   = $response->body();
        $json   = $response->json();
        $detail = $json['detail'] ?? data_get($json, 'message') ?? $body;
        $detail = is_string($detail) ? $detail : (string) json_encode($detail);

        $isApplicationNotFound = $status === 400 && (
            str_contains((string) $detail, 'application') ||
            str_contains((string) $detail, 'utilisateur') ||
            str_contains($body, 'applicationnotfound') ||
            str_contains($body, 'Application non trouvée')
        );

        if ($isApplicationNotFound) {
            Log::channel('stack')->warning('Workflow API application not found (400)', [
                'status'   => $status,
                'response' => $json ?: $body,
                'body_raw' => $body,
            ]);
        } else {
            Log::channel('stack')->info('Workflow API error response', [
                'status'   => $status,
                'response' => $json ?: $body,
            ]);
        }

        $message = match (true) {
            $status === 401                                         => 'Authentication required: invalid or missing JWT token.',
            $status === 404 && str_contains($detail, 'circuit')    => 'Circuit not found.',
            $status === 404 && str_contains($detail, 'instance')   => 'Workflow instance not found.',
            $status === 404                                         => 'Resource not found.',
            $status === 400 && str_contains($detail, 'Required request parameter') => 'Missing required parameters. Check your JSON payload.',
            $status === 400 && str_contains($detail, 'circuitunauthorized')        => 'Circuit not authorized for this application.',
            $status === 400 && str_contains($detail, 'workflowalreadyrunning')     => 'A workflow is already running for this object.',
            $status === 400 && str_contains($detail, 'instanceunauthorized')       => 'This instance is not accessible by this application.',
            $status === 400 && str_contains($detail, 'duplicate_composite_key')    => 'A workflow with this circuit/object combination already exists.',
            $status === 400                                         => "Validation error: {$detail}",
            default                                                 => "Server error [{$status}]: {$detail}",
        };

        throw new RuntimeException($message, $status);
    }

}
