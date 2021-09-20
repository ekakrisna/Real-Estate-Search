<?php

namespace App\Models;

use App\Events\CustomerNews;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Console\Scraping\DataRow\Base\BaseScrapingRow;

use Illuminate\Support\Facades\Log;

class CustomerNew extends Model
{
    use Notifiable;

    protected $table = 'customer_news';
    public $timestamps = true;

    protected $fillable = [
        'customers_id',
        'type',
        'is_show',
        'location',
        'property_deliveries_id',
        'customer_news_lands_id',
        'is_send_email',
    ];

    const RECOMMENDED_PROPERTY = 1;
    const ADD_PROPERTY = 2;
    const PROPERTY_UPDATE = 3;
    const PROPERTY_END = 4;

    public function property_deliveries()
    {
        return $this->belongsTo(PropertyDelivery::class, 'property_deliveries_id');
    }

    public function customer_news_property()
    {
        return $this->hasMany(CustomerNewsProperty::class, 'customer_news_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    protected $appends = ['url', 'ja', 'notif'];

    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute()
    {
        $result = new \stdClass;
        $format = "Y-m-d";
        if (!empty($this->created_at)) $result->created_at = Carbon::parse($this->created_at)->format($format);
        if (!empty($this->updated_at)) $result->updated_at = Carbon::parse($this->updated_at)->format($format);
        return $result;
    }

    public function getFullAddressAttribute()
    {
        if (!empty($this->customer_news_lands)) {
            $property_name = $this->customer_news_lands->prefectures->name;
            $city_name = $this->customer_news_lands->city->name;
            $town_name = $this->customer_news_lands->town->name;
            return $property_name . $city_name . $town_name;
        } else {
            return null;
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // @TODO: Build URL based on route
    // ----------------------------------------------------------------------
    // ----------------------------------------------------------------------
    public function getUrlAttribute()
    {
        $url = new \stdClass;
        if (!empty($this->location)) $url->frontendDetailProperty = route('frontend.property.list.location',  $this->location);
        return $url;
    }

    // ----------------------------------------------------------------------
    // @TODO: Build URL based on route
    // ----------------------------------------------------------------------
    // ----------------------------------------------------------------------
    public function getNotifAttribute()
    {
        if (empty($this->customers_id)) return null;
        $notif = new \stdClass;
        $notif->countNotif = CustomerNew::where([['customers_id', $this->customers_id], ['is_show', 0]])->count();
        return $notif;
    }

    public static function storeNews($propertyOrm, $newsType, $customerIds = null)
    {
        try {
            $afterLoctation = BaseScrapingRow::convartLocation($propertyOrm->location);

            if ($afterLoctation['TOWN_ID'] != null) {
                $town = Town::find($afterLoctation['TOWN_ID']);
                $city = $town->city;

                // Get customer list
                $customerList = null;
                switch ($newsType) {
                    case CustomerNew::ADD_PROPERTY:
                        if ($customerIds != null) {
                            //  Narrow down by customers_id(s) if get $customerIds.
                            $customerList  = CustomerDesiredArea::
                                where(function ($query) use ($town) {
                                    // OK if match to id or it is NULL.  NG if set an OTHER id.
                                    $query
                                        ->where('cities_area_id', $town->city_areas->id)
                                        ->orWhereNull('cities_area_id');
                                })
                                ->where('cities_id', $city->id)
                                ->whereIn('customers_id', $customerIds)
                                ->get();
                        } else {
                            // Target all user if no customers_id(s).
                            $customerList  = CustomerDesiredArea::
                                where(function ($query) use ($town) {
                                    // OK if match to id or it is NULL.  NG if set an OTHER id.
                                    $query
                                        ->where('cities_area_id', $town->city_areas->id)
                                        ->orWhereNull('cities_area_id');
                                })
                                ->where('cities_id', $city->id)
                                ->get();
                        }
                        break;

                    case CustomerNew::PROPERTY_UPDATE:
                        $customerList  = CustomerFavoriteProperty::where('properties_id', $propertyOrm->id);
                        if ($customerIds != null) {
                            $customerList  = $customerList->whereIn('customers_id', $customerIds);
                        }
                        $customerList  = $customerList->get();
                        break;

                    case CustomerNew::PROPERTY_END:
                        $customerList  = CustomerFavoriteProperty::where('properties_id', $propertyOrm->id);
                        if ($customerIds != null) {
                            $customerList  = $customerList->whereIn('customers_id', $customerIds);
                        }
                        $customerList  = $customerList->get();
                        break;

                    default:
                        return;
                }
                /**
                 * Create CustomerNew records for each customer.
                 */
                foreach ($customerList as $customer) {
                    $location = $propertyOrm->location;
                    $id = $propertyOrm->id;

                    if ( $newsType == CustomerNew::PROPERTY_UPDATE || $newsType ==  CustomerNew::PROPERTY_END ) {
                        $customerNewsModel = CustomerNew::where('location', $location)
                            ->where('customers_id', $customer->customers_id)
                            ->where('type', $newsType)
                            ->whereDate('created_at', Carbon::today())
                            // TODO: why ??
                            ->whereHas('customer_news_property', function ($query) use ($id) {
                                $query->where('property_id', $id);
                            })->first();
                    } else {
                        $customerNewsModel = CustomerNew::where('location', $location)
                            ->where('customers_id', $customer->customers_id)
                            ->where('type', $newsType)
                            ->whereDate('created_at', Carbon::today())->first();
                    }

                    //
                    if ($customerNewsModel == null) {
                        $customerNewsModel = CustomerNew::create([
                            'customers_id' => $customer->customers_id,
                            'type' => $newsType,
                            'is_show' => 0,
                            'is_send_email' => 0,
                            'location' => $propertyOrm->location,
                        ]);
                    }
                    $customerNewsProperty = CustomerNewsProperty::updateOrCreate(
                        [
                            'customer_news_id' => $customerNewsModel->id,
                            'property_id' =>  $propertyOrm->id
                        ]
                    );

                    //------------------------------------------------------
                    // SAVE LOG INFO
                    //------------------------------------------------------
                    Log::info(
                        Carbon::now() . " - Created Customer News: ",
                        [   'id' => $customerNewsModel->id,
                            'customers_id' => $customer->customers_id,
                            'type' => $newsType,
                            'property_deliveries_id' => $propertyOrm->id
                        ]
                    );
                    //------------------------------------------------------
                }// End: foreach ($customerList as $customer)
            } // End: if ($afterLoctation['TOWN_ID'] != null)
        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport(
                "Failed Create Customer New",
                $e->getMessage()
            );
            //------------------------------------------------------
        }
    }
}
