<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpPropertyCotegory extends Model
{
    protected $table = 'lp_property_categories';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'display_name'
    ];
    
    public static function getCategoryIdFromName($cotegory_name)
    {
        $cotegory_id = null;
        switch($cotegory_name){
            case "新築戸建":
                $cotegory_id = 1;
            break;
            case "中古戸建":
                $cotegory_id = 2;
            break;
        }
        return $cotegory_id;
    }
}
