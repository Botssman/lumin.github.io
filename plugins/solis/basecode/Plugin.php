<?php namespace Solis\BaseCode;

use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function registerComponents()
    {
        return [
            \Solis\Basecode\Components\PostViews::class => 'PostViews',
        ];
    }
}
