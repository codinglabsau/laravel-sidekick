<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeCodeowners extends Command
{
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
            $this->info("The $file_name file was overwritten at $directory$file_name.");
            if ($print_output) {
                $this->line($file_contents);
            }

            //If user rejects confirmation
        } else {
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
        //Begin creating file
        $file_contents = '* @';

        //If single user specified
        if ($this->option('user')) {
            $file_contents .= $this->option('user');
        } else {
            $file_contents .= $this->option('organisation') . '/' . $this->option('team');
        }

        //Save file
        $this->saveFile('CODEOWNERS', $file_contents, 'root', '.github/', true);

        //Return successful for command.
        return 0;
    }
}
