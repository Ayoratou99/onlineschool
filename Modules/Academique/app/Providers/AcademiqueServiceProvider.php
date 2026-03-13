<?php

namespace Modules\Academique\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Academique\Models\Batiment;
use Modules\Academique\Models\Cycle;
use Modules\Academique\Models\Domaine;
use Modules\Academique\Models\Etablissement;
use Modules\Academique\Models\EmploiDuTemps;
use Modules\Academique\Models\EmploiDuTempsException;
use Modules\Academique\Models\Etage;
use Modules\Academique\Models\Filiere;
use Modules\Academique\Models\Groupe;
use Modules\Academique\Models\Matiere;
use Modules\Academique\Models\MatiereEnseignant;
use Modules\Academique\Models\Niveau;
use Modules\Academique\Models\Parcours;
use Modules\Academique\Models\Programme;
use Modules\Academique\Models\ProgrammeDetail;
use Modules\Academique\Models\Salle;
use Modules\Academique\Models\SalleIndisponibilite;
use Modules\Academique\Models\Semestre;
use Modules\Academique\Models\UniteEnseignement;
use Modules\Academique\Policies\BatimentPolicy;
use Modules\Academique\Policies\CyclePolicy;
use Modules\Academique\Policies\DomainePolicy;
use Modules\Academique\Policies\EtablissementPolicy;
use Modules\Academique\Policies\EmploiDuTempsExceptionPolicy;
use Modules\Academique\Policies\EmploiDuTempsPolicy;
use Modules\Academique\Policies\EtagePolicy;
use Modules\Academique\Policies\FilierePolicy;
use Modules\Academique\Policies\GroupePolicy;
use Modules\Academique\Policies\MatiereEnseignantPolicy;
use Modules\Academique\Policies\MatierePolicy;
use Modules\Academique\Policies\NiveauPolicy;
use Modules\Academique\Policies\ParcoursPolicy;
use Modules\Academique\Policies\ProgrammeDetailPolicy;
use Modules\Academique\Policies\ProgrammePolicy;
use Modules\Academique\Policies\SalleIndisponibilitePolicy;
use Modules\Academique\Policies\SallePolicy;
use Modules\Academique\Policies\SemestrePolicy;
use Modules\Academique\Policies\UniteEnseignementPolicy;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AcademiqueServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Academique';

    protected string $nameLower = 'academique';

    public function boot(): void
    {
        Gate::policy(Cycle::class, CyclePolicy::class);
        Gate::policy(Domaine::class, DomainePolicy::class);
        Gate::policy(Etablissement::class, EtablissementPolicy::class);
        Gate::policy(Filiere::class, FilierePolicy::class);
        Gate::policy(Parcours::class, ParcoursPolicy::class);
        Gate::policy(Niveau::class, NiveauPolicy::class);
        Gate::policy(Groupe::class, GroupePolicy::class);
        Gate::policy(Semestre::class, SemestrePolicy::class);
        Gate::policy(UniteEnseignement::class, UniteEnseignementPolicy::class);
        Gate::policy(Matiere::class, MatierePolicy::class);
        Gate::policy(Programme::class, ProgrammePolicy::class);
        Gate::policy(ProgrammeDetail::class, ProgrammeDetailPolicy::class);
        Gate::policy(MatiereEnseignant::class, MatiereEnseignantPolicy::class);
        Gate::policy(Batiment::class, BatimentPolicy::class);
        Gate::policy(Etage::class, EtagePolicy::class);
        Gate::policy(Salle::class, SallePolicy::class);
        Gate::policy(SalleIndisponibilite::class, SalleIndisponibilitePolicy::class);
        Gate::policy(EmploiDuTemps::class, EmploiDuTempsPolicy::class);
        Gate::policy(EmploiDuTempsException::class, EmploiDuTempsExceptionPolicy::class);
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(
            \App\Contracts\NiveauResolverInterface::class,
            \Modules\Academique\Services\NiveauResolver::class
        );
        $this->app->bind(
            \App\Contracts\SemestreResolverInterface::class,
            \Modules\Academique\Services\SemestreResolver::class
        );
        $this->app->bind(
            \App\Contracts\UeResolverInterface::class,
            \Modules\Academique\Services\UeResolver::class
        );
    }

    protected function registerCommands(): void
    {
    }

    protected function registerCommandSchedules(): void
    {
    }

    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);
        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
        }
    }

    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));
        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $this->nameLower.'.'.$config);
                    $existing = config($key, []);
                    config([$key => array_replace_recursive($existing, require $file->getPathname())]);
                }
            }
        }
    }

    public function registerViews(): void
    {
        $sourcePath = module_path($this->name, 'resources/views');
        if (is_dir($sourcePath)) {
            $this->loadViewsFrom($sourcePath, $this->nameLower);
        }
    }

    public function provides(): array
    {
        return [];
    }
}
