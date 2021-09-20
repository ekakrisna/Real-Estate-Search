<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stephenjude\DefaultModelSorting\Traits\DefaultOrderBy;

/**
 * Class PropertyPhoto
 * 
 * @property int $id
 * @property int $properties_id
 * @property int $file_id
 * 
 * @property Property $property
 * @property File $file
 *
 * @package App\Models
 */
class PropertyPhoto extends Model
{
    use DefaultOrderBy;
    protected static $orderByColumn = 'sort_number';
	protected static $orderByColumnDirection = 'asc';

	protected $table = 'property_photos';
	public $timestamps = false;

	protected $casts = [
		'properties_id' => 'int',
		'file_id' => 'int',
		'sort_number'=> 'int'
	];

	protected $fillable = [
		'properties_id',
		'file_id',
		'sort_number',
	];

    protected $with = [ 'file' ];

	public function property()
	{
		return $this->belongsTo(Property::class, 'properties_id');
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}
}
