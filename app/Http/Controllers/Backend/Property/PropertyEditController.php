<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Property;

use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\ImageHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// --------------------------------------------------------------------------
use App\Models\Property;
use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\CompanyUser as User;
use App\Models\Customer;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerNew;
use App\Models\PropertyStatus;
use App\Models\PropertyPhoto;
use App\Models\PropertyFlyer;
use App\Models\File;
use App\Models\PropertyPublishingSetting;
use App\Models\PropertyPublishingSetting as Publishing;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PropertyEditController extends Controller
{
    // ----------------------------------------------------------------------
    public function edit($propertyID)
    {
        // ------------------------------------------------------------------
        $data = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Page meta
        // ------------------------------------------------------------------
        $data->page_type = 'edit';
        $data->page_title = '物件の編集';
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Property
        // ------------------------------------------------------------------
        $relations = [
            'property_publishing_setting.company',
            'property_publishing_setting.company_user',
            'property_publishing_setting.customer',
            'property_photos.file', 'property_flyers.file',
            'property_publish'
        ];
        $data->property = Property::with($relations)->find($propertyID);
        if (!$data->property) return abort(404);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // All currently stored publishing data
        // ------------------------------------------------------------------
        $publishings = Publishing::where('properties_id', $propertyID)->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Creating publishing options of companies data with descendant data
        // ------------------------------------------------------------------
        $companies = Company::select(['id', 'company_name'])->get();
        $data->publishingOptions = $companies;
        // ------------------------------------------------------------------
        if (!$publishings->isEmpty()) {
            if (!$companies->isEmpty()) foreach ($companies as $company) {
                // ----------------------------------------------------------
                // If company exists in the publishing data
                // ----------------------------------------------------------
                $isSelectedCompany = $publishings->contains('companies_id', $company->id);
                if ($isSelectedCompany) {
                    // ------------------------------------------------------
                    // Load the user list
                    // ------------------------------------------------------
                    $company->load(['users' => function ($query) {
                        $query->select(['id', 'companies_id', 'name', 'email']);
                    }]);
                    // ------------------------------------------------------
                    if (!$company->users->isEmpty()) foreach ($company->users as $user) {
                        // --------------------------------------------------
                        // If user exists in the publishing data,
                        // Load the customer list
                        // --------------------------------------------------
                        $isSelectedUser = $publishings->contains('company_users_id', $user->id);
                        if ($isSelectedUser) $user->load(['customers' => function ($query) {
                            $query->select(['id', 'company_users_id', 'name', 'email']);
                        }]);
                        // --------------------------------------------------
                    }
                }
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Grouped publishing data
        // ------------------------------------------------------------------
        $homeMakerPublishings = Publishing::where('type', 'home_maker')->where('properties_id', $propertyID)->get();
        $realEstatePublishings = Publishing::where('type', 'real_estate')->where('properties_id', $propertyID)->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Publishing selection flag / Boolean
        // ------------------------------------------------------------------
        $selection = new \stdClass;
        $selection->homeMaker = !$homeMakerPublishings->isEmpty();
        $selection->realEstate = !$realEstatePublishings->isEmpty();
        $data->publishingSelection = $selection;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $customer = new \stdClass;
        $customer->homeMaker = $homeMakerPublishings;
        $customer->realEstate = $realEstatePublishings;
        $data->publishingCustomer = $customer;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // New customer template
        // ------------------------------------------------------------------
        $template['homeMaker'] = factory(PropertyPublishingSetting::class)->state('init')->make(['type' => 'home_maker']);
        $template['realEstate'] = factory(PropertyPublishingSetting::class)->state('init')->make(['type' => 'real_estate']);
        $data->template = $template;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // All companies by type
        // ------------------------------------------------------------------
        $role = 'company_roles_id';
        $homeMaker = CompanyRole::ROLE_HOME_MAKER;
        $realEstate = CompanyRole::ROLE_ADMIN;
        // ------------------------------------------------------------------
        $allCompany['realEstate'] = Company::with('role')->where($role, $realEstate)->get();
        $allCompany['homeMaker'] = Company::with('role')->where($role, $homeMaker)->get();
        $data->company = $allCompany;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // return $data;
        return view('backend.property.edit.form', (array) $data);
        // ------------------------------------------------------------------
    }

    public function update(Request $request, $id)
    {
        // --------------------------------------------------------------
        // get property data from request
        // --------------------------------------------------------------
        $dataset = json_decode($request->dataset, true);
        $property_data = $dataset['property'];
        // --------------------------------------------------------------

        // --------------------------------------------------------------
        // if building condition is false, make it's desc to null
        // --------------------------------------------------------------
        if (!$property_data['building_conditions']) $property_data['building_conditions_desc'] = null;
        // --------------------------------------------------------------

        $current_property = Property::findOrFail($id);

        // --------------------------------------------------------------
        // Create the publishing customers
        // --------------------------------------------------------------
        PropertyPublishingSetting::where('properties_id', $id)->delete();
        $customerList = [];
        if (!empty($dataset['customer'])) {
            foreach ($dataset['customer'] as $group) {
                foreach ($group as $customer) {

                    // --------------------------------------------------
                    //CONDITION IF JUST INPUT COMPANY, USER NULL, CUSTOMER NULL
                    //INPUT ALL CUSTOMER RELATED TO THE COMPANY
                    // --------------------------------------------------
                    if ($customer['company_users_id'] == Null && $customer['customers_id'] == Null) {
                        $companyId = $customer['companies_id'];
                        $get_all_customer = Customer::with('company_user.company')
                            ->whereHas('company_user.company', function ($query) use ($companyId) {
                                $query->where('id', $companyId);
                            })->get();

                        foreach ($get_all_customer as $cust) {
                            if (in_array($cust->id, $customerList)) {
                                continue;
                            }
                            array_push($customerList, $cust->id);

                            $customer['company_users_id'] = $cust['company_users_id'];
                            $customer['customers_id'] = $cust['id'];
                            $customer['properties_id'] = $current_property['id'];

                            PropertyPublishingSetting::create($customer);
                        }
                    }
                    // --------------------------------------------------

                    // --------------------------------------------------
                    //CONDITION IF JUST INPUT COMPANY and USER, CUSTOMER NULL
                    //INPUT ALL CUSTOMER RELATED TO THE COMPANY USER
                    // --------------------------------------------------
                    elseif ($customer['customers_id'] == Null) {
                        $companyUserId = $customer['company_users_id'];
                        $get_all_customer = Customer::with('company_user')
                            ->whereHas('company_user', function ($query) use ($companyUserId) {
                                $query->where('id', $companyUserId);
                            })->get();

                        foreach ($get_all_customer as $cust) {
                            if (in_array($cust->id, $customerList)) {
                                continue;
                            }
                            array_push($customerList, $cust->id);

                            $customer['customers_id'] = $cust['id'];
                            $customer['properties_id'] = $current_property['id'];

                            PropertyPublishingSetting::create($customer);
                        }
                    }
                    // --------------------------------------------------

                    else {
                        // --------------------------------------------------
                        // if (in_array($customer['companies_id'], $customerList)) {
                        //     continue;
                        // }
                        array_push($customerList, $customer['companies_id']);
                        $customer['properties_id'] = $current_property['id'];

                        PropertyPublishingSetting::create($customer);
                        // --------------------------------------------------
                    }
                }
            }
        }

        /**
         * Create customer news.
         */

        // --------------------------------------------------------------
        // if property change from not published to publish , create customer news.
        // PropertyStatus::APPROVAL_PENDING / PropertyStatus::NOT_POSTED / PropertyStatus::FINISH_PUBLISH ( not published ) to
        // PropertyStatus::PUBLISHED / PropertyStatus::PULICATION_LIMITED ( published )
        // --------------------------------------------------------------
        // TODO: Since this conditions are exactly same to one of ApiPropertyDialogController.php, let's make function on Property.
        $is_alreadyAdd_news = false;
        if (
            $current_property->property_statuses_id == PropertyStatus::APPROVAL_PENDING ||
            $current_property->property_statuses_id == PropertyStatus::NOT_POSTED ||
            $current_property->property_statuses_id == PropertyStatus::FINISH_PUBLISH
        ) {
            if (
                $property_data['property_statuses_id']  == PropertyStatus::PUBLISHED ||
                $property_data['property_statuses_id']  == PropertyStatus::PULICATION_LIMITED
            ) {
                $customer_ids = null;
                if ( $property_data['property_statuses_id']  == PropertyStatus::PUBLISHED ) {
                    $customer_ids = null;
                } elseif ( $property_data['property_statuses_id']  == PropertyStatus::PULICATION_LIMITED ){
                    //
                    $customer_ids = PropertyPublishingSetting::
                        where('properties_id', $current_property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    $customer_ids = CustomerFavoriteProperty::
                        whereIn('customers_id', $customer_ids)
                        ->where('properties_id', $current_property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    $notPublishcustomer_ids = CustomerFavoriteProperty::
                        whereNotIn('customers_id', $customer_ids)
                        ->where('properties_id', $current_property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    CustomerNew::storeNews($current_property, CustomerNew::PROPERTY_END, $notPublishcustomer_ids);
                }
                CustomerNew::storeNews($current_property, CustomerNew::ADD_PROPERTY, $customer_ids);
                $is_alreadyAdd_news = true;
                $property_data['publish_date'] = Carbon::now();
            }
        }

        if (
            $current_property['property_statuses_id'] == PropertyStatus::PUBLISHED ||
            $current_property['property_statuses_id'] == PropertyStatus::PULICATION_LIMITED
        ) {

            // if status change from 2(publish) to 3(Limited publish)
            if (
                $current_property['property_statuses_id'] == PropertyStatus::PUBLISHED &&
                $property_data['property_statuses_id'] == PropertyStatus::PULICATION_LIMITED &&
                !$is_alreadyAdd_news
            ) {

                $customer_ids = PropertyPublishingSetting::
                    where('properties_id', $current_property['id'])
                    ->pluck('customers_id')
                    ->toArray();

                $customer_ids = CustomerFavoriteProperty::
                    whereIn('customers_id', $customer_ids)
                    ->where('properties_id', $current_property['id'])
                    ->pluck('customers_id')
                    ->toArray();

                $notPublishcustomer_ids = CustomerFavoriteProperty::
                    whereNotIn('customers_id', $customer_ids)
                    ->where('properties_id', $current_property['id'])
                    ->pluck('customers_id')
                    ->toArray();

                CustomerNew::storeNews($current_property, CustomerNew::PROPERTY_END, $notPublishcustomer_ids);
                CustomerNew::storeNews($current_property, CustomerNew::PROPERTY_UPDATE, $customer_ids);
                $is_alreadyAdd_news = true;
            }

            // if status change from 3(Limited publish) to 2(publish)
            if (
                $current_property->property_statuses_id == PropertyStatus::PULICATION_LIMITED &&
                $property_data['property_statuses_id'] == PropertyStatus::PUBLISHED &&
                !$is_alreadyAdd_news
            ) {
                // 限定掲載の設定で紐付いている
                $customer_ids = PropertyPublishingSetting::
                    where('properties_id', $current_property['id'])
                    ->pluck('customers_id')
                    ->toArray();

                // 限定掲載の設定で紐付いている+物件をお気に入りに登録している
                $customer_ids = CustomerFavoriteProperty::
                    whereIn('customers_id', $customer_ids)
                    ->where('properties_id', $current_property['id'])
                    ->pluck('customers_id')
                    ->toArray();

                // 限定掲載の設定で紐付いていない+物件をお気に入りに登録している
                $addPublishcustomer_ids = CustomerFavoriteProperty::
                    whereNotIn('customers_id', $customer_ids)
                    ->where('properties_id', $current_property['id'])
                    ->pluck('customers_id')
                    ->toArray();

                CustomerNew::storeNews($current_property, CustomerNew::ADD_PROPERTY, $addPublishcustomer_ids);
                $is_alreadyAdd_news = true;
            }
            // TODO: Why fill here in advance??
            $current_property->fill($property_data);
            // if anything data is changed.
            if ($current_property->isDirty() &&
                !$is_alreadyAdd_news &&
                $property_data['property_statuses_id'] == PropertyStatus::PUBLISHED ||
                $property_data['property_statuses_id'] == PropertyStatus::PULICATION_LIMITED
            ) {
                CustomerNew::storeNews($current_property, CustomerNew::PROPERTY_UPDATE);
                $is_alreadyAdd_news = true;
            }
        }

        // if status change to 4(not publish), 5(publish finish) and 1(pending)
        if (
            $property_data['property_statuses_id'] == PropertyStatus::NOT_POSTED ||
            $property_data['property_statuses_id'] == PropertyStatus::FINISH_PUBLISH ||
            $property_data['property_statuses_id'] == PropertyStatus::APPROVAL_PENDING
        ) {
            if (!$is_alreadyAdd_news) {
                CustomerNew::storeNews($current_property, CustomerNew::PROPERTY_END);
            }
        }

        $current_property->fill($property_data);
        $current_property->save();

        // --------------------------------------------------------------
        // return response
        // --------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        $response->property = $current_property;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // --------------------------------------------------------------
    }
}
