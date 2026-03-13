<?php

namespace Modules\ActivityLog\Http\Controllers\Api\V1;

use App\Contracts\AuditLoggerInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\ActivityLog\Http\Requests\IndexActivityLogRequest;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Services\ActivityLogService;
use Modules\ActivityLog\Documentation\Swagger\ActivityLogSwagger;

class ActivityLogController extends Controller
{
    use ActivityLogSwagger;

    public function __construct(ActivityLogService $service)
    {
        parent::__construct($service);
    }

    public function getAll(IndexQueryRequest $request): JsonResponse
    {
        return $this->getAllModel($request->validated());
    }

    public function index(IndexActivityLogRequest $request): JsonResponse
    {
        $data = $this->service->paginateWithFilters(
            (int) $request->query('per_page', 15),
            (int) $request->query('page', 1),
            $request->getFilters(),
            $request->query('populate'),
            $request->getSort(),
            $request->getSearch()
        );
        if (app()->bound(AuditLoggerInterface::class)) {
            app(AuditLoggerInterface::class)->logListed('activity_log');
        }
        return $this->sendResponse($data, 'FUIP_100');
    }

    public function show(ActivityLog $activityLog, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $activityLog);
        return $this->getModel($activityLog, $request->validated());
    }
}
