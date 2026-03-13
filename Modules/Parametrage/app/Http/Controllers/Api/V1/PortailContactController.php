<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailContactRequest;
use Modules\Parametrage\Services\PortailContactService;

class PortailContactController extends Controller
{
    public function __construct(PortailContactService $service)
    {
        parent::__construct($service);
    }

    public function show(): JsonResponse
    {
        $contact = $this->service->get();
        if (! $contact) {
            return $this->sendError('FUIP_404');
        }
        return $this->sendResponse($contact, 'FUIP_100');
    }

    public function update(UpdatePortailContactRequest $request): JsonResponse
    {
        $contact = $this->service->update($request->validated());
        return $this->sendResponse($contact, 'FUIP_200');
    }
}
