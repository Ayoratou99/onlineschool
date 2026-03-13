<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TenantStorageService;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailConfigRequest;
use Modules\Parametrage\Services\PortailConfigService;

class PortailConfigController extends Controller
{
    public function __construct(
        PortailConfigService $service,
        protected TenantStorageService $storage
    ) {
        parent::__construct($service);
    }

    public function show(): JsonResponse
    {
        $config = $this->service->get();
        if (! $config) {
            return $this->sendError('FUIP_404');
        }
        return $this->sendResponse($config, 'FUIP_100');
    }

    public function update(UpdatePortailConfigRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['logo'], $data['favicon']);

        if ($request->hasFile('logo')) {
            $data['logo_url'] = $this->storage->put('portail/logos/' . uniqid() . '.png', $request->file('logo'));
        }
        if ($request->hasFile('favicon')) {
            $data['favicon_url'] = $this->storage->put('portail/favicons/' . uniqid() . '.ico', $request->file('favicon'));
        }

        $config = $this->service->update($data);
        return $this->sendResponse($config, 'FUIP_200');
    }
}
