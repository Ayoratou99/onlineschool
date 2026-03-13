<?php

namespace Modules\Statistique\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Statistique\Services\EntityRegistry;

class StatistiqueQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $operations = implode(',', config('statistique.operations', [
            'count', 'count_distinct', 'sum', 'avg', 'min', 'max',
        ]));

        $intervals = implode(',', config('statistique.period_intervals', [
            'hour', 'day', 'week', 'month', 'year',
        ]));

        // Use EntityRegistry for auto-discovered entities
        $entitySlugs = implode(',', app(EntityRegistry::class)->slugs());

        return [
            'entity'          => "required|string|in:$entitySlugs",
            'target_column'   => 'required|string|max:100',
            'operation'       => "required|string|in:$operations",

            // Periode
            'with_period'     => 'sometimes|boolean',
            'period'          => 'required_if:with_period,true|array',
            'period.start'    => 'required_with:period|date',
            'period.end'      => 'required_with:period|date|after_or_equal:period.start',
            'period_column'   => 'sometimes|string|max:100',
            'period_group_by' => "sometimes|string|in:$intervals",

            // Filtres & Groupements
            'filters'         => 'sometimes|array',
            'filters.*'       => 'required',
            'group_by'        => 'sometimes|array',
            'group_by.*'      => 'required|string|max:100',

            // Cache control
            'no_cache'        => 'sometimes|boolean',
            'cache_ttl'       => 'sometimes|integer|min:1|max:86400',
        ];
    }

    public function messages(): array
    {
        $slugs = implode(', ', app(EntityRegistry::class)->slugs());

        return [
            'entity.required'            => "Le champ 'entity' est obligatoire.",
            'entity.in'                  => "L'entite specifiee n'existe pas. Entites disponibles : $slugs",
            'target_column.required'     => "Le champ 'target_column' est obligatoire.",
            'operation.required'         => "Le champ 'operation' est obligatoire.",
            'operation.in'               => "Operation non supportee. Valeurs acceptees : " . implode(', ', config('statistique.operations', [])),
            'period.required_if'         => "Le champ 'period' est requis quand 'with_period' est true.",
            'period.start.required_with' => "La date de debut (period.start) est requise.",
            'period.start.date'          => "La date de debut (period.start) doit etre une date valide.",
            'period.end.required_with'   => "La date de fin (period.end) est requise.",
            'period.end.date'            => "La date de fin (period.end) doit etre une date valide.",
            'period.end.after_or_equal'  => "La date de fin doit etre posterieure ou egale a la date de debut.",
            'period_group_by.in'         => "Intervalle de periode non supporte. Valeurs acceptees : " . implode(', ', config('statistique.period_intervals', [])),
            'cache_ttl.min'              => "Le TTL du cache doit etre d'au moins 1 seconde.",
            'cache_ttl.max'              => "Le TTL du cache ne peut pas depasser 86400 secondes (24h).",
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success'  => false,
            'app_code' => 'FUIP_422',
            'message'  => 'Erreur de validation des donnees statistiques.',
            'errors'   => $validator->errors()->toArray(),
        ], 422));
    }
}
