<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerContactU
 * 
 * @property int $id
 * @property int|null $customers_id
 * @property int|null $properties_id
 * @property int|null $contact_us_types_id
 * @property string|null $subject
 * @property string|null $text
 * @property bool|null $flag
 * @property bool|null $is_finish
 * @property string|null $person_in_charge
 * @property string|null $note
 * @property string|null $name
 * @property string|null $email
 * @property string|null $company_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Customer|null $customer
 * @property ContactUsType|null $contact_us_type
 * @property Property|null $property
 *
 * @package App\Models
 */
class CustomerContactUs extends Model
{
	protected $table = 'customer_contact_us';
	public $timestamps = true;
	protected $appends = [ 'url', 'ja' ];

	protected $casts = [
		'id' => 'int',
		'customers_id' => 'int',
		'properties_id' => 'int',
		'contact_us_types_id' => 'int',
		'flag' => 'bool',
		'is_finish' => 'bool'
	];

	protected $fillable = [
		'customers_id',
		'properties_id',
		'contact_us_types_id',
		'subject',
		'text',
		'flag',
		'is_finish',
		'person_in_charge',
		'note',
		'name',
		'email',
		'company_name'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customers_id');
	}

	public function contact_us_type()
	{
		return $this->belongsTo(ContactUsType::class, 'contact_us_types_id');
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
        if( !empty( $this->updated_at )) $result->updated_at = Carbon::parse( $this->updated_at )->format( $format );
        return $result;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute(){
        if( empty( $this->id )) return null;
        $url = new \stdClass;
        $url->edit = '#';
		$url->view = '#';
		$url->detail = route('admin.contact.detail', $this->id);
		$url->change_flag = $this->customers_id !== NULL ? route('admin.changeCustomerFlag', $this->customers_id) : "#";
		$url->property_detail =  $this->properties_id !== NULL ? route('admin.property.detail', $this->properties_id) : "";
		$url->change_star = route('admin.customer_all_contact.flag', $this->id);
		$url->change_contact_flag = route('admin.changeCustomerContactUsFlag', $this->id);
        return $url;
    }
    // ----------------------------------------------------------------------
}
