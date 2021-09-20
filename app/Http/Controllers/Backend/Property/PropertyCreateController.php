<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Property;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
// --------------------------------------------------------------------------
use App\Models\Company;
use App\Models\CompanyRole;
// --------------------------------------------------------------------------
use App\Models\Customer;
use App\Models\File;
use App\Models\Property;
use App\Models\PropertyConvertStatus;
use App\Models\PropertyFlyer as Flyer;
use App\Models\PropertyPhoto as Photo;
use App\Models\PropertyPublishingSetting as Publishing;
use App\Models\Scraping;

// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PropertyCreateController extends Controller
{

    protected function validator(array $data, $type)
    {
        return Validator::make($data, [
            'property_statuses_id'          => 'required|numeric|max:20',
            'building_conditions'           => 'required|numeric|max:1',
            'building_conditions_desc'      => 'required|string',
            'location'                      => 'required|string',
            'property_photo.*'              => 'mimes:jpeg,jpg,png,gif',
            'property_flyer.*'              => 'mimes:jpeg,jpg,png,gif',
            'add_property_photo.*'          => 'mimes:jpeg,jpg,png,gif',
            'add_property_flyer.*'          => 'mimes:jpeg,jpg,png,gif',
            'home_maker_company.*'          => 'required|numeric|exists:companies,id',
            'home_maker_company_user.*'     => 'required|numeric|exists:company_users,id',
            'home_maker_customer.*'         => 'required|numeric|exists:customers,id',
            'real_estate_company.*'         => 'required|numeric|exists:companies,id',
            'real_estate_company_user.*'    => 'required|numeric|exists:company_users,id',
            'real_estate_customer.*'        => 'required|numeric|exists:customers,id',
        ]);
    }
    // ----------------------------------------------------------------------
    // Index page
    // ----------------------------------------------------------------------
    public function index()
    {
        $data = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $data->page_type = "create";
        $data->page_title = __('label.create_property');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Initial property data
        // ------------------------------------------------------------------
        $data->property = factory(Property::class)->state('init')->make();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Creating publishing options of companies data
        // ------------------------------------------------------------------
        $companies = Company::select(['id', 'company_name'])->get();
        $data->publishingOptions = $companies;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Initial publishing/customers data
        // ------------------------------------------------------------------
        $homeMaker = factory(Publishing::class)->state('init')->make(['type' => 'home_maker']);
        $realEstate = factory(Publishing::class)->state('init')->make(['type' => 'real_estate']);
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
        // dd( $data->company );
        return view('backend.property.create.index', (array) $data);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Store process
    // ----------------------------------------------------------------------
    public function store(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------
        $dataset = json_decode($request->dataset);
        if (!empty($dataset->property)) {
            // --------------------------------------------------------------
            $dataProperty = (object) $dataset->property;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $dataProperty->property_scraping_type_id = 100;
            $dataProperty->property_convert_status_id = PropertyConvertStatus::SUCCESSFUL;
            $dataProperty->companies_id = 1;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Convert price from Yen to Man
            // --------------------------------------------------------------
            if (!empty($dataProperty->minimum_price)) {
                $dataProperty->minimum_price = fromMan($dataProperty->minimum_price);
            }
            if (!empty($dataProperty->maximum_price)) {
                $dataProperty->maximum_price = fromMan($dataProperty->maximum_price);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Convert land-area from Tsubo to Meter
            // --------------------------------------------------------------
            if (!empty($dataProperty->minimum_land_area)) {
                $dataProperty->minimum_land_area = fromTsubo($dataProperty->minimum_land_area);
            }
            if (!empty($dataProperty->maximum_land_area)) {
                $dataProperty->maximum_land_area = fromTsubo($dataProperty->maximum_land_area);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the property
            // --------------------------------------------------------------
            $property = Property::create((array) $dataProperty);
            $response->property = $property; // Add to the JSON response
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the publishing customers
            // --------------------------------------------------------------
            $customerList = [];
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

                            foreach ($get_all_customer as $cust) {
                                if (in_array($cust->id, $customerList)) {
                                    continue;
                                }
                                array_push($customerList, $cust->id);

                                $customer->company_users_id = $cust->company_users_id;
                                $customer->customers_id = $cust->id;
                                $customer->properties_id = $property->id;

                                Publishing::create((array) $customer);
                            }
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

                            foreach ($get_all_customer as $cust) {
                                if (in_array($cust->id, $customerList)) {
                                    continue;
                                }
                                array_push($customerList, $cust->id);

                                $customer->customers_id = $cust->id;
                                $customer->properties_id = $property->id;

                                Publishing::create((array) $customer);
                            }
                        }
                        // --------------------------------------------------

                        else {
                            // --------------------------------------------------
                            if (in_array($customer->companies_id, $customerList)) {
                                continue;
                            }
                            array_push($customerList, $customer->companies_id);
                            $customer->properties_id = $property->id;
                            Publishing::create((array) $customer);
                            // --------------------------------------------------
                        }
                    }
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Process the file upload
            // --------------------------------------------------------------
            $uploads = ['photos', 'flyers'];
            foreach ($uploads as $group) if ($request->hasfile($group)) {
                foreach ($request->file($group) as $key => $image) {
                    // ------------------------------------------------------
                    $file = File::uploadFile($image, $property->id, $group);
                    // ------------------------------------------------------
                    if ('photos' == $group) {
                        $fileable = array(
                            'file_id' => $file->id,
                            'properties_id' => $property->id,
                            'sort_number' => $request->sort_number_photo[$key],
                        );
                        Photo::create($fileable);
                    } elseif ('flyers' == $group) {
                        $fileable = array(
                            'file_id' => $file->id,
                            'properties_id' => $property->id,
                            'sort_number' => $request->sort_number_flyer[$key],
                        );
                        Flyer::create($fileable);
                    }
                    // ------------------------------------------------------
                }
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
