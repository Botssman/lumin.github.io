<?php namespace Lumin\Integrations;

use Backend;
use Lumin\Integrations\Console\Freshdesk;
use Lumin\Integrations\Console\Lever;
use Lumin\Integrations\Models\Settings;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Integrations',
            'description' => 'No description provided yet...',
            'author' => 'Lumin',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register(): void
    {
        $this->registerConsoleCommand('lumin.freshdesk', Freshdesk::class);
        $this->registerConsoleCommand('lumin.lever', Lever::class);
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        //
    }


    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

    }


    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label' => 'Integrations',
                'description' => 'Integrations settings: Freshdesk, Lever etc.',
                'category' => 'Integrations',
                'icon' => 'icon-refresh',
                'class' => Settings::class
            ]
        ];
    }
}
