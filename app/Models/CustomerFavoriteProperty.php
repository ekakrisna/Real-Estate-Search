<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerFavoriteProperty
 * 
 * @property int $id
 * @property int|null $customers_id
 * @property int|null $properties_id
 * @property Carbon|null $created_at
 * 
 * @property Customer|null $customer
 * @property Property|null $property
 *
 * @package App\Models
 */
class CustomerFavoriteProperty extends Model
{
	use \Awobaz\Compoships\Compoships;
	
	protected $table = 'customer_favorite_properties';
	public $timestamps = false;
	protected $appends = [ 'ja','url' ];

	protected $casts = [
		'id' => 'int',
		'customers_id' => 'int',
		'properties_id' => 'int'
	];

	protected $fillable = [		
		'customers_id',
		'created_at',
		'properties_id'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customers_id');
	}

	public function property()
	{
		return $this->belongsTo(Property::class, 'properties_id');
	}	

	// ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute(){
        $result = new \stdClass; $format = "Y年m月d日 H:i";
        if( !empty( $this->created_at )) $result->created_at = Carbon::parse( $this->created_at )->format( $format );        
        return $result;
    }
	// ----------------------------------------------------------------------
	
	public function getUrlAttribute(){
		if( empty( $this->id )) return null;
		if( empty( $this->properties_id )) return null;
        $url = new \stdClass;
        $url->edit = route('admin.customer.edit', $this->customers_id);
		$url->view = '#';	
		$url->change_flag = route('admin.changeCustomerFlag', $this->id);		
		$url->detail = route('admin.contact.detail', $this->id);
        return $url;
    }
}
