<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\Traits\WritesFiles;

class MakeCodingStyles extends Command
{
    use WritesFiles;

    protected $signature = 'make:coding-styles';
    protected $description = 'Create a new .php_cs.dist file.';

    public function handle(): int
    {
        $file_contents = Storage::disk('root')->get('stubs/.php_cs.dist');

        $this->prepareAndWriteFile('.php_cs.dist', $file_contents, 'root');

        $this->newLine();

        $this->info('Executing `composer require codinglabsau/phpstyles`');
        $composer_process = new Process(['composer', 'require', 'codinglabsau/php-styles', '--dev']);
        $composer_process->run(function ($type, $buffer) {
            $this->output->write($buffer); //Write to console without new line.
        });
        $this->newLine();
        $this->info('Execution of `composer require codinglabsau/phpstyles` successful.');
        $this->newLine();

        $this->info('Executing `./vendor/bin/php-cs-fixer fix --dry-run`');
        $dry_run_process = new Process(['./vendor/bin/php-cs-fixer', 'fix', '--dry-run']);
        $dry_run_process->run();
        $this->line($dry_run_process->getOutput());
        $this->info('Execution of `./vendor/bin/php-cs-fixer fix --dry-run` successful.');
        $this->newLine();

        $this->info('Executing `./vendor/bin/php-cs-fixer fix`');
        $proper_run_process = new Process(['./vendor/bin/php-cs-fixer', 'fix']);
        $proper_run_process->run();
        $this->line($proper_run_process->getOutput());
        $this->info('Execution of `./vendor/bin/php-cs-fixer fix` successful.');
        $this->newLine();

        return 0;
    }
}
