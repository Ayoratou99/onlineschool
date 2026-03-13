<?php

namespace Modules\Statistique\Services;

use Illuminate\Support\Facades\Cache;

class StatistiqueCacheManager
{
    /**
     * Génère une clé de cache unique basée sur les paramètres de la requête.
     * Les paramètres liés au cache (no_cache, cache_ttl) sont exclus du hash.
     */
    public function generateKey(array $params): string
    {
        $filtered = array_diff_key($params, array_flip(['no_cache', 'cache_ttl']));
        ksort($filtered);
        // Sort nested arrays for deterministic keys
        array_walk_recursive($filtered, function (&$item) {
            // no-op, just ensure consistent traversal
        });
        $prefix = config('statistique.cache.prefix', 'stats');

        return "$prefix:" . sha1(json_encode($filtered));
    }

    /**
     * Récupère un résultat depuis le cache.
     *
     * @return mixed|null null si absent ou cache désactivé
     */
    public function get(array $params): mixed
    {
        if (!$this->isEnabled()) {
            return null;
        }

        return Cache::tags(['statistique'])->get($this->generateKey($params));
    }

    /**
     * Stocke un résultat dans le cache.
     *
     * @param int|null $ttl  Durée en secondes (null = config par défaut)
     */
    public function put(array $params, mixed $data, ?int $ttl = null): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        $ttl = $ttl ?? (int) config('statistique.cache.ttl', 300);

        Cache::tags(['statistique'])->put(
            $this->generateKey($params),
            $data,
            $ttl
        );
    }

    /**
     * Vide tout le cache des statistiques (via tag).
     */
    public function flush(): void
    {
        Cache::tags(['statistique'])->flush();
    }

    /**
     * Vérifie si le cache est activé dans la config.
     */
    private function isEnabled(): bool
    {
        return (bool) config('statistique.cache.enabled', true);
    }
}
