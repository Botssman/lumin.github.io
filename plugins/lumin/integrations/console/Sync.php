<?php namespace Lumin\Integrations\Console;

use ApplicationException;
use Illuminate\Console\Command;
use Lumin\Integrations\Classes\Services\FreshDeskService;
use Lumin\Integrations\Classes\Services\LeverService;
use Lumin\Integrations\Models\Settings;

/**
 * Sync Command
 *
 * @link https://docs.octobercms.com/3.x/extend/console-commands.html
 */
class Sync extends Command
{
    /**
     * @var string signature for the console command.
     */
    protected $signature = 'integrations:sync {service}';

    /**
     * @var string description is the console command description
     */
    protected $description = 'No description provided yet...';

    /**
     * handle executes the console command.
     * @throws ApplicationException
     */
    public function handle(): void
    {
        $service = match($this->argument('service')){
            'freshdesk' => new FreshDeskService(
                Settings::get('freshdesk_articles_api_url')
            ),
            'lever' => new LeverService(
                Settings::get('lever_api_url')
            ),
            'default' => throw new ApplicationException('Wrong service!')
        };

        $count = count($service->entries);

        $this->output->info("Sync started. Total number of articles: $count");

        $bar = $this->output->createProgressBar($count);

        foreach ($service->entries as $entry) {
            try {
                $service->processEntry($entry);
            } catch (\Exception $exception) {
                $this->output->error($exception->getMessage());
            }
            $bar->advance();
        }

        $this->output->info("Sync ended");
    }
}
