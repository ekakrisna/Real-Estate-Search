<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyConvertStatus extends Model
{
    protected $table = 'property_convert_status';
	public $timestamps = true;
    public $incrementing = false;

	const SUCCESSFUL = 0;
    const WRONG_LOCATION = 100;
    const WRONG_PRICE = 200;
    const WRONG_LAND_AREA = 300;
    const ALRADY_UPDATE = 999;
	const BACKUP_INVALID_DATA = 1000;

	protected $fillable = [
		'label',
	];

	protected $casts = [
		'id' => 'int'
	];
}
