<?php

use Illuminate\Support\Facades\Route;
use Modules\Workflow\Http\Controllers\Api\V1\InstanceWorkflowController;
use Modules\Workflow\Http\Controllers\WorkflowController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'verified'])->prefix('workflow')->group(function () {
        // Instance workflow API (proxy to external workflow engine)
        Route::get('instance-workflows', [InstanceWorkflowController::class, 'searchInstances'])
            ->name('workflow.instance-workflows.search');
        Route::get('instance-workflows/{instanceId}', [InstanceWorkflowController::class, 'getInstance'])
            ->name('workflow.instance-workflows.show');
        Route::get('instance-workflows/{instanceId}/svg', [InstanceWorkflowController::class, 'getInstanceSvg'])
            ->name('workflow.instance-workflows.svg');
        Route::get('instance-workflows/{instanceId}/current-step-actions', [InstanceWorkflowController::class, 'getCurrentStepActions'])
            ->name('workflow.instance-workflows.current-step-actions');
        Route::post('instance-workflows/{instanceId}/transition', [InstanceWorkflowController::class, 'executeTransition'])
            ->name('workflow.instance-workflows.transition');
        Route::get('instance-workflows/{instanceId}/history', [InstanceWorkflowController::class, 'getHistory'])
            ->name('workflow.instance-workflows.history');
        Route::post('instance-workflows/{instanceId}/suspend', [InstanceWorkflowController::class, 'suspendWorkflow'])
            ->name('workflow.instance-workflows.suspend');
        Route::post('instance-workflows/{instanceId}/resume', [InstanceWorkflowController::class, 'resumeWorkflow'])
            ->name('workflow.instance-workflows.resume');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('workflows', WorkflowController::class)->names('workflow');
    });
});
