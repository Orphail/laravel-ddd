<?php

namespace Src\Common\Presentation\CLI;

use Illuminate\Console\Command as Cmd;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateQueryCmd extends Cmd
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:query {boundedContext} {domainName} {queryName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new query in the specified domain';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $boundedContext = $this->argument('boundedContext');
        $domainName = $this->argument('domainName');
        $queryName = $this->argument('queryName');

        $path = 'src/' . $boundedContext . '/' . $domainName . '/Application/UseCases/Queries/' . $queryName . '.php';

        $stub = File::get('./stubs/Query.stub');
        $stubReplace = [
            '**BoundedContext**' => $boundedContext,
            '**Domain**' => $domainName,
            '**QueryName**' => $queryName,
            '**domain_var**' => '$' . Str::snake($domainName),
            '**action**' => Str::camel(preg_replace('/(' . $domainName . ')?Query/', '', $queryName)),
        ];

        $file = strtr($stub, $stubReplace);

        File::put($path, $file);
        return 1;
    }
}
