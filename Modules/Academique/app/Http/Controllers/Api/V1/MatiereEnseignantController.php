<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\MatiereEnseignant\CreateMatiereEnseignantDTO;
use Modules\Academique\DTOs\MatiereEnseignant\UpdateMatiereEnseignantDTO;
use Modules\Academique\Http\Requests\MatiereEnseignant\StoreMatiereEnseignantRequest;
use Modules\Academique\Http\Requests\MatiereEnseignant\UpdateMatiereEnseignantRequest;
use Modules\Academique\Models\MatiereEnseignant;
use Modules\Academique\Services\MatiereEnseignantService;

class MatiereEnseignantController extends Controller
{
    public function __construct(MatiereEnseignantService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', MatiereEnseignant::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(MatiereEnseignant $matiere_enseignant, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $matiere_enseignant);
        return $this->getModel($matiere_enseignant, $request->validated());
    }

    public function store(StoreMatiereEnseignantRequest $request): JsonResponse
    {
        $dto = CreateMatiereEnseignantDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateMatiereEnseignantRequest $request, MatiereEnseignant $matiere_enseignant): JsonResponse
    {
        $dto = UpdateMatiereEnseignantDTO::fromRequest($request);
        return $this->updateFromDTO($matiere_enseignant, $dto);
    }

    public function destroy(MatiereEnseignant $matiere_enseignant): JsonResponse
    {
        $this->authorize('delete', $matiere_enseignant);
        return $this->deleteModel($matiere_enseignant);
    }
}
