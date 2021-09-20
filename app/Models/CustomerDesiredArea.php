<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\CustomerDesiredArea as DesiredArea;

/**
 * Class CustomerDesiredArea
 * 
 * @property int $id
 * @property int $customers_id
 * @property int $cities_id
 * @property int|null $towns_id
 * @property bool|null $default
 * @property Carbon|null $created_at
 * 
 * @property Customer $customer
 * @property Town|null $town
 * @property City $city
 *
 * @package App\Models
 */
class CustomerDesiredArea extends Model
{
	protected $table = 'customer_desired_areas';
	public $timestamps = false;

    protected $appends = [ 'location' ];

	protected $casts = [
		'id' => 'int',
		'customers_id' => 'int',
		'cities_id' => 'int',		
		'default' => 'bool',
        'cities_area_id' => 'int'
	];

	protected $fillable = [
		'customers_id',
		'cities_id',		
		'default',
		'created_at',
        'cities_area_id',
	];

    // ----------------------------------------------------------------------
    // The cities areas relation
    // ----------------------------------------------------------------------
    public function city_areas()
	{
		return $this->belongsTo(CitiesAreas::class, 'cities_area_id');
	}

    // ----------------------------------------------------------------------
    // Customer to which this area belongs to
    // ----------------------------------------------------------------------
	public function customer(){
		return $this->belongsTo( Customer::class, 'customers_id' );
	}
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // The town relation
    // ----------------------------------------------------------------------
	public function town(){
		return $this->belongsTo( Town::class, 'towns_id' );
	}
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // The city relation
    // ----------------------------------------------------------------------
	public function city(){
		return $this->belongsTo( City::class, 'cities_id' );
	}
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get and compose the area location string (prefecture + city + town)
    // ----------------------------------------------------------------------
    public function getLocationAttribute(){
        if( empty( $this->town )) return null;
        // ------------------------------------------------------------------
        $town = $this->town; $city = $town->city;
        $prefecture = $city->prefecture;
        // ------------------------------------------------------------------
        return $prefecture->name.$city->name.$town->name;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get customer's default desired-area
    // ----------------------------------------------------------------------
    public static function getDefaultArea(){
        // ------------------------------------------------------------------
        $customer = Auth::guard('user')->user();
        if( empty( $customer )) return;
        // ------------------------------------------------------------------
        return DesiredArea::where( 'customers_id', $customer->id )
            ->where( 'default', true )->first();
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
