<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\Traits\WritesFiles;

class MakeReviewTemplates extends Command
{
    use WritesFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:review-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new PR description review template.';

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
        $file_contents = Storage::disk('root')->get('stubs/pull_request_template.md');

        $this->prepareAndWriteFile('pull_request_template.md', $file_contents, 'root', '.github/');

        //Return successful for command.
        return 0;
    }
}
