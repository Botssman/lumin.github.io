<?php namespace Lumin\Integrations\Console;

use Illuminate\Console\Command;
use Lumin\Integrations\Classes\Services\FreshDeskService;
use SystemException;

/**
 * Freshdesk Command
 *
 * @link https://docs.octobercms.com/3.x/extend/console-commands.html
 */
class Freshdesk extends Command
{
    /**
     * @var string signature for the console command.
     */
    protected $signature = 'sync:freshdesk';

    /**
     * @var string description is the console command description
     */
    protected $description = 'Синхронизирует посты Freshdesk';

    /**
     * handle executes the console command.
     * @throws SystemException
     */
    public function handle()
    {
        $freshDesk = new FreshDeskService();

        $count = count($freshDesk->articles);

        $this->output->info("Sync started. Total number of articles: $count");

        $bar = $this->output->createProgressBar($count);

        foreach ($freshDesk->articles as $article) {
            try {
                $freshDesk->processArticle($article);
            } catch (\Exception $exception) {
                $this->output->error($exception->getMessage());
            }
            $bar->advance();
        }

        $this->output->info("Sync ended");

    }
}
