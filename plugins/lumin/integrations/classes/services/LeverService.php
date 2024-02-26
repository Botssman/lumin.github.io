<?php

namespace Lumin\Integrations\Classes\Services;

use ApplicationException;
use Http;
use Lumin\Integrations\Classes\Contracts\Integratable;
use Lumin\Integrations\Models\Settings;
use SystemException;
use Tailor\Models\EntryRecord;

class LeverService extends IntegrationService implements Integratable
{
    public function processEntry(array $data) : mixed
    {
        $handle = Settings::get('lever_posts_handle', 'Blog\LeverPost');

        $entry = EntryRecord::inSection($handle)
            ->where('external_id', $data['id'])
            ->firstOr(fn() => EntryRecord::inSection($handle));

        $entry->title = $data['text'];
        $entry->department= $data['categories']['department'] ?? '';
        $entry->location= $data['categories']['location'] ?? '';
        $entry->url= $data['applyUrl'];
        $entry->is_enabled = 1;

        $entry->save();
        return $entry;
    }
}
