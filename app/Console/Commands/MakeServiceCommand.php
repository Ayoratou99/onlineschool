<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--module= : The module name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class and implement interface if it exists';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $serviceName = $this->argument('name');
        $moduleName = $this->option('module');

        if (! $moduleName) {
            $this->error('Module name is required. Use --module=ModuleName');

            return self::FAILURE;
        }

        $modulePath = base_path("Modules/{$moduleName}");
        if (! File::exists($modulePath)) {
            $this->error("Module [{$moduleName}] does not exist!");

            return self::FAILURE;
        }

        $serviceName = Str::studly($serviceName);
        $serviceName = Str::endsWith($serviceName, 'Service') ? $serviceName : $serviceName.'Service';
        $interfaceName = Str::before($serviceName, 'Service').'Interface';

        $servicesPath = "{$modulePath}/app/Services";
        if (! File::exists($servicesPath)) {
            File::makeDirectory($servicesPath, 0755, true);
        }

        $interfacePath = "{$modulePath}/app/Interfaces/{$interfaceName}.php";
        $interfaceExists = File::exists($interfacePath);

        $servicePath = "{$servicesPath}/{$serviceName}.php";

        if (File::exists($servicePath)) {
            $this->error("Service [{$serviceName}] already exists!");

            return self::FAILURE;
        }

        $namespace = "Modules\\{$moduleName}\\Services";
        $interfaceNamespace = "Modules\\{$moduleName}\\Interfaces\\{$interfaceName}";

        $interfaceMethods = [];
        $requiredImports = [];

        if ($interfaceExists) {
            $interfaceMethods = $this->getInterfaceMethods($interfacePath);
            $requiredImports = $this->getRequiredImports($interfacePath, $moduleName, $interfaceMethods);
        }

        $stub = $this->getStub($interfaceExists, $namespace, $interfaceNamespace, $serviceName, $interfaceName, $interfaceMethods, $requiredImports);

        File::put($servicePath, $stub);

        $this->info("Service [{$serviceName}] created successfully!");

        if ($interfaceExists) {
            $this->info("Service implements [{$interfaceName}] interface with ".count($interfaceMethods).' method(s).');
        }

        return self::SUCCESS;
    }

    /**
     * Get methods from interface file.
     */
    protected function getInterfaceMethods(string $interfacePath): array
    {
        $content = File::get($interfacePath);
        $methods = [];

        // Extract method signatures using regex
        // Pattern matches: public function methodName(type $param, ...): ReturnType;
        preg_match_all('/public\s+function\s+(\w+)\s*\(([^)]*)\)\s*:?\s*([^;{]*);/m', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $methodName = trim($match[1]);
            $parameters = trim($match[2] ?? '');
            $returnType = trim($match[3] ?? '');

            $methods[] = [
                'name' => $methodName,
                'parameters' => $parameters,
                'return_type' => $returnType,
            ];
        }

        return $methods;
    }

    /**
     * Get required imports from interface and methods.
     */
    protected function getRequiredImports(string $interfacePath, string $moduleName, array $interfaceMethods): array
    {
        $content = File::get($interfacePath);
        $imports = [];

        // Get imports from interface file
        preg_match_all('/^use\s+([^;]+);/m', $content, $matches);
        foreach ($matches[1] as $import) {
            $imports[] = trim($import);
        }

        // Check return types and parameters for common types
        $commonTypes = [
            'Collection' => 'Illuminate\\Support\\Collection',
            'User' => "Modules\\{$moduleName}\\Models\\User",
        ];

        foreach ($interfaceMethods as $method) {
            // Check return type
            $returnType = trim($method['return_type'] ?? '');
            if (isset($commonTypes[$returnType])) {
                $imports[] = $commonTypes[$returnType];
            }

            // Check parameters
            $parameters = $method['parameters'] ?? '';
            foreach ($commonTypes as $type => $namespace) {
                if (strpos($parameters, $type) !== false) {
                    $imports[] = $namespace;
                }
            }
        }

        return array_unique($imports);
    }

    /**
     * Get the stub file for the service.
     */
    protected function getStub(bool $hasInterface, string $namespace, string $interfaceNamespace, string $serviceName, string $interfaceName, array $interfaceMethods = [], array $requiredImports = []): string
    {
        $implements = $hasInterface ? "implements {$interfaceName}" : '';

        $useStatements = '';
        if ($hasInterface) {
            $useStatements .= "use {$interfaceNamespace};\n";
        }

        if (! empty($requiredImports)) {
            foreach ($requiredImports as $import) {
                $useStatements .= "use {$import};\n";
            }
        }

        if (! empty($useStatements)) {
            $useStatements .= "\n";
        }

        $methods = '';
        if ($hasInterface && ! empty($interfaceMethods)) {
            foreach ($interfaceMethods as $method) {
                $methodName = $method['name'];
                $parameters = $method['parameters'] ?? '';
                $returnType = ! empty($method['return_type']) ? ': '.$method['return_type'] : '';

                $methods .= <<<PHP

    /**
     * {$this->getMethodDescription($methodName)}.
     */
    public function {$methodName}({$parameters}){$returnType}
    {
        // TODO: Implement this method
    }

PHP;
            }
        }

        return <<<PHP
<?php

namespace {$namespace};

{$useStatements}class {$serviceName} {$implements}
{
    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        //
    }
{$methods}}

PHP;
    }

    /**
     * Get a description for the method based on its name.
     */
    protected function getMethodDescription(string $methodName): string
    {
        $descriptions = [
            'findById' => 'Find a record by ID',
            'findAll' => 'Get all records',
            'create' => 'Create a new record',
            'update' => 'Update an existing record',
            'delete' => 'Delete a record',
            'findByEmail' => 'Find a record by email',
            'suspend' => 'Suspend a record',
            'activate' => 'Activate a record',
        ];

        return $descriptions[$methodName] ?? 'Handle '.$methodName;
    }
}
