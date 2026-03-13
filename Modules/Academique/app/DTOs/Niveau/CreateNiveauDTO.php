<?php

namespace Modules\Academique\DTOs\Niveau;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Niveau\StoreNiveauRequest;

final readonly class CreateNiveauDTO implements ArrayableDTO
{
    public function __construct(
        public string $filiereId,
        public string $parcoursId,
        public string $anneeAcademiqueId,
        public string $code,
        public string $libelle,
        public ?int $ordre,
        public ?int $creditsRequis,
        public ?int $effectifMax,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreNiveauRequest $request): self
    {
        return new self(
            filiereId: $request->validated('filiere_id'),
            parcoursId: $request->validated('parcours_id'),
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            ordre: $request->validated('ordre') !== null ? (int) $request->validated('ordre') : null,
            creditsRequis: $request->validated('credits_requis') !== null ? (int) $request->validated('credits_requis') : null,
            effectifMax: $request->validated('effectif_max') !== null ? (int) $request->validated('effectif_max') : null,
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'filiere_id' => $this->filiereId,
            'parcours_id' => $this->parcoursId,
            'annee_academique_id' => $this->anneeAcademiqueId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'ordre' => $this->ordre,
            'credits_requis' => $this->creditsRequis,
            'effectif_max' => $this->effectifMax,
            'is_active' => $this->isActive,
        ];
    }
}
