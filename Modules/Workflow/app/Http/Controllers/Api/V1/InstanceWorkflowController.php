<?php

namespace Modules\Workflow\Http\Controllers\Api\V1;

use App\Traits\SendsAppJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Workflow\Http\Requests\ExecuteTransitionRequest;
use Modules\Workflow\Http\Requests\GetCurrentStepActionsRequest;
use Modules\Workflow\Http\Requests\SearchInstancesRequest;
use Modules\Workflow\Services\WorkflowService;
use Symfony\Component\HttpFoundation\Response;

class InstanceWorkflowController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use SendsAppJsonResponse;

    public function __construct(
        protected WorkflowService $service
    ) {
        $this->loadAppCodes();
    }

    public function searchInstances(SearchInstancesRequest $request): JsonResponse
    {
        $filters = $request->only(['statut', 'page', 'size']);
        $data = $this->service->searchInstances(array_filter($filters));
        return $this->sendResponse($data, 'FUIP_100');
    }

    public function getInstance(string $instanceId): JsonResponse
    {
        $this->authorize('workflow.viewInstance');
        $data = $this->service->getInstance($instanceId);
        return $this->sendResponse($data, 'FUIP_101');
    }

    public function getInstanceSvg(string $instanceId): Response|JsonResponse
    {
        $this->authorize('workflow.viewInstance');
        $svg = $this->service->getInstanceSvg($instanceId);
        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function getCurrentStepActions(GetCurrentStepActionsRequest $request, string $instanceId): JsonResponse
    {
        $userId = (string) (Auth::id() ?? $request->query('userId', ''));
        if ($userId === '') {
            throw ValidationException::withMessages(['userId' => ['User context required (authenticated or userId).']]);
        }
        $data = $this->service->getCurrentStepActions(
            $instanceId,
            $userId,
            $request->query('roleFilter'),
            $request->query('functionFilter')
        );
        return $this->sendResponse($data, 'FUIP_100');
    }

    public function executeTransition(ExecuteTransitionRequest $request, string $instanceId): JsonResponse
    {
        $this->authorize('workflow.executeTransition');
        $data = $this->service->executeTransition($instanceId, $request->validated());
        return $this->sendResponse($data, 'FUIP_200');
    }

    public function getHistory(string $instanceId): JsonResponse
    {
        $this->authorize('workflow.viewInstance');
        $data = $this->service->getHistory($instanceId);
        return $this->sendResponse($data, 'FUIP_100');
    }

    public function suspendWorkflow(string $instanceId): JsonResponse
    {
        $this->authorize('workflow.suspendWorkflow');
        $data = $this->service->suspendWorkflow($instanceId);
        return $this->sendResponse($data, 'FUIP_200');
    }

    public function resumeWorkflow(string $instanceId): JsonResponse
    {
        $this->authorize('workflow.resumeWorkflow');
        $data = $this->service->resumeWorkflow($instanceId);
        return $this->sendResponse($data, 'FUIP_200');
    }
}
