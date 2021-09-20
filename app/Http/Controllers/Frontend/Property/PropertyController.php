<?php

namespace App\Http\Controllers\Frontend\Property;

use App\Http\Controllers\Controller;
use App\Models\BeforeLoginCustomerLoginLogActivities;
use App\Models\BeforeLoginCustomers;
use App\Models\Customer;
use App\Models\CustomerContactUs;
use App\Models\CustomerDesiredArea;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerLogActivity;
use App\Models\Property;
use App\Models\PropertyStatus;
use App\Models\Town;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

use App\Mail\CustomerContactUsEmail;
use App\Models\ActionType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'text'                 => 'required|string',
        ]);
    }

    public function index(Request $request)
    {
        $data = new \stdClass;
        $locationName = $request->location ?? '';
        $data->location = $locationName;
        $idCustomers = Auth::guard('user')->user()->id;
        $data->idCustomers  = $idCustomers;
        //$currentCustomer    = Customer::find($idCustomers);
        //$notleave           = $currentCustomer->not_leave_record;
        //if (!$notleave) {
        //$log['action_types_id'] = 4;
        //$log['customers_id']    = $idCustomers;
        //$log['ip']              = $request->ip();
        //$log['access_time']     = Carbon::now();
        //$savelog = new CustomerLogActivity();
        //$savelog->fill($log)->save();
        //}

        //---------------------------------------------------
        //Save customer log activity when Visiting Property Index
        //---------------------------------------------------
        CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_BROWSING, $idCustomers, $request->ip());
        //---------------------------------------------------

        $data->areas    = CustomerDesiredArea::with(['town', 'city.prefecture'])->where('customers_id', $idCustomers)->get();
        $data->customer = Customer::with('customer_desired_areas.town.city.prefecture')->find($idCustomers);
        $data->town     = Town::with('city.prefecture')->where('name', $locationName)->first();

        if ($locationName && $data->town->count() != 0) {
            $townName = $data->town->name;
            $property = Property::with(['property_photos.file', 'customer_favorite_properties'])->where('location', 'LIKE', '%' . $townName . '%')->get();
            $data->properties     = $property;
            $data->desired_area   = [
                'town'         => $data->town->name,
                'city'         => $data->town->city->name,
                'prefecture'   => $data->town->city->prefecture->name,
                'id'           => $data->town->id . "," . $data->town->city->id . "," . $data->town->city->prefecture->id,
            ];
            $data->togle_area = CustomerDesiredArea::where([
                ['cities_id', '=', $data->town->city->id],
                ['towns_id', '=', $data->town->city->prefecture->id],
            ])->get()->count();
        } else {
            $data->properties     = null;
            $data->desired_area   = null;
            $data->togle_area     = null;
        }
        $data->title = __('label.property_information');
        return view('frontend.property.index', (array) $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $response = new \stdClass;
        try {
            $idProperties = $id;
            $dataset = json_decode($request->dataset, true);
            $text = $dataset['text'];

            if (Auth::guard('user')->user()) {
                $id                 = Auth::guard('user')->user()->id;
                $currentCustomer    = Customer::find($id);
                //----------------------------------------------------
                //IF USER LOGGED IN
                //----------------------------------------------------

                //$log['action_types_id'] = 6;
                //$log['customers_id']    = $currentCustomer->id;
                //$log['properties_id']    = $idProperties;
                //$log['ip']              = $request->ip();
                //$log['access_time']     = Carbon::now();
                //$savelog = new CustomerLogActivity();
                //$savelog->fill($log)->save();

                //---------------------------------------------------
                //Save customer log activity when sending Email
                //---------------------------------------------------
                CustomerLogActivity::storeCustomerLog(ActionType::CONTACT_US, $id, $request->ip(), $idProperties);
                //---------------------------------------------------

                $data = $request->all();
                $data['customers_id'] = $id;
                $data['contact_us_types_id'] = 901;
                $data['name'] = $currentCustomer->name;
                $data['email'] = $currentCustomer->email;
                $data['properties_id'] = $idProperties;
                $data['flag'] = 0;
                $data['text'] = $text;
                $data['is_finish'] = 0;
                $this->validator($data)->validate();
                $new = new CustomerContactUs();
                $new->fill($data)->save();
                $new->status = 'success';
                //----------------------------------------------------
            } else {
                //----------------------------------------------------
                //IF USER "NOT" LOGGED IN
                //----------------------------------------------------
                $textemail = $text . " <br><br><br> " . $dataset['text_detail'];

                $data = $request->all();
                $data['customers_id'] = null;
                $data['contact_us_types_id'] = 901;
                $data['name'] = null;
                $data['email'] = $dataset['customer_email'];
                $data['properties_id'] = $idProperties;
                $data['flag'] = 0;
                $data['text'] = $textemail;
                $data['is_finish'] = 0;
                $this->validator($data)->validate();
                $new = new CustomerContactUs();
                $new->fill($data)->save();
                $new->status = 'success';
                //----------------------------------------------------

                //----------------------------------------------------
                //Send Email
                //----------------------------------------------------
                try {
                    Mail::send(['html' => 'mail.CustomerContactUs'], $data, function ($message) use ($data) {
                        $message
                            ->to('info@tochi-s.net')
                            ->bcc(explode(',', config('mail.property_contact_us_emails_bcc')))
                            ->subject('Customer Contact Us Form Property Detail');
                        $message->from($data['email'], $data['email']);
                    });
                } catch (\Exception $e) {

                    //------------------------------------------------------
                    //Send chat to chatwork if failing in function
                    //------------------------------------------------------
                    Log::info(Carbon::now() . " - Frontend/PropertyController (storesendemail Function), Error: " . $e->getMessage());
                    sendMessageOfErrorReport("Frontend/PropertyController (storesendemail Function), Error: ", $e);
                    //------------------------------------------------------

                    $response->status = 'failed';
                    $response->message = $e->getMessage();
                    return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
                }
                //----------------------------------------------------
            }

            return response()->json($new, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/PropertyController (store Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/PropertyController (store Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e->getMessage();

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function addFavorite($id, Request $request)
    {
        $idCustomers = Auth::guard('user')->user()->id;
        // If not_leave_record of customers is false, set action_types_id of customer_log_activities to 5 and register the history.
        //$currentCustomer    = Customer::find($idCustomers);
        //$notleave           = $currentCustomer->not_leave_record;
        //if (!$notleave) {
        //$log['action_types_id'] = 5;
        //$log['customers_id']    = $idCustomers;
        //$log['properties_id']   = $id;
        //$log['ip']              = $request->ip();
        //$log['access_time']     = Carbon::now();
        //$savelog = new CustomerLogActivity();
        //$savelog->fill($log)->save();
        //}

        //---------------------------------------------------
        //Save customer log activity when Add Property Favorites
        //---------------------------------------------------
        CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_FAVORITES, $idCustomers, $request->ip(), $id);
        //---------------------------------------------------

        $data                    = $request->all();
        $customerFavorite = CustomerFavoriteProperty::all()->last();
        $data['id']    = $customerFavorite->id + 1;
        $data['properties_id']   = $id;
        $data['created_at']   = Carbon::now()->toDateTimeString();
        $query = CustomerFavoriteProperty::where([
            ['customers_id', '=', $data['customers_id']],
            ['properties_id', '=', $data['properties_id']],
        ])->get()->count();
        if ($query == 0) {
            $new = new CustomerFavoriteProperty();
            if ($new->fill($data)->save()) {
                $idLast = $new->id;
                $data = CustomerFavoriteProperty::find($idLast);
                return array($data);
            }
        } else {
            $query = CustomerFavoriteProperty::where([
                ['customers_id', '=', $data['customers_id']],
                ['properties_id', '=', $data['properties_id']],
            ])->delete();
            if ($query) {
                return array();
            }
        }
    }

    public function addDesired(Request $request)
    {
        $idCustomers = Auth::guard('user')->user()->id;
        // If not_leave_record of customers is false, set action_types_id of customer_log_activities to 5 and register the history.
        //$currentCustomer    = Customer::find($idCustomers);
        //$notleave           = $currentCustomer->not_leave_record;
        //if (!$notleave) {
        foreach ($request['properties'] as $key => $value) {
            //$log['action_types_id'] = 5;
            //$log['customers_id']    = $idCustomers;
            //$log['properties_id']   = $value['id'];
            //$log['ip']              = $request->ip();
            //$log['access_time']     = Carbon::now();
            //$savelog = new CustomerLogActivity();
            //$savelog->fill($log)->save();

            //---------------------------------------------------
            //Save customer log activity when Add Desired Area
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_FAVORITES, $idCustomers, $request->ip(), $value['id']);
            //---------------------------------------------------
        }
        //}

        $data               = $request->all();
        $data['created_at'] = Carbon::now();
        $data['default']    = 1;
        $query = CustomerDesiredArea::where([
            ['customers_id', '=', $data['customers_id']],
            ['cities_id', '=', $data['cities_id']],
            ['towns_id', '=', $data['towns_id']],
        ])->get()->count();
        if ($query == 0) {
            $new = new CustomerDesiredArea();
            if ($new->fill($data)->save()) {
                $idLast = $new->id;
                $data = CustomerDesiredArea::find($idLast);
                return array($data);
            }
        } else {
            $query = CustomerDesiredArea::where([
                ['customers_id', '=', $data['customers_id']],
                ['cities_id', '=', $data['cities_id']],
                ['towns_id', '=', $data['towns_id']],
            ])->delete();
            if ($query) {
                return array();
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // ------------------------------------------------------------------
        // store data to customer log activities if customer not leave record is false
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $customer = Auth::guard('user')->user();

        if ($customer) {
            $customer_id = $customer->id;
            $property_id = $id;

            $property =  Property::with(['property_photos.file', 'property_flyers.file', 'customer_favorite_properties' => function ($q) use ($property_id, $customer_id) {
                $q->where([['properties_id', $property_id], ['customers_id', $customer_id]]);
            }, 'company.company_users'])->where('id', $property_id)->first();

            // Define variables for blade
            $data->idCustomers  = $customer_id;
            $data->idProperties = $id;
            $data->property = null;
            $data->errormessage = "";
            $data->title = __('label.property_information');

            if ($property === null) {
                $data->errormessage = __('label.property_not_found');
                $data->email_body = "";
                return view('frontend.property.detail', (array) $data);
            }
            // ------------------------------------------------------------------

            if ($property->property_statuses_id == PropertyStatus::APPROVAL_PENDING || $property->property_statuses_id == PropertyStatus::NOT_POSTED || $property->property_statuses_id == PropertyStatus::FINISH_PUBLISH) {
                $data->errormessage = __('label.property_not_publish');
                $data->email_body = "";
                return view('frontend.property.detail', (array) $data);
            }

            //if (!$customer->not_leave_record) {
            // ------------------------------------------------------------------
            // check if there is data with same cust id (from logged in customer) and searched property id
            // ------------------------------------------------------------------
            /*
                if ($property->property_statuses_id == PropertyStatus::PULICATION_LIMITED) {
                    $customer_exsist = false;
                    foreach ($property->property_publishing_setting as $publish) {
                        if ($publish->customers_id == $customer_id) {
                            $customer_exsist = true;
                        }
                    }
                    if (!$customer_exsist) {
                        $data->errormessage = __('label.property_not_publish');
                        return view('frontend.property.detail', (array) $data);
                    }
                }

                if (!$customer->not_leave_record) {
                    // ------------------------------------------------------------------
                    // check if there is data with same cust id (from logged in customer) and searched property id
                    // ------------------------------------------------------------------
                    /*
                $existedBrowseActivity = CustomerLogActivity::where('action_types_id', 4)
                                                                    ->where('customers_id', $customer->id)
                                                                    ->where('properties_id', $property_id)
                                                                    ->first();

                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // if existed, update the ip, and access date
                // ------------------------------------------------------------------
                if($existedBrowseActivity)
                {
                    $existedBrowseActivity['ip']            = $request->ip();
                    $existedBrowseActivity['access_time']   = Carbon::now()->toDateTimeString();
                    $existedBrowseActivity->save();
                }
                // ------------------------------------------------------------------
                */
            // ------------------------------------------------------------------
            // if it isn't, create a new one
            // ------------------------------------------------------------------
            //$customerBrowseActivity                     = new CustomerLogActivity();
            //$customerBrowseActivity->customers_id       = $customer->id;
            //$customerBrowseActivity->properties_id      = $property_id;
            //$customerBrowseActivity->action_types_id    = 4;
            //$customerBrowseActivity->ip                 = $request->ip();
            //$customerBrowseActivity->access_time        = Carbon::now()->toDateTimeString();
            //$customerBrowseActivity->save();

            //---------------------------------------------------
            //Save customer log activity when View Property Detail
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_BROWSING, $customer->id, $request->ip(), $property_id);
            //---------------------------------------------------

            // ------------------------------------------------------------------
            //}
            // ------------------------------------------------------------------

            $idCustomers = $customer_id;
            $data->idCustomers  = $idCustomers;
            $data->idProperties = $id;
            $data->property = $property;
        } else {
            $property_id = $id;

            $property =  Property::with(['property_photos.file', 'property_flyers.file', 'customer_favorite_properties' => function ($q) use ($property_id) {
                $q->where([['properties_id', $property_id]]);
            }, 'company.company_users'])->where('id', $property_id)->first();

            // Define variables for blade
            $data->idProperties = $id;
            $data->property = null;
            $data->errormessage = "";
            $data->title = __('label.property_information');

            if ($property === null) {
                $data->errormessage = __('label.property_not_found');
                $data->email_body = "";
                return view('frontend.property.detail', (array) $data);
            }
            // ------------------------------------------------------------------

            if ($property->property_statuses_id == PropertyStatus::APPROVAL_PENDING || $property->property_statuses_id == PropertyStatus::NOT_POSTED || $property->property_statuses_id == PropertyStatus::FINISH_PUBLISH) {
                $data->errormessage = __('label.property_not_publish');
                $data->email_body = "";
                return view('frontend.property.detail', (array) $data);
            }

            if ($property->property_statuses_id == PropertyStatus::PULICATION_LIMITED) {
                $customer_exsist = false;
                // foreach ($property->property_publishing_setting as $publish) {
                //     if ($publish->customers_id == $customer_id) {
                //         $customer_exsist = true;
                //     }
                // }
                if (!$customer_exsist) {
                    $data->errormessage = __('label.property_not_publish');
                    $data->email_body = "";
                    return view('frontend.property.detail', (array) $data);
                }
            }

            // $idCustomers = $customer_id;
            // $data->idCustomers  = $idCustomers;
            $data->idProperties = $id;
            $data->property = $property;
        }

        //$new_line_code = "%0D%0A";
        $new_line_code = "<br>";

        // crate email for not logged in user.
        $email_location = "住所 : "  . $property->location;
        $email_price_min = "価格 : " . toManDisplay($property->minimum_price);
        $email_price_max =  $property->maximum_price == null ? '' : ' ~ ' . toManDisplay($property->maximum_price);

        $email_land_area_min = "土地面積 : " . toTsubo($property->minimum_land_area, '0,0') . '坪(' . $property->minimum_land_area . '㎡)';
        $email_land_area_max = $property->maximum_land_area == null ? '' : ' ~ ' . toTsubo($property->maximum_land_area, '0,0') . '坪(' . $property->maximum_land_area . '㎡)';
        $email_url = '物件URL : ' . $property->url->frontend_view;

        $email_body = '' . $new_line_code
            . $email_location . $new_line_code
            . $email_price_min . $email_price_max . $new_line_code
            . $email_land_area_min . $email_land_area_max . $new_line_code
            . $email_url . $new_line_code . $new_line_code;

        $data->email_body = $email_body;

        return view('frontend.property.detail', (array) $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
