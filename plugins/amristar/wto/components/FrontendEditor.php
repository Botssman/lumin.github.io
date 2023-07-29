<?php 

namespace Amristar\WTO\Components;

use Cms\Classes\ComponentBase;
use System\Classes\MediaLibrary;
use BackendAuth;
use Config;
use Flash;
use Input;
use File;
use Log;
use Ini;
use Mail;
use Db;

class FrontendEditor extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Frontend Editor',
            'description' => 'Content Editor for WTO.'
        ];
    }

    public function defineProperties()
    {
        return [
        ];
    }

    public function onRun()
    {
        if( $this->checkEditor() ){
            
            $this->addCss('/plugins/amristar/wto/assets/css/frontedit.css');
            $this->addJs('/plugins/amristar/wto/assets/js/frontedit.js');
            
            $this->addCss('/plugins/amristar/wto/assets/css/content-tools.min.css');
            $this->addJs('/plugins/amristar/wto/assets/js/content-tools.min.js');

        }
    }

    public function onSaveImages()
    {
        $form = Input::all();

        if( !isset($form['image']) ) return;

        $page_path = $form['slug'];
        if( $page_path === "/" ) $page_path .= 'index';
        $page_path = themes_path().'/wto/content/static-pages'.$page_path.'.htm';

        $page_data = explode( '==', file_get_contents( $page_path ) );

        $page_attrs = Ini::parse( $page_data[0] );
        $page_attrs['viewBag'][$form['edit-id']] = '/'.$form['image']->getClientOriginalName();
        $page_data = Ini::render($page_attrs)."\n==";
        
        file_put_contents( $page_path, $page_data, LOCK_EX);
        
        MediaLibrary::instance()->put( $form['image']->getClientOriginalName(), File::get($form['image']->getRealPath()) );

        return [
            'src' => url('/storage/app/media').'/'.$form['image']->getClientOriginalName(),
            'edit-id' => $form['edit-id']
        ];
    }

    public function onSaveTexts()
    {
        $regions = Input::all();

        $page_path = $regions['wto_page_path'];
        unset($regions['wto_page_path']);

        if( $page_path === "/" ) $page_path .= 'index';
        $page_path = themes_path().'/wto/content/static-pages'.$page_path.'.htm';

        if( empty($regions) ) return;

        $page_data = explode( '==', file_get_contents( $page_path ) );

        $page_attrs = Ini::parse( $page_data[0] );

        foreach($regions as $key => $value){
            $page_attrs['viewBag'][$key] = $value;
        }

        $page_data = Ini::render($page_attrs)."\n==";

        file_put_contents( $page_path, $page_data, LOCK_EX);

        return [
        ];
    }

    public function onSendMail()
    {
        $fields = [];

        $mail_settings = Db::select("SELECT * FROM 'system_settings' WHERE item = 'system_mail_settings'");
        $email_to = json_decode($mail_settings[0]->value)->sender_email;

        foreach(Input::all() as $key => $value){
            if( $key === 'sendTo' ){
                $email_to = explode(',', $value);
            } else {
                $fields[] = ['name' => $key, 'value' => $value];
            }
        }
        
        Mail::sendTo($email_to, 'default', ['fields' => $fields]);

        return ['result' => 'success'];
    }

    public function checkEditor()
    {
        $backendUser = BackendAuth::getUser();
        return $backendUser;
    }

}