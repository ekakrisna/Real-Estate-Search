<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpPropertyScrapingType extends Model
{
    //lp_property_scraping_types
    const CREATE = 100;
    const UPDATE = 200;
    const DELETE = 300;

    const JPN_TEXT = [
        self::CREATE => "新規登録",
        self::UPDATE => "更新",
        self::DELETE => "掲載終了",
    ];

    protected $fillable = [
        'id',
        'label',
    ];

    protected $casts = [
        'id' => 'int',
    ];
}
