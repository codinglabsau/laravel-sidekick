<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Facades\Storage;

trait WritesFiles
{
    /**
     * Prepares a file and writes it to a file at the specified location, prompting the user for confirmation in the console if it already exists.
     *
     * @param  string  $file_name               The name of the file.
     * @param  string  $file_contents           The contents of the file.
     * @param  string  $disk                    The disk to use for the Storage facade.
     * @param  string  $directory               The subdirectory in which to write the file.
     * @param  bool    $print_output            A boolean controlling whether to print the contents of the file to the console after writing.
     *
     * @return void
     */
    public function prepareAndWriteFile($file_name, $file_contents, $disk = 'local', $directory = '', $print_output = false)
    {
        //Make directory
        Storage::disk($disk)->makeDirectory($directory);

        //If doesn't exist
        if (Storage::disk($disk)->missing($directory . $file_name)) {
            $confirmationOutput = "A $file_name file was created at $directory$file_name.";
            $this->writeFile($file_name, $file_contents, $disk, $directory, $print_output, $confirmationOutput);

        //If exists and has go-ahead confirmation
        } elseif ((Storage::disk($disk)->exists($directory . $file_name) && $this->confirm("A $file_name file already exists. Would you like to overwrite it?"))) {

            //Delete existing file
            Storage::disk($disk)->delete($file_name);

            $confirmationOutput = "The $file_name file was overwritten at $directory$file_name.";
            $this->writeFile($file_name, $file_contents, $disk, $directory, $print_output, $confirmationOutput);

        //If user rejects confirmation
        } else {
            $this->info("The $file_name file was not overwritten.");
        }
    }

    /**
     * Write a file to the specified location, printing the contents of the file upon saving with a confirmation messsage, if specified.
     *
     * @param  string  $file_name               The name of the file.
     * @param  string  $file_contents           The contents of the file.
     * @param  string  $disk                    The disk to use for the Storage facade.
     * @param  string  $directory               The subdirectory in which to write the file.
     * @param  bool    $print_output            A boolean controlling whether to print the contents of the file to the console after writing.
     * @param  string  $confirmationOutput      The message to print a confirmation message to the console after writing.
     *
     * @return void
     */
    public function writeFile($file_name, $file_contents, $disk, $directory, $print_output, $confirmationOutput)
    {
        //Write file
        Storage::disk($disk)->put($directory . $file_name, $file_contents);

        //Confirmation text and print file
        $this->info($confirmationOutput);
        if ($print_output) {
            $this->line($file_contents);
        }
    }
}
