<?php

namespace Lumin\Integrations\Classes\Services;

use ApplicationException;
use Http;
use Lumin\Integrations\Models\Settings;
use SystemException;
use Tailor\Models\EntryRecord;

class FreshDeskService
{
    private string $articlesUrl;

    public array $articles;

    /**
     * @throws ApplicationException
     */
    public function __construct()
    {
        $this->articlesUrl = Settings::get('freshdesk_articles_api_url');

        if (empty($this->articlesUrl)) {
            throw new ApplicationException('Freshdesk articles API URL is not specified');
        }

        $this->articles = $this->get($this->articlesUrl);
    }


    /**
     * @throws SystemException
     */
    public function processArticle(array $data): void
    {
        $handle = Settings::get('freshdesk_articles_handle', 'Blog\FreshdeskPost');

        $article = EntryRecord::inSection($handle)
            ->where('external_id', $data['id'])
            ->firstOr(fn() => EntryRecord::inSection($handle));

        // Skip if there's no changes
        if ($article->updated_at >= $data['updated_at']) return;

        $article->title = $data['title'];
        $article->slug = str_slug($data['title']);
        $article->text = $data['description'];
        $article->external_id = $data['id'];

        $category = $this->processCategory([
            'title' => $data['category_name'],
            'slug' => str_slug($data['category_name']),
            'external_id' => $data['category_id']
        ]);

        $article->category_id = $category;

        $article->save();
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

    /**
     * @param string $url
     * @return array
     */
    private function get(string $url) : array
    {
        $articlesResponse = Http::get($url);
        return $articlesResponse->json();
    }


}
