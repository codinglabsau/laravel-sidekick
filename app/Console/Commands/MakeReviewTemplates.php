<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\Traits\WritesFiles;

class MakeReviewTemplates extends Command
{
    use WritesFiles;

    protected $signature = 'make:review-templates';
    protected $description = 'Create a new PR description review template.';

    public function handle(): int
    {
        $file_contents = Storage::disk('root')->get('stubs/pull_request_template.md');

        $this->prepareAndWriteFile('pull_request_template.md', $file_contents, 'root', '.github/');

        return 0;
    }
}
