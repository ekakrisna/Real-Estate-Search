<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpPropertyConvertStatus extends Model
{
    protected $table = 'lp_property_convert_status';
    public $timestamps = true;
    public $incrementing = false;

    const SUCCESSFUL = 0;
    const WRONG_LOCATION = 100;
    const WRONG_PRICE = 200;
    const WRONG_LAND_AREA = 300;
    const ALRADY_UPDATE = 999;

    const JPN_ID = [
        self::SUCCESSFUL => 0,
        self::WRONG_LOCATION => 100,
        self::WRONG_PRICE => 200,
        self::WRONG_LAND_AREA => 300,
        self::ALRADY_UPDATE => 999,
    ];
    
    protected $fillable = [
        'label',
    ];

	protected $casts = [
		'id' => 'int'
	];
}
