<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PropertyStatus
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $label
 *
 * @property Collection|Property[] $properties
 *
 * @package App\Models
 */
class PropertyStatus extends Model
{

    const APPROVAL_PENDING = 1; //承認待ち
	const PUBLISHED = 2;  // 掲載
	const PULICATION_LIMITED = 3; // 掲載(限定)
	const NOT_POSTED = 4; // 非掲載
	const FINISH_PUBLISH = 5; // 掲載終了

	protected $table = 'property_statuses';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'name',
		'label'
	];

	public function properties()
	{
		return $this->hasMany(Property::class, 'property_statuses_id');
	}
}
