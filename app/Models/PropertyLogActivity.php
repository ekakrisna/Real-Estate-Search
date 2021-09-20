<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PropertyLogActivity
 * 
 * @property int $id
 * @property int|null $properties_id
 * @property string|null $before_update_text
 * @property string|null $after_update_text
 * @property Carbon|null $created_at
 * 
 * @property Property|null $property
 *
 * @package App\Models
 */
class PropertyLogActivity extends Model
{
	protected $table = 'property_log_activities';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'properties_id' => 'int'
	];

	protected $fillable = [
		'properties_id',
		'before_update_text',
		'after_update_text',
		'property_scraping_type_id'
	];

	public function property()
	{
		return $this->belongsTo(Property::class, 'properties_id');
	}

	public function property_scraping_type()
    {
        return $this->belongsTo(PropertyScrapingType::class,'property_scraping_type_id');
    }

	protected $appends = [ 'ja' ];

	// ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute(){
        $result = new \stdClass; $format = "Y年m月d日"; $format_time = "Y年m月d日 H:i";
        if( !empty( $this->created_at )) $result->created_at = Carbon::parse( $this->created_at )->format( $format );
        if( !empty( $this->created_at )) $result->created_time = Carbon::parse( $this->created_at )->format( $format_time );
        return $result;
    }

	public function setPropertiesIdAttribute($value)
    {
        $this->attributes['properties_id'] = $value;
    }

	public function setPropertyScrapingTypeIdAttribute($value)
    {
        $this->attributes['property_scraping_type_id'] = $value;
    }
    // ----------------------------------------------------------------------

	
}
