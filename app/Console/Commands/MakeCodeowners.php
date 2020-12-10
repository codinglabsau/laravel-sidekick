<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\WritesFiles;

class MakeCodeowners extends Command
{
    use WritesFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:codeowners {--t|team=reviewers} {--o|organisation=codinglabsau} {--u|user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CODEOWNERS file.';

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
        //Begin creating file
        $file_contents = '* @';

        //If single user specified
        if ($this->option('user')) {
            $file_contents .= $this->option('user');
        } else {
            $file_contents .= $this->option('organisation') . '/' . $this->option('team');
        }

        //Save file
        $this->prepareAndWriteFile('CODEOWNERS', $file_contents, 'root', '.github/', true);

        //Return successful for command.
        return 0;
    }
}
