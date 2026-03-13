<?php

namespace Modules\Statistique\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Statistique\Http\Requests\StatistiqueQueryRequest;
use Modules\Statistique\Services\StatistiqueCacheManager;
use Modules\Statistique\Services\StatistiqueService;

class StatistiqueController extends Controller
{
    protected array $appCodes = [];

    public function __construct(
        private StatistiqueService $service,
        private StatistiqueCacheManager $cacheManager,
    ) {
        $path = base_path('app_code_responses.json');
        if (File::exists($path)) {
            $this->appCodes = json_decode(File::get($path), true) ?? [];
        }
    }

    /**
     * Exécuter une requête statistique dynamique.
     *
     * POST /api/v1/statistique/query
     */
    public function query(StatistiqueQueryRequest $request): JsonResponse
    {
        try {
            $result = $this->service->execute($request->validated());

            return response()->json([
                'success'  => true,
                'app_code' => 'FUIP_100',
                'message'  => $this->appCodes['FUIP_100'] ?? 'Statistiques récupérées avec succès.',
                'data'     => $result['result'],
                'meta'     => $result['meta'],
                'from_cache' => $result['from_cache'],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success'  => false,
                'app_code' => 'FUIP_422',
                'message'  => $this->appCodes['FUIP_422'] ?? 'Erreur de validation.',
                'errors'   => ['details' => $e->getMessage()],
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success'  => false,
                'app_code' => 'FUIP_500',
                'message'  => $this->appCodes['FUIP_500'] ?? 'Erreur lors de l\'exécution de la requête.',
                'errors'   => ['details' => 'Erreur SQL : ' . $e->getMessage()],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success'  => false,
                'app_code' => 'FUIP_500',
                'message'  => $this->appCodes['FUIP_500'] ?? 'Erreur interne du serveur.',
                'errors'   => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Vider le cache des statistiques.
     *
     * DELETE /api/v1/statistique/cache
     */
    public function clearCache(): JsonResponse
    {
        try {
            $this->cacheManager->flush();

            return response()->json([
                'success'  => true,
                'app_code' => 'FUIP_200',
                'message'  => $this->appCodes['FUIP_200'] ?? 'Cache des statistiques vidé avec succès.',
                'data'     => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'  => false,
                'app_code' => 'FUIP_500',
                'message'  => $this->appCodes['FUIP_500'] ?? 'Erreur lors du vidage du cache.',
                'errors'   => ['details' => $e->getMessage()],
            ], 500);
        }
    }
}
