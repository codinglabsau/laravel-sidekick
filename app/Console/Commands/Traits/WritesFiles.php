<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Facades\Storage;

trait WritesFiles
{
    public function prepareAndWriteFile(string $file_name, string $file_contents, string $disk = 'local', string $directory = '', bool $print_output = false)
    {
        Storage::disk($disk)->makeDirectory($directory);

        if (Storage::disk($disk)->missing($directory . $file_name)) {

            $confirmationOutput = "A $file_name file was created at {$directory}{$file_name}.";
            $this->writeFile($file_name, $file_contents, $disk, $directory, $print_output, $confirmationOutput);

        } elseif ((Storage::disk($disk)->exists($directory . $file_name) && $this->confirm("A $file_name file already exists. Would you like to overwrite it?"))) {

            Storage::disk($disk)->delete($file_name);

            $confirmationOutput = "The $file_name file was overwritten at {$directory}{$file_name}.";
            $this->writeFile($file_name, $file_contents, $disk, $directory, $print_output, $confirmationOutput);

        } else {
            $this->info("The $file_name file was not overwritten.");
        }
    }

    protected function writeFile(string $file_name, string $file_contents, string $disk, string $directory, bool $print_output, string $confirmationOutput)
    {
        Storage::disk($disk)->put($directory . $file_name, $file_contents);

        $this->info($confirmationOutput);
        if ($print_output) {
            $this->line($file_contents);
        }
    }
}
