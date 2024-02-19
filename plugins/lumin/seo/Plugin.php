<?php namespace Lumin\Seo;

use Backend;
use Lumin\Seo\Classes\Events\SeoHandler;
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
            'name' => 'Seo',
            'description' => 'No description provided yet...',
            'author' => 'Lumin',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        \Event::subscribe(new SeoHandler);
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Lumin\Seo\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'lumin.seo.some_permission' => [
                'tab' => 'Seo',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'seo' => [
                'label' => 'Seo',
                'url' => Backend::url('lumin/seo/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['lumin.seo.*'],
                'order' => 500,
            ],
        ];
    }
}
