<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Property;

use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
// --------------------------------------------------------------------------
use App\Models\Company;
use App\Models\CompanyUser as User;
use App\Models\CompanyRole;
// --------------------------------------------------------------------------
use App\Models\Customer;
use App\Models\CustomerNew;
use App\Models\CustomerDesiredArea;
use App\Models\Property;
use App\Models\PropertyDelivery;
use App\Models\PropertyDeliveryTargetSetting;
use App\Models\Town;
use App\Console\Scraping\DataRow\Base\BaseScrapingRow;

use Illuminate\Support\Facades\Log;

// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PropertyDeliveryController extends Controller
{
    // ----------------------------------------------------------------------
    public function index($propertyID)
    {
        $data = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $data->property_id = $propertyID;
        $data->page_type = "create";
        $data->page_title = '情報配信';
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Initial property data
        // ------------------------------------------------------------------
        $data->propertyDelivery = factory(PropertyDelivery::class)->state('init')->make();
        // ------------------------------------------------------------------
        // Initial TargetSetting/customers data
        // ------------------------------------------------------------------
        $homeMaker = factory(PropertyDeliveryTargetSetting::class)->state('init')->make();
        $realEstate = factory(PropertyDeliveryTargetSetting::class)->state('init')->make();
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        $customer = new \stdClass;
        $customer->homeMaker = array($homeMaker);
        $customer->realEstate = array($realEstate);
        $data->customer = $customer;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // New customer template
        // ------------------------------------------------------------------
        $template = new \stdClass;
        $template->homeMaker = $homeMaker;
        $template->realEstate = $realEstate;
        $data->template = $template;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // All companies by type
        // ------------------------------------------------------------------
        $company = new \stdClass;
        $data->company = $company;
        // ------------------------------------------------------------------
        $role = 'company_roles_id';
        $homeMaker = CompanyRole::ROLE_HOME_MAKER;
        $realEstate = CompanyRole::ROLE_ADMIN;
        // ------------------------------------------------------------------
        $company->realEstate = Company::with('role')->where($role, $realEstate)->get();
        $company->homeMaker = Company::with('role')->where($role, $homeMaker)->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return view('backend.property.delivery.index', (array) $data);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Store method
    // ----------------------------------------------------------------------
    public function store($propertyID, Request $request)
    {
        // ------------------------------------------------------------------
        $customerList = [];
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------
        $dataset = json_decode($request->dataset);

        $propertyModel =  Property::find($propertyID);

        if (!empty($dataset->propertyDelivery)) {
            // Set Properties ID
            $dataset->propertyDelivery->properties_id = $propertyID;
            $dataset->propertyDelivery->created_at = Carbon::now();
            $dataset->propertyDelivery->updated_at = Carbon::now();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the property
            // --------------------------------------------------------------
            $PropertyDelivery = PropertyDelivery::create((array) $dataset->propertyDelivery);

            if ($PropertyDelivery->favorite_registered_area == 1) {
                $afterLoctation = BaseScrapingRow::convartLocation($propertyModel->location);
                if ($afterLoctation['TOWN_ID'] != null) {
                    $town_id = $afterLoctation['TOWN_ID'];
                    $town = Town::find($town_id);
                    $city = $town->city;

                    $customerDesiredList  = CustomerDesiredArea::where(function ($query) use ($city, $town) {
                        $query->where('cities_area_id', $town->city_areas->id)->where('cities_id', $city->id);
                    })
                        ->orWhere(function ($query) use ($city) {
                            $query->whereNull('cities_area_id')->where('cities_id', $city->id);
                        })->get();

                    foreach ($customerDesiredList as $desiredCustomer) {
                        $tmp = new \stdClass;
                        $tmp->company_users_id = 9999;
                        $tmp->customers_id = $desiredCustomer->customers_id;
                        $dataset->customer->favarite[] = $tmp;
                    }
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the publishing customers
            // --------------------------------------------------------------

            if (!empty($dataset->customer)) {
                foreach ($dataset->customer as $group) {
                    foreach ($group as $customer) {

                        // --------------------------------------------------
                        //CONDITION IF JUST INPUT COMPANY, USER NULL, CUSTOMER NULL
                        //INPUT ALL CUSTOMER RELATED TO THE COMPANY
                        // --------------------------------------------------
                        if ($customer->company_users_id == Null && $customer->customers_id == Null) {
                            $companyId = $customer->companies_id;
                            $get_all_customer = Customer::with('company_user.company')
                                ->whereHas('company_user.company', function ($query) use ($companyId) {
                                    $query->where('id', $companyId);
                                })->get();

                            // --------------------------------------------------
                            foreach ($get_all_customer as $cust) {
                                if (in_array($cust->id, $customerList)) {
                                    continue;
                                }
                                array_push($customerList, $cust->id);
                                $this->Store_News($PropertyDelivery, $cust->id, $cust->company_users_id, $customerList, $customer);
                            }
                            // --------------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        //CONDITION IF JUST INPUT COMPANY and USER, CUSTOMER NULL
                        //INPUT ALL CUSTOMER RELATED TO THE COMPANY USER
                        // --------------------------------------------------
                        elseif ($customer->customers_id == Null) {
                            $companyUserId = $customer->company_users_id;
                            $get_all_customer = Customer::with('company_user')
                                ->whereHas('company_user', function ($query) use ($companyUserId) {
                                    $query->where('id', $companyUserId);
                                })->get();

                            // --------------------------------------------------
                            foreach ($get_all_customer as $cust) {
                                if (in_array($cust->id, $customerList)) {
                                    continue;
                                }
                                array_push($customerList, $cust->id);
                                $this->Store_News($PropertyDelivery, $cust->id, $cust->company_users_id, $customerList, $customer);
                            }
                            // --------------------------------------------------
                        }
                        // --------------------------------------------------
                        else {
                            $get_all_customer = Customer::where('id', $customer->customers_id)->get();
                            // --------------------------------------------------
                            foreach ($get_all_customer as $cust) {
                                if (in_array($cust->id, $customerList)) {
                                    continue;
                                }
                                array_push($customerList, $cust->id);
                                $this->Store_News($PropertyDelivery, $cust->id, $cust->company_users_id, $customerList, $customer);
                            }
                            // --------------------------------------------------
                        }
                        // --------------------------------------------------
                    }
                }
            }
            // --------------------------------------------------------------


            $response->property = $propertyModel; // Add to the JSON response
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    private function Store_News($PropertyDelivery, $custId, $custCompanyUser, $customerList, $customer)
    {
        try {
            $is_exclude_notices = true;
            $is_exclude_budget = true;
            $is_exclude_land = true;

            // --------------------------------------------------------------
            // Exclude Notices
            // --------------------------------------------------------------
            if ($PropertyDelivery->exclude_received_over_three == 1) {
                $is_exclude_notices = $this->Exclude_Notices($custId, $PropertyDelivery->properties_id);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Exclude Customer out of budget
            // --------------------------------------------------------------
            if ($PropertyDelivery->exclude_customers_outside_the_budget == 1) {
                $is_exclude_budget = $this->Exclude_Budget($custId, $PropertyDelivery->properties_id);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Exclude Customer out of land
            // --------------------------------------------------------------
            if ($PropertyDelivery->exclude_customers_outside_the_desired_land_area == 1) {
                $is_exclude_land = $this->Exclude_Land($custId, $PropertyDelivery->properties_id);
            }
            // --------------------------------------------------------------

            if ($is_exclude_notices && $is_exclude_budget && $is_exclude_land) {
                $customer->company_users_id = $custCompanyUser;
                $customer->customers_id = $custId;
                $customer->property_deliveries_id = $PropertyDelivery->id;

                PropertyDeliveryTargetSetting::create((array) $customer);

                $savedata = CustomerNew::create([
                    'customers_id' => $custId,
                    'type' => CustomerNew::RECOMMENDED_PROPERTY,
                    'is_show' => 0,
                    'location' => $PropertyDelivery->property->location,
                    'property_deliveries_id' => $PropertyDelivery->id,
                ]);

                //------------------------------------------------------
                // SAVE LOG INFO
                //------------------------------------------------------
                Log::info(Carbon::now() . " - Created Customer News: ", ['id' => $savedata->id, 'customers_id' => $custId, 'type' => CustomerNew::RECOMMENDED_PROPERTY, 'property_deliveries_id' => $PropertyDelivery->id]);
                //------------------------------------------------------
            }
        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport(
                "Failed Create Customer",
                $e->getMessage()
            );
            //------------------------------------------------------
        }
    }


    private function Exclude_Notices($customer_id, $property_id)
    {
        // --------------------------------------------------------------
        // Exclude Notices
        // --------------------------------------------------------------

        $customer_news = CustomerNew::where('customers_id', $customer_id)
            ->whereHas('property_deliveries', function ($query) use ($property_id) {
                $query->where('properties_id', $property_id);
            })->count();

        if ($customer_news >= 3) {
            return false;
        }
        return true;
        // --------------------------------------------------------------

    }

    private function Exclude_Budget($customer_id, $property_id)
    {
        // --------------------------------------------------------------
        // Exclude Customer out of budget
        // --------------------------------------------------------------
        $customer = Customer::find($customer_id);
        $customer_min_price = $customer->minimum_price;
        $customer_max_price = $customer->maximum_price;

        // ------------------------------------------------------------------
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($customer_min_price) ? toMan($customer_min_price) : '';
        $maxPrice = !empty($customer_max_price) ? toMan($customer_max_price) : '';

        $property = Property::where('id', $property_id)->PriceRange($minPrice, $maxPrice)->first();

        $result = true;
        if ($property == null) {
            $result = false;
        }
        return $result;
        // --------------------------------------------------------------
    }

    private function Exclude_Land($customer_id, $property_id)
    {
        // --------------------------------------------------------------
        // Exclude Customer out of Land Area
        // --------------------------------------------------------------
        $customer = Customer::find($customer_id);
        $customer_min_land = $customer->minimum_land_area;
        $customer_max_land = $customer->maximum_land_area;

        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($customer_min_land) ? toTsubo($customer_min_land) : '';
        $maxLandArea = !empty($customer_max_land) ? toTsubo($customer_max_land) : '';

        $property = Property::where('id', $property_id)->LandAreaRange($minLandArea, $maxLandArea)->first();

        $result = true;
        if ($property == null) {
            $result = false;
        }
        return $result;
        // --------------------------------------------------------------

    }
}
// --------------------------------------------------------------------------
