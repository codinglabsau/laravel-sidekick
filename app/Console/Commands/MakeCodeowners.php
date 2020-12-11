<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\WritesFiles;

class MakeCodeowners extends Command
{
    use WritesFiles;

    protected $signature = 'make:codeowners {--t|team=reviewers} {--o|organisation=codinglabsau} {--u|user=}';
    protected $description = 'Create a new CODEOWNERS file.';

    public function handle(): int
    {
        $file_contents = '* @';

        if ($this->option('user')) {
            $file_contents .= $this->option('user');
        } else {
            $file_contents .= $this->option('organisation') . '/' . $this->option('team');
        }

        $this->prepareAndWriteFile('CODEOWNERS', $file_contents, 'root', '.github/', true);

        return 0;
    }
}
