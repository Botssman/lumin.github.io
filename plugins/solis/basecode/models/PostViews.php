<?php namespace Solis\Basecode\Models;

use Model;

/**
 * PostViews Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class PostViews extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'solis_basecode_post_views';

    /**
     * @var array rules for validation
     */
    public $rules = [];
}
