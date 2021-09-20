<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\Customer;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerNew;
use App\Models\File;
use App\Models\Property;
use App\Models\PropertyFlyer;
use App\Models\PropertyPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PropertyPublishingSetting as Publishing;
use App\Models\PropertyPublishingSetting;
use App\Models\PropertyStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApiPropertyDialogController extends Controller
{
    public function uploadPhotos(Request $request, $propertyID)
    {
        if ($request->hasFile('photos')) {
            $file = $request->file('photos');
            // ------------------------------------------------------
            $id = (string) Str::uuid();
            $directory = 'properties';
            $extension = $file->extension();
            $filename = "property-{$propertyID}-photos-{$id}";
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Upload the file
            // ------------------------------------------------------
            $file->storeAs($directory, "{$filename}.{$extension}");
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Store into the File table
            // ------------------------------------------------------
            $storedFile = File::create([
                'name' => "{$filename}",
                'extension' => $extension,
            ]);
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Store to property photos/flyers table
            // ------------------------------------------------------
            $GetSortNumber = PropertyPhoto::where('properties_id', $propertyID)->orderBy('id', 'desc')->first();
            if (empty($GetSortNumber)) {
                $SortNumber = 1;
            } else {
                $SortNumber = $GetSortNumber->sort_number + 1;
            }
            $fileable = array(
                'file_id' => $storedFile->id,
                'properties_id' => $propertyID,
                'sort_number' => $SortNumber
            );
            // ------------------------------------------------------
            // Store to property photos/flyers table
            // ------------------------------------------------------
            $save = PropertyPhoto::create($fileable);
            // ------------------------------------------------------
            if ($save) {
                $response = new \stdClass;
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
            // ------------------------------------------------------
        } else {
            abort(404);
        }
    }

    public function uploadFlyers(Request $request, $propertyID)
    {
        if ($request->hasFile('flyers')) {
            $file = $request->file('flyers');
            // ------------------------------------------------------
            $id = (string) Str::uuid();
            $directory = 'properties';
            $extension = $file->extension();
            $filename = "property-{$propertyID}-flyers-{$id}";
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Upload the file
            // ------------------------------------------------------
            $file->storeAs($directory, "{$filename}.{$extension}");
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Store into the File table
            // ------------------------------------------------------
            $storedFile = File::create([
                'name' => "{$filename}",
                'extension' => $extension,
            ]);
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Store to property photos/flyers table
            // ------------------------------------------------------
            $GetSortNumber = PropertyPhoto::where('properties_id', $propertyID)->orderBy('id', 'desc')->first();
            if (empty($GetSortNumber)) {
                $SortNumber = 1;
            } else {
                $SortNumber = $GetSortNumber->sort_number + 1;
            }
            $fileable = array(
                'file_id' => $storedFile->id,
                'properties_id' => $propertyID,
                'sort_number' => $SortNumber
            );
            // ------------------------------------------------------
            // Store to property photos/flyers table
            // ------------------------------------------------------
            $save = PropertyFlyer::create($fileable);
            // ------------------------------------------------------
            if ($save) {
                $response = new \stdClass;
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
            // ------------------------------------------------------
        } else {
            abort(404);
        }
    }

    public function updatePhotos(Request $request, $propertyID)
    {
        if ($request->hasFile('photos')) {
            $photoID = $request->photo_id;

            $file = $request->file('photos');
            // ------------------------------------------------------
            $id = (string) Str::uuid();
            $directory = 'properties';
            $extension = $file->extension();
            $filename = "property-{$propertyID}-photos-{$id}";
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Delete the file
            // ------------------------------------------------------
            $photo = PropertyPhoto::where('id', $photoID)->first();
            $file_id = $photo->file_id;
            // ------------------------------------------------------
            $filephoto = File::find($file_id);
            $filepath = '/properties/' . $filephoto->name . '.' . $filephoto->extension;
            Storage::delete($filepath);
            // ------------------------------------------------------
            // Upload the file
            // ------------------------------------------------------
            $file->storeAs($directory, "{$filename}.{$extension}");
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Store into the File table
            // ------------------------------------------------------
            $storedFile = $filephoto->update([
                'name' => "{$filename}",
                'extension' => $extension,
            ]);
            // ------------------------------------------------------
            if ($storedFile) {
                $response = new \stdClass;
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
            // ------------------------------------------------------
        } else {
            abort(404);
        }
    }

    public function updateFlyers(Request $request, $propertyID)
    {
        if ($request->hasFile('flyers')) {
            $flayerID = $request->flyer_id;

            $file = $request->file('flyers');
            // ------------------------------------------------------
            $id = (string) Str::uuid();
            $directory = 'properties';
            $extension = $file->extension();
            $filename = "property-{$propertyID}-flyers-{$id}";
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Delete the file
            // ------------------------------------------------------
            $flayer = PropertyFlyer::where('id', $flayerID)->first();
            $file_id = $flayer->file_id;
            // ------------------------------------------------------
            $fileflayer = File::find($file_id);
            $filepath = '/properties/' . $fileflayer->name . '.' . $fileflayer->extension;
            Storage::delete($filepath);
            // ------------------------------------------------------
            // Upload the file
            // ------------------------------------------------------
            $file->storeAs($directory, "{$filename}.{$extension}");
            // ------------------------------------------------------
            // ------------------------------------------------------
            // Store into the File table
            // ------------------------------------------------------
            $storedFile = $fileflayer->update([
                'name' => "{$filename}",
                'extension' => $extension,
            ]);
            // ------------------------------------------------------
            if ($storedFile) {
                $response = new \stdClass;
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
            // ------------------------------------------------------
        } else {
            abort(404);
        }
    }

    public function deletePhotos(Request $request)
    {
        try {
            // ------------------------------------------------------
            // Delete the file
            // ------------------------------------------------------
            $photoID = $request->photo_id;
            $photo = PropertyPhoto::where('id', $photoID)->first();
            $file_id = $photo->file_id;
            $filephoto = File::find($file_id);
            $filepath = '/properties/' . $filephoto->name . '.' . $filephoto->extension;
            $photo->delete();
            $filephoto->delete();
            Storage::delete($filepath);
            $response = new \stdClass;
            $response->status = 'success';
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (deletePhotos Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function deleteFlyers(Request $request)
    {
        try {
            // ------------------------------------------------------
            // Delete the file
            // ------------------------------------------------------
            $flayerID = $request->flyer_id;
            $flayer = PropertyFlyer::where('id', $flayerID)->first();
            $file_id = $flayer->file_id;
            // ------------------------------------------------------
            $fileflayer = File::find($file_id);
            $filepath = '/properties/' . $fileflayer->name . '.' . $fileflayer->extension;
            $delete = Storage::delete($filepath);
            if ($delete) {
                $response = new \stdClass;
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (deleteFlyers Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function orderPhotoNext(Request $request, $photoID)
    {
        try {
            $response = new \stdClass;
            $dataPropertytmp = 0;
            $dataNexttmp = 0;
            $property = PropertyPhoto::where('id', $photoID)->first();
            $nextID = PropertyPhoto::where('id', '>', $photoID)->min('id');
            $next = PropertyPhoto::where('id', $nextID)->first();

            $last = PropertyPhoto::where('properties_id', $property->properties_id)->latest('id')->first();
            if ($last->id != $photoID) {

                $dataPropertytmp = $property->sort_number;
                $dataNexttmp = $next->sort_number;

                $property->sort_number = $dataNexttmp;
                $property->save();

                $next->sort_number = $dataPropertytmp;
                $next->save();

                $response->propertyPhoto = PropertyPhoto::where('properties_id', $next->properties_id)->first();
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (orderPhotoNext Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function orderPhotoPrev(Request $request, $photoID)
    {
        try {
            $response = new \stdClass;
            $dataPropertytmp = 0;
            $dataPrevtmp = 0;
            $property = PropertyPhoto::where('id', $photoID)->first();
            $previousID = PropertyPhoto::where('id', '<', $photoID)->max('id');
            $previous = PropertyPhoto::where('id', $previousID)->first();

            $first = PropertyPhoto::where('properties_id', $property->properties_id)->first();
            if ($first->id != $photoID) {
                $dataPropertytmp = $property->sort_number;
                $dataPrevtmp = $previous->sort_number;

                $property->sort_number = $dataPrevtmp;
                $property->save();

                $previous->sort_number = $dataPropertytmp;
                $previous->save();

                $response->propertyPhoto = PropertyPhoto::where('properties_id', $previous->properties_id)->first();
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (orderPhotoPrev Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function orderFlyerNext(Request $request, $photoID)
    {
        try {
            $response = new \stdClass;
            $dataPropertytmp = 0;
            $dataNexttmp = 0;
            $property = PropertyFlyer::where('id', $photoID)->first();
            $nextID = PropertyFlyer::where('id', '>', $photoID)->min('id');
            $next = PropertyFlyer::where('id', $nextID)->first();

            $last = PropertyFlyer::where('properties_id', $property->properties_id)->latest('id')->first();
            if ($last->id != $photoID) {

                $dataPropertytmp = $property->sort_number;
                $dataNexttmp = $next->sort_number;

                $property->sort_number = $dataNexttmp;
                $property->save();

                $next->sort_number = $dataPropertytmp;
                $next->save();

                $response->propertyFlyer = PropertyFlyer::where('properties_id', $next->properties_id)->first();
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (orderFlyerNext Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function orderFlyerPrev(Request $request, $photoID)
    {
        try {
            $response = new \stdClass;
            $dataPropertytmp = 0;
            $dataPrevtmp = 0;
            $property = PropertyFlyer::where('id', $photoID)->first();
            $previousID = PropertyFlyer::where('id', '<', $photoID)->max('id');
            $previous = PropertyFlyer::where('id', $previousID)->first();

            $first = PropertyFlyer::where('properties_id', $property->properties_id)->first();
            if ($first->id != $photoID) {
                $dataPropertytmp = $property->sort_number;
                $dataPrevtmp = $previous->sort_number;

                $property->sort_number = $dataPrevtmp;
                $property->save();

                $previous->sort_number = $dataPropertytmp;
                $previous->save();

                $response->propertyFlyer = PropertyFlyer::where('properties_id', $previous->properties_id)->first();
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (orderFlyerPrev Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $property = Property::with(['property_status', 'property_photos.file', 'property_flyers.file'])->findOrFail($request->id);
            $property_data['is_bug_report'] = $request->is_bug_report;
            $property_data['property_statuses_id'] = $request->status_id;

            // --------------------------------------------------------------
            // --------------------------------------------------------------
            // Create the publishing customers
            // --------------------------------------------------------------
            PropertyPublishingSetting::where('properties_id', $request->id)->delete();
            $customerList = [];
            if (!empty($request->customer)) {
                foreach ($request->customer as $group) {
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
                                $customer['properties_id'] = $property['id'];

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
                                $customer['properties_id'] = $property['id'];
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
                            $customer['properties_id'] = $property['id'];
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
            // TODO: Since this conditions are exactly same to one of PropertyEditConroller.php, let's make function on Property.
            $is_alreadyAdd_news = false;
            if (
                $property->property_statuses_id == PropertyStatus::APPROVAL_PENDING ||
                $property->property_statuses_id == PropertyStatus::NOT_POSTED ||
                $property->property_statuses_id == PropertyStatus::FINISH_PUBLISH
            ) {

                if (
                    $property_data['property_statuses_id']  == PropertyStatus::PUBLISHED ||
                    $property_data['property_statuses_id']  == PropertyStatus::PULICATION_LIMITED
                ) {
                    $customer_ids = null;
                    if ( $property_data['property_statuses_id']  == PropertyStatus::PUBLISHED ) {
                        $customer_ids = null;
                    } elseif ( $property_data['property_statuses_id']  == PropertyStatus::PULICATION_LIMITED ){
                        $customer_ids = PropertyPublishingSetting::
                            where('properties_id', $property['id'])
                            ->pluck('customers_id')
                            ->toArray();

                        $customer_ids = CustomerFavoriteProperty::
                            whereIn('customers_id', $customer_ids)
                            ->where('properties_id', $property['id'])
                            ->pluck('customers_id')
                            ->toArray();

                        $notPublishcustomer_ids = CustomerFavoriteProperty::
                            whereNotIn('customers_id', $customer_ids)
                            ->where('properties_id', $property['id'])
                            ->pluck('customers_id')
                            ->toArray();

                        CustomerNew::storeNews($property, CustomerNew::PROPERTY_END, $notPublishcustomer_ids);
                    }
                    CustomerNew::storeNews($property, CustomerNew::ADD_PROPERTY, $customer_ids);
                    $is_alreadyAdd_news = true;
                    $property_data['publish_date'] = Carbon::now();
                }
            }


            if (
                $property['property_statuses_id'] == PropertyStatus::PUBLISHED ||
                $property['property_statuses_id'] == PropertyStatus::PULICATION_LIMITED
            ) {

                // if status change from 2(publish) to 3(Limited publish)
                if (
                    $property->property_statuses_id == PropertyStatus::PUBLISHED &&
                    $property_data['property_statuses_id'] == PropertyStatus::PULICATION_LIMITED &&
                    !$is_alreadyAdd_news
                ) {

                    $customer_ids = PropertyPublishingSetting::
                        where('properties_id', $property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    $customer_ids = CustomerFavoriteProperty::
                        whereIn('customers_id', $customer_ids)
                        ->where('properties_id', $property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    $notPublishcustomer_ids = CustomerFavoriteProperty::
                        whereNotIn('customers_id', $customer_ids)
                        ->where('properties_id', $property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    CustomerNew::storeNews($property, CustomerNew::PROPERTY_END, $notPublishcustomer_ids);
                    CustomerNew::storeNews($property, CustomerNew::PROPERTY_UPDATE, $customer_ids);
                    $is_alreadyAdd_news = true;
                }

                // if status change from 3(Limited publish) to 2(publish)
                if (
                    $property->property_statuses_id == PropertyStatus::PULICATION_LIMITED &&
                    $property_data['property_statuses_id'] == PropertyStatus::PUBLISHED &&
                    !$is_alreadyAdd_news
                ) {

                    $customer_ids = PropertyPublishingSetting::
                        where('properties_id', $property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    $customer_ids = CustomerFavoriteProperty::
                        whereIn('customers_id', $customer_ids)
                        ->where('properties_id', $property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    $addPublishcustomer_ids = CustomerFavoriteProperty::
                        whereNotIn('customers_id', $customer_ids)
                        ->where('properties_id', $property['id'])
                        ->pluck('customers_id')
                        ->toArray();

                    CustomerNew::storeNews($property, CustomerNew::ADD_PROPERTY, $addPublishcustomer_ids);
                    $is_alreadyAdd_news = true;
                }

                $property->fill($property_data);
                // if anything data is changed.
                if ($property->isDirty() &&
                    !$is_alreadyAdd_news &&
                    (
                        $property_data['property_statuses_id'] == PropertyStatus::PUBLISHED ||
                        $property_data['property_statuses_id'] == PropertyStatus::PULICATION_LIMITED
                    )
                ) {
                    CustomerNew::storeNews($property, CustomerNew::PROPERTY_UPDATE);
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
                    CustomerNew::storeNews($property, CustomerNew::PROPERTY_END);
                }
            }

            $property->fill($property_data);
            $property->save();

            $response = new \stdClass;
            $response->property = Property::with(['property_status', 'property_photos.file', 'property_flyers.file'])->findOrFail($request->id);
            $response->homeMaker = PropertyPublishingSetting::where([['properties_id', $request->id], ['type', 'home_maker']])->get();
            $response->realEstate = PropertyPublishingSetting::where([['properties_id', $request->id], ['type', 'real_estate']])->get();
            $response->status = 'success';
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (updateStatus Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function assetOrder(Request $request)
    {
        try {
            $response = new \stdClass;
            $uploads = ['photos', 'flyers'];
            foreach ($uploads as $group) if ($request->$group) {
                if ($group == 'photos') {
                    foreach ($request->$group as $key => $value) {
                        $propertyPhoto = PropertyPhoto::where('id', $value['id'])->first();
                        $propertyPhoto->sort_number = $value['order'];
                        $propertyPhoto->save();
                    }
                }
                if ($group == 'flyers') {
                    $propertyFlyer = PropertyFlyer::where('id', $value['id'])->first();
                    $propertyFlyer->sort_number = $value['order'];
                    $propertyFlyer->save();
                }
            }
            $propertyPhotos = PropertyPhoto::where('properties_id', $request->property)->get();
            $propertyFlyers = PropertyFlyer::where('properties_id', $request->property)->get();
            $response->photos = $propertyPhotos;
            $response->flyers = $propertyFlyers;
            $response->status = 'success';
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyDialogController (assetOrder Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function publishRange(Request $request)
    {
        // ------------------------------------------------------------------
        $propertyID = $request->id;
        $data = new \stdClass;
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

        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }
}
