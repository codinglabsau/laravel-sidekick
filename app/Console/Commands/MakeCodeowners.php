<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\WritesFiles;

class MakeCodeowners extends Command
{
    use WritesFiles;

    protected $signature = 'make:codeowners {--o|organisation=} {--t|team=} {--u|user=}';
    protected $description = 'Create a new CODEOWNERS file.';

    public function handle(): int
    {
        $file_contents = '* @';

        $prefersOrganisation = null;
        $providedInput = null;

        $options = $this->options();
        $organisation = $this->option('organisation');
        $team = $this->option('team');
        $user = $this->option('user');

        if (!(isset($organisation)) && !(isset($team)) && !(isset($user))) {
            $providedInput = false;
            $this->error('Command executed without sufficient options.');
            $preference = $this->choice('Please select whether you would like to generate a CODEOWNERS file for an organisation and team or a single user', ['Organisation and Team', 'User'], 0);
            $prefersOrganisation = $preference == 'Organisation and Team';
        } else {
            $providedInput = true;
            $prefersOrganisation = (isset($organisation) || isset($team));
        }

        if ($prefersOrganisation) {
            if ($providedInput && (!(isset($organisation)) || !(isset($team)))) {
                $this->error('There is missing information in order to generate a CODEOWNERS file for your organisation and team.');
            }
            $organisation ??= $this->ask('Enter your organisation name');
            $team ??= $this->ask('Enter your team name');
            $file_contents .= $organisation . '/' . $team;
        } else {
            $user ??= $this->ask('Enter the name of the user');
            $file_contents .= $user;
        }

        $this->prepareAndWriteFile('CODEOWNERS', $file_contents, 'root', '.github/', true);

        return 0;
    }
}
