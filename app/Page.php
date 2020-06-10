<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // fillable
    protected $fillable = ['page_title', 'page_content', 'page_slug'];

    // slug function
    public static function slug( Page $p ) {
    	return '/p-' . $p->page_slug;
    }
}
