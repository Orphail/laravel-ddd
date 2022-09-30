<?php

namespace Src\Common\Presentation\CLI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateCommandCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:command {boundedContext} {domainName} {commandName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new command in the specified domain';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $boundedContext = $this->argument('boundedContext');
        $domainName = $this->argument('domainName');
        $commandName = $this->argument('commandName');

        $path = 'src/' . $boundedContext . '/' . $domainName . '/Application/UseCases/Commands/' . $commandName . '.php';

        $stub = File::get('./stubs/Command.stub');
        $stubReplace = [
            '**BoundedContext**' => $boundedContext,
            '**Domain**' => $domainName,
            '**CommandName**' => $commandName,
            '**domain_lc**' => Str::snake($domainName),
            '**action**' => Str::camel(preg_replace('/(' . $domainName . ')?Command/', '', $commandName)),
        ];

        $file = strtr($stub, $stubReplace);

        File::put($path, $file);
        return 1;
    }
}
