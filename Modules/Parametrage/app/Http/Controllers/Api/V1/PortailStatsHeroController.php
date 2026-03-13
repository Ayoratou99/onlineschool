<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\ReorderPortailRequest;
use Modules\Parametrage\Http\Requests\Portail\StorePortailStatsHeroRequest;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailStatsHeroRequest;
use Modules\Parametrage\Models\PortailStatsHero;
use Modules\Parametrage\Services\PortailStatsHeroService;

class PortailStatsHeroController extends Controller
{
    public function __construct(PortailStatsHeroService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function store(StorePortailStatsHeroRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $data['is_active'] ?? true;
        $record = PortailStatsHero::create($data);
        return $this->sendResponse($record, 'FUIP_201', 201);
    }

    public function show(PortailStatsHero $portail_stats_hero, IndexQueryRequest $request): JsonResponse
    {
        $params = $request->validated();
        $record = $this->service->find($portail_stats_hero->id, $params['populate'] ?? null);
        return $record ? $this->sendResponse($record, 'FUIP_101') : $this->sendError('FUIP_404');
    }

    public function update(PortailStatsHero $portail_stats_hero, UpdatePortailStatsHeroRequest $request): JsonResponse
    {
        $record = $this->service->update($portail_stats_hero->id, $request->validated());
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }

    public function destroy(PortailStatsHero $portail_stats_hero): JsonResponse
    {
        $deleted = $this->service->delete($portail_stats_hero->id);
        return $deleted ? $this->sendResponse($portail_stats_hero, 'FUIP_204') : $this->sendError('FUIP_404');
    }

    public function reorder(ReorderPortailRequest $request): JsonResponse
    {
        $this->service->reorder($request->validated('ids'));
        return $this->sendResponse(['ids' => $request->validated('ids')], 'FUIP_200');
    }
}
