<?php

namespace App\Http\Controllers;

use App\Contracts\ArrayableDTO;
use App\Contracts\AuditLoggerInterface;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Online School API',
    description: 'API microservice for Online School multi-tenant',
    contact: new OA\Contact(email: 'support@onlineschool.com'),
    license: new OA\License(name: 'Proprietary', url: 'https://onlineschool.com/license')
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Local Development Server')]
#[OA\Server(url: 'https://api.yourdomain.com', description: 'Production Server')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Enter JWT token'
)]
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected array $appCodes = [];

    public function __construct(
        protected ?BaseService $service = null
    ) {
        $path = base_path('app_code_responses.json');

        if (!File::exists($path)) {
            \Log::warning('app_code_responses.json not found at: ' . $path);
            $this->appCodes = [];
            return;
        }

        $content = File::get($path);
        $codes = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Invalid JSON in app_code_responses.json: ' . json_last_error_msg());
            $this->appCodes = [];
            return;
        }

        $this->appCodes = $codes ?? [];
    }

    protected function getAppMessage(string $code): string
    {
        return $this->appCodes[$code] ?? 'This action not reported yet';
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => Auth::guard('api')->user()->load('roles', 'roles.permissions')
        ]);
    }

    public function sendForbiddenResponse(): JsonResponse
    {
        return response()->json([
            'success'  => false,
            'app_code' => 'ERR_PERMISSION_DENIED',
            'message'  => $this->appCodes['ERR_PERMISSION_DENIED'] ?? "Vous n'êtes pas autorisé à effectuer cette action.",
            'errors'   => [
                'detail' => 'Permission refusée.'
            ]
        ], 403);
    }

    protected function sendResponse(mixed $data, string $appCode, int $httpCode = 200): JsonResponse
    {
        return response()->json([
            'success'  => true,
            'app_code' => $appCode,
            'message'  => $this->appCodes[$appCode] ?? 'This action not reported yet',
            'data'     => $data,
        ], $httpCode);
    }

    protected function sendError(string $appCode, array $errors = [], int $httpCode = 404): JsonResponse
    {
        return response()->json([
            'success'  => false,
            'app_code' => $appCode,
            'message'  => $this->appCodes[$appCode] ?? 'This action not reported yet',
            'errors'   => $errors,
        ], $httpCode);
    }

    /**
     * Get all records. Call with validated/array data only, e.g. ['populate' => ...].
     */
    public function getAllModel(array $params = []): JsonResponse
    {
        if (!$this->service) {
            return $this->sendError('FUIP_400', ['detail' => 'No service bound'], 400);
        }
        $populate = $params['populate'] ?? null;
        $data = $this->service->all($populate);
        return $this->sendResponse($data, 'FUIP_100');
    }

    /**
     * Paginate records. Call with validated/array data only.
     * Expected keys: per_page (int), page (int), populate (string|null), sort (array|string|null), search (array|string|null).
     */
    public function paginateModel(array $params): JsonResponse
    {
        if (!$this->service) {
            return $this->sendError('FUIP_400', ['detail' => 'No service bound'], 400);
        }
        $sort = $params['sort'] ?? null;
        if (is_string($sort) && $sort !== '' && json_validate($sort)) {
            $sort = json_decode($sort, true);
        } elseif (is_string($sort)) {
            $sort = null;
        }
        $search = $params['search'] ?? null;
        if (is_string($search) && $search !== '' && json_validate($search)) {
            $search = json_decode($search, true);
        } elseif (is_string($search)) {
            $search = null;
        }
        $populate = $params['populate'] ?? null;

        $data = $this->service->paginate(
            (int) ($params['per_page'] ?? 15),
            (int) ($params['page'] ?? 1),
            $populate,
            $sort,
            $search
        );
        if (app()->bound(AuditLoggerInterface::class)) {
            app(AuditLoggerInterface::class)->logListed($this->service->getEntityName());
        }
        return $this->sendResponse($data, 'FUIP_100');
    }

    /**
     * Get one record by model. Call with validated/array data only, e.g. ['populate' => ...].
     */
    public function getModel(Model $model, array $params = []): JsonResponse
    {
        if (!$this->service) {
            return $this->sendError('FUIP_400', ['detail' => 'No service bound'], 400);
        }
        $populate = $params['populate'] ?? null;
        $record = $this->service->find($model->id, $populate);
        if ($record && app()->bound(AuditLoggerInterface::class)) {
            app(AuditLoggerInterface::class)->logViewed($record);
        }
        return $record ? $this->sendResponse($record, 'FUIP_101') : $this->sendError('FUIP_404');
    }

    /**
     * Create a record from a DTO. Use FormRequest → DTO::fromRequest() then this method.
     */
    public function createFromDTO(ArrayableDTO $dto): JsonResponse
    {
        if (! $this->service) {
            return $this->sendError('FUIP_400', ['detail' => 'No service bound'], 400);
        }
        try {
            $record = $this->service->create($dto->toArray());
            return $this->sendResponse($record, 'FUIP_201', 201);
        } catch (\Exception $e) {
            return $this->sendError('FUIP_500', ['details' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a record from a DTO. Use FormRequest → DTO::fromRequest() then this method.
     */
    public function updateFromDTO(Model $model, ArrayableDTO $dto): JsonResponse
    {
        if (! $this->service) {
            return $this->sendError('FUIP_400', ['detail' => 'No service bound'], 400);
        }
        try {
            $record = $this->service->update($model->id, $dto->toArray());
            return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
        } catch (\Exception $e) {
            return $this->sendError('FUIP_500', ['details' => $e->getMessage()], 500);
        }
    }

    public function deleteModel(Model $model): JsonResponse
    {
        if (!$this->service) {
            return $this->sendError('FUIP_400', ['detail' => 'No service bound'], 400);
        }
        $deleted = $this->service->delete($model->id);
        return $deleted ? $this->sendResponse($model, 'FUIP_204') : $this->sendError('FUIP_404');
    }
}
