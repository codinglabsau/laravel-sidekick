<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\Traits\WritesFiles;

class MakeCodingStyles extends Command
{
    use WritesFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:coding-styles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new .php_cs.dist file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Read stub
        $file_contents = Storage::disk('root')->get('stubs/.php_cs.dist');

        //Save file
        $this->prepareAndWriteFile('.php_cs.dist', $file_contents, 'root');

        $this->newLine();

        //Run `composer require codinglabsau/phpstyles`
        $this->info('Executing `composer require codinglabsau/phpstyles`');
        $composer_process = new Process(['composer', 'require', 'codinglabsau/php-styles']);
        $composer_process->run(function ($type, $buffer) {
            echo($buffer);
        });
        $this->newLine();
        $this->info('Execution of `composer require codinglabsau/phpstyles` successful.');
        $this->newLine();

        //Run `./vendor/bin/php-cs-fixer fix --dry-run`
        $this->info('Executing `./vendor/bin/php-cs-fixer fix --dry-run`');
        $dry_run_process = new Process(['./vendor/bin/php-cs-fixer', 'fix', '--dry-run']);
        $dry_run_process->run();
        $this->line($dry_run_process->getOutput());
        $this->info('Execution of `./vendor/bin/php-cs-fixer fix --dry-run` successful.');
        $this->newLine();

        //Run `./vendor/bin/php-cs-fixer fix`
        $this->info('Executing `./vendor/bin/php-cs-fixer fix`');
        $proper_run_process = new Process(['./vendor/bin/php-cs-fixer', 'fix']);
        $proper_run_process->run();
        $this->line($proper_run_process->getOutput());
        $this->info('Execution of `./vendor/bin/php-cs-fixer fix` successful.');
        $this->newLine();

        //Return successful for command.
        return 0;
    }
}
