<?php

namespace Src\Common\Presentation\CLI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDomainCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {boundedContext} {domainName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new domain directory structure';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $boundedContext = $this->argument('boundedContext');
        $domainName = $this->argument('domainName');
        $basePath = 'src/' . $boundedContext . '/' . $domainName;

        // Application
        $applicationStructure = [
            'DTO',
            'Repositories/Eloquent',
            'Exceptions',
            'Jobs',
            'Providers',
            'Mappers',
            'UseCases/Commands',
            'UseCases/Queries',
        ];
        foreach ($applicationStructure as $applicationDirectory) {
            File::makeDirectory($basePath . '/Application/' . $applicationDirectory, 0755, true);
            touch($basePath . '/Application/' . $applicationDirectory . '/.gitkeep');
        }

        // Domain
        $domainStructure = [
            'Exceptions',
            'Factories',
            'Model',
            'Policies',
            'Repositories',
            'ShipmentServices'
        ];
        foreach ($domainStructure as $domainDirectory) {
            $path = $basePath . '/Domain/' . $domainDirectory;
            File::makeDirectory($path, 0755, true);
            if ($domainDirectory === 'Model') {
                $stub = File::get('./stubs/DomainModel.stub');
                $stubReplace = [
                    '**BoundedContext**' => $boundedContext,
                    '**Domain**' => $domainName,
                ];
                $file = strtr($stub, $stubReplace);
                File::put($path . '/' . $domainName . '.php', $file);
                continue;
            }
            touch($basePath . '/Domain/' . $domainDirectory . '/.gitkeep');
        }

        // Infrastructure
        $infrastructureStructure = [
            'EloquentModels'
        ];
        foreach ($infrastructureStructure as $infrastructureDirectory) {
            File::makeDirectory($basePath . '/Infrastructure/' . $infrastructureDirectory, 0755, true);
            touch($basePath . '/Infrastructure/' . $infrastructureDirectory . '/.gitkeep');
        }

        // Presentation
        $presentationStructure = [
            'API',
            'CLI',
            'HTTP'
        ];
        foreach ($presentationStructure as $presentationDirectory) {
            File::makeDirectory($basePath . '/Presentation/' . $presentationDirectory, 0755, true);
            touch($basePath . '/Presentation/' . $presentationDirectory . '/.gitkeep');
        }

        return 1;
    }
}
