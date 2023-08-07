<?php namespace Solis\Basecode\Components;

use Cms\Classes\ComponentBase;
use Hash;
use Solis\BaseCode\Models\PostViews as View;

/**
 * PostViews Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class PostViews extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'PostViews'
        ];
    }

    public function onRun()
    {
        $this->addView();
        $this->page['postViews'] = View::where('post_id', $this->getPost())->count();
    }

    public function addView()
    {
        $view_hash = hash_hmac('sha1', $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ??
            strval(strtotime(date('Y-m-d') . ' 00:00:00')));

        $view = View::where('post_id', $this->getPost())->where('hash', $view_hash)->get();

        if($view->isEmpty()) {
            $newView = new View();
            $newView->hash = $view_hash;
            $newView->post_id = $this->getPost();
            $newView->save();
        }
    }

    public function getPost()
    {
        $post_id = $this->page->post->id;
        return $post_id;
    }
}
