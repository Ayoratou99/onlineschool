<?php

namespace Modules\Statistique\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class EntityRegistry
{
    /**
     * Cache interne (runtime) pour eviter de re-scanner par requete.
     */
    private ?array $resolved = null;


    public function all(): array
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $entities = [];

        // 1. Auto-discovery
        if (config('statistique.auto_discover', true)) {
            $entities = $this->discover();
        }

        // 2. Merge manual overrides (overrides win)
        $manual = config('statistique.entities', []);
        $entities = array_merge($entities, $manual);

        // 3. Exclude
        $excluded = config('statistique.excluded_entities', []);
        if (!empty($excluded)) {
            $entities = array_filter($entities, fn (string $class) => !in_array($class, $excluded, true));
        }

        // Sort alphabetically by slug
        ksort($entities);

        $this->resolved = $entities;

        return $this->resolved;
    }

    /**
     * Retourne la liste des slugs disponibles (pour messages d erreur / validation).
     *
     * @return string[]
     */
    public function slugs(): array
    {
        return array_keys($this->all());
    }

    /**
     * Resout un slug d entite en FQCN de modele.
     *
     * @throws \InvalidArgumentException si le slug n existe pas ou la classe n existe pas
     */
    public function resolve(string $slug): string
    {
        $entities = $this->all();

        if (!isset($entities[$slug])) {
            throw new \InvalidArgumentException(
                "Entite '$slug' non trouvee. Entites disponibles : " . implode(', ', array_keys($entities)) . "."
            );
        }

        $class = $entities[$slug];

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                "La classe modele '$class' configuree pour l'entite '$slug' n'existe pas."
            );
        }

        return $class;
    }

    /**
     * Scanne tous les modules pour trouver les modeles Eloquent.
     * Chaque modele est enregistre avec comme slug le nom de sa table.
     *
     * @return array<string, class-string<Model>>
     */
    private function discover(): array
    {
        $modulesPath = base_path('Modules');
        $entities = [];

        if (!is_dir($modulesPath)) {
            return $entities;
        }

        $directories = File::directories($modulesPath);

        foreach ($directories as $moduleDir) {
            $moduleName = basename($moduleDir);
            $modelsDir  = $moduleDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Models';

            if (!is_dir($modelsDir)) {
                continue;
            }

            $files = File::files($modelsDir);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $className = $file->getFilenameWithoutExtension();
                $fqcn = "Modules\\{$moduleName}\\Models\\{$className}";

                if (!class_exists($fqcn)) {
                    continue;
                }

                // Verifie que c'est bien un modele Eloquent (ou Authenticatable)
                if (!is_subclass_of($fqcn, Model::class)) {
                    continue;
                }

                // Slug = table name du modele (ex: User -> users, DeclarationPerteDocument -> declaration_perte_documents)
                /** @var Model $instance */
                $instance = new $fqcn;
                $slug = $instance->getTable();

                $entities[$slug] = $fqcn;
            }
        }

        return $entities;
    }
}
