<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralMaster extends Model
{
    protected $table = 'general_masters';
	public $timestamps = true;

	protected $fillable = [
        'key_name',
		'value',
	];
}
