<?php

namespace Amristar\WTO;

use October\Rain\Filesystem\Zip;

// use RainLab\Translate\Classes\Translator;

class Plugin extends \System\Classes\PluginBase

{
    public function pluginDetails()
    {
        return [
            'name' => 'WTO Plugin',
            'description' => 'WTO themes support.',
            'author' => 'AMRISTAR & Co.',
            'icon' => 'icon-leaf',
        ];
    }

    public function registerNavigation()
    {
        return [
            // 'options' => [
            //     'label' => 'Опции',
            //     'url' => '/backend/cms/themeoptions/update/' . Theme::getActiveTheme()->getDirName(),
            //     'icon' => 'icon-cogs',
            //     'permissions' => ['*'],
            //     'order' => 500,
            // ],
        ];
    }

    public function boot()
    {
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'update_theme') {
                $source_file = $_GET['source'];
                $output_file = base_path() . '/' . basename($source_file);
                $dest_file = @fopen($output_file, "w");
                $resource = curl_init();
                curl_setopt($resource, CURLOPT_URL, $source_file . '?ver=' . time());
                curl_setopt($resource, CURLOPT_FILE, $dest_file);
                curl_setopt($resource, CURLOPT_HEADER, 0);
                curl_exec($resource);
                curl_close($resource);
                fclose($dest_file);
                $zip = new Zip;
                $zip->open($output_file);
                $zip->extractTo(base_path() . '/');
                $zip->close();
                unlink($output_file);
                die('<script>history.back();</script>');
            }
        }

        if (isset($_GET['test'])) {
            // print_r(Translator::instance()->getLocale());
            die();
        }
    }

    public function registerComponents()
    {
        return [
            '\Amristar\WTO\Components\Forms' => 'forms',
        ];
    }
}
