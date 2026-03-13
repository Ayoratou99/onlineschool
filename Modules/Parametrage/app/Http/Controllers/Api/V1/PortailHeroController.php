<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TenantStorageService;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailHeroRequest;
use Modules\Parametrage\Services\PortailHeroService;

class PortailHeroController extends Controller
{
    public function __construct(
        PortailHeroService $service,
        protected TenantStorageService $storage
    ) {
        parent::__construct($service);
    }

    public function show(): JsonResponse
    {
        $hero = $this->service->get();
        if (! $hero) {
            return $this->sendError('FUIP_404');
        }
        return $this->sendResponse($hero, 'FUIP_100');
    }

    public function update(UpdatePortailHeroRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storage->put('portail/hero/' . uniqid() . '.jpg', $request->file('image'));
        }

        $hero = $this->service->update($data);
        return $this->sendResponse($hero, 'FUIP_200');
    }
}
