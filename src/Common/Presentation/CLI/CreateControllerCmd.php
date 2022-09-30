<?php

namespace Src\Common\Presentation\CLI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateControllerCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:controller {boundedContext} {domainName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new controller in the specified domain';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $boundedContext = $this->argument('boundedContext');
        $domainName = $this->argument('domainName');

        $path = 'src/' . $boundedContext . '/' . $domainName . '/Presentation/HTTP/' . $domainName . 'Controller.php';

        $stub = File::get('./stubs/Controller.stub');
        $stubReplace = [
            '**BoundedContext**' => $boundedContext,
            '**Domain**' => $domainName,
            '**domain_lc**' => Str::snake($domainName),
        ];

        $file = strtr($stub, $stubReplace);

        File::put($path, $file);
        return 1;
    }
}
