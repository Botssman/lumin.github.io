<?php namespace Lumin\Integrations\Models;


/**
 * Settings Model
 *
 * @link https://docs.octobercms.com/3.x/extend/settings/model-settings.html#database-settings
 */
class Settings extends \System\Models\SettingModel
{

    public string $settingsCode = 'lumin_integrations_settings';

    public string $settingsFields = '$/lumin/integrations/models/settings/fields.yaml';
}
