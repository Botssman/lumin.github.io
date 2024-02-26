<?php namespace Lumin\Integrations\Console;

use Illuminate\Console\Command;

/**
 * Lever Command
 *
 * @link https://docs.octobercms.com/3.x/extend/console-commands.html
 */
class Lever extends Command
{
    /**
     * @var string signature for the console command.
     */
    protected $signature = 'integrations:lever {user}';

    /**
     * @var string description is the console command description
     */
    protected $description = 'No description provided yet...';

    /**
     * handle executes the console command.
     */
    public function handle()
    {
        $username = $this->argument('user');

        $this->output->writeln("Hello {$username}!");
    }
}
