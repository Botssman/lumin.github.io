<?php

namespace Lumin\Integrations\Classes\Services;

use ApplicationException;
use Http;
use Lumin\Integrations\Classes\Contracts\Integratable;
use Lumin\Integrations\Models\Settings;
use SystemException;
use Tailor\Models\EntryRecord;

class FreshDeskService extends IntegrationService implements Integratable
{

    /**
     * @throws SystemException
     */
    public function processEntry(array $data): mixed
    {
        $handle = Settings::get('freshdesk_articles_handle', 'Blog\FreshdeskPost');

        $entry = EntryRecord::inSection($handle)
            ->where('external_id', $data['id'])
            ->firstOr(fn() => EntryRecord::inSection($handle));

        // Skip if there's no changes
        if ($entry->updated_at >= $data['updated_at']) return null;

        $entry->title = $data['title'];
        $entry->slug = str_slug($data['title']);
        $entry->text = $data['description'];
        $entry->external_id = $data['id'];

        $category = $this->processCategory([
            'title' => $data['category_name'],
            'slug' => str_slug($data['category_name']),
            'external_id' => $data['category_id']
        ]);

        $entry->category_id = $category;

        $entry->save();

        return $entry;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws SystemException
     */
    protected function processCategory(array $data): mixed
    {
        $handle = Settings::get('freshdesk_categories_handle', 'Blog\Category');

        $category = EntryRecord::inSection($handle)
            ->where('external_id', $data['external_id'])
            ->firstOr(fn() => EntryRecord::inSection($handle));

        $category->title = $data['title'];
        $category->slug = str_slug($data['title']);
        $category->external_id = $data['external_id'];
        $category->save();

        return $category;
    }


}
