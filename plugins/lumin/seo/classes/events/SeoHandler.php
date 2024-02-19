<?php namespace Lumin\Seo\Classes\Events;

use October\Rain\Events\Dispatcher;

class SeoHandler
{
    /**
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function subscribe(Dispatcher $dispatcher) : void
    {
        $dispatcher->listen('cms.pageLookup.listTypes', self::class . '@listTypes');
        $dispatcher->listen('cms.pageLookup.getTypeInfo', self::class . '@getTypeInfo');
        $dispatcher->listen('cms.pageLookup.resolveItem', self::class . '@resolveItem');
    }

    /**
     * @return array
     */
    public function listTypes() : array
    {
        return [
            'category'       => 'Категория',
            'all-categories' => ['Все категории', true],
        ];
    }

    /**
     * @param string $type
     * @return array|bool
     */
    public function getTypeInfo(string $type): array|bool
    {
        return match ($type) {
//            'category' => [
//                'references' => Category::query()->tap(new ActiveScope())->pluck('name', 'id'),
//                'cmsPages'   => Page::withComponent('CategoryPage')->all()
//            ],
            default => true
        };
    }

    /**
     * @param $type
     * @param $item
     * @param $url
     * @param $theme
     * @return ?array
     */
    public function resolveItem($type, $item, $url, $theme) : array|null
    {
        $controller = new Controller($theme);

//        if ($type == 'all-categories') {
//            $items = Category::query()
//                ->get()
//                ->map(function ($category) {
//                    return [
//                        'title' => $category->name,
//                        'url'   => $category->url,
//                    ];
//                })->toArray();
//            return [
//                'items' => $items,
//            ];
//        }

        $model = match ($type) {
            //'category' => Category::find($item->reference),
            default => null
        };

        if (!$model) {
            return null;
        }

        $pageUrl = $controller->pageUrl($item->cmsPage, [
            'id'   => $model->id,
            'slug' => $model->slug
        ]);

        return [
            'url'      => $pageUrl,
            'isActive' => $pageUrl == $url,
            'title'    => $model->title,
            'mtime'    => $model->updated_at,
        ];
    }
}

