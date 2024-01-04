<?php

namespace Amristar\WTO;

use October\Rain\Filesystem\Zip;
use Tailor\Models\GlobalRecord;

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

    public function boot()
    {
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'update_theme') {

                $update_options = GlobalRecord::findForGlobal('theme_update');
                $status = $update_options->update_status;
                $secret = $update_options->update_password;

                $pass = get('pass');

                if (md5($secret) !== $pass || $status == 0) {
                    die('<script>alert(`No access!`);history.back();</script>');
                }

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
    }

    public function registerComponents()
    {
        return [
            '\Amristar\WTO\Components\Forms' => 'forms',
        ];
    }
}
