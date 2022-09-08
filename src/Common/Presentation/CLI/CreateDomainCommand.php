<?php

namespace Src\Common\Presentation\CLI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDomainCommand extends Command
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
        $basePath = 'src/' . $this->argument('boundedContext') . '/' . $this->argument('domainName');

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
            'Services'
        ];
        foreach ($domainStructure as $domainDirectory) {
            File::makeDirectory($basePath . '/Domain/' . $domainDirectory, 0755, true);
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
