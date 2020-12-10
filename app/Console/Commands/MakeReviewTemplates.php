<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeReviewTemplates extends Command
{
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
     * Save a file at the specified location, prompting the user for confirmation in the console if it already exists.
     *
     * @param  string  $file_name       The name of the file.
     * @param  string  $file_contents   The contents of the file.
     * @param  string  $directory       The subdirectory in which to save the file.
     * @param  string  $disk            The disk to use for the Storage facade.
     *
     * @return void
     */
    public function saveFile($file_name, $file_contents, $disk = 'local', $directory = '', $print_output = false)
    {
        //Make directory
        Storage::disk('root')->makeDirectory($directory);

        //If doesn't exist
        if (Storage::disk('root')->missing($directory . $file_name)) {

            //Write file
            Storage::disk('root')->put($directory . $file_name, $file_contents);

            //Confirmation text and print file
            // return "A $file_name file was created at $directory$file_name.\n $file_contents";
            $this->info("A $file_name file was created at $directory$file_name.");
            if ($print_output) {
                $this->line($file_contents);
            }

            //If exists and has go-ahead confirmation
        } elseif ((Storage::disk('root')->exists($directory . $file_name) && $this->confirm("A $file_name file already exists. Would you like to overwrite it?"))) {

            //Delete existing file
            Storage::disk('root')->delete($file_name);

            //Write new file
            Storage::disk('root')->put($directory . $file_name, $file_contents);

            //Confirmation text and print file
            // return "The $file_name file was overwritten at $directory$file_name.\n $file_contents";
            $this->info("The $file_name file was overwritten at $directory$file_name.");
            if ($print_output) {
                $this->line($file_contents);
            }

            //If user rejects confirmation
        } else {
            // return "The $file_name file was not overwritten.".;
            $this->info("The $file_name file was not overwritten.");
        }
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

        $this->saveFile('pull_request_template.md', $file_contents, 'root', '.github/');

        //Return successful for command.
        return 0;
    }
}
