<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Property;

use App\Http\Controllers\Controller;
use App\Helpers\FileHelper;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Customer;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// --------------------------------------------------------------------------
use App\Models\Property;
use App\Models\PropertyDelivery;
use App\Models\PropertyPublishingSetting;
use App\Models\PropertyConvertStatus;
use App\Models\PropertyStatus;
use App\Models\File;
use App\Models\PropertyFlyer as Flyer;
use App\Models\PropertyPhoto as Photo;

class PropertyController extends Controller
{
    public function index()
    {
        $data['page_title'] = __('label.list_of_properties');
        $data['property_statuses'] = PropertyStatus::all();

        return view('backend.property.index.index', $data);
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Property list filter
    // ----------------------------------------------------------------------
    public function filter(Request $request)
    {
        // ------------------------------------------------------------------
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default configuration
        // ------------------------------------------------------------------
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        $relations = collect(['property_status', 'property_photos.file', 'property_flyers.file', 'property_publish']);
        $query = Property::select('*')->OtherThanBackUp()->where(function ($query) {
            $query->where('property_convert_status_id', PropertyConvertStatus::SUCCESSFUL)
                ->orWhere('property_convert_status_id', PropertyConvertStatus::ALRADY_UPDATE);
        });

        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // View perpage
        // ------------------------------------------------------------------
        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum property update date
        // ------------------------------------------------------------------
        if (!empty($filter->updateDateStart)) {
            $query->whereDate('updated_at', '>=', $filter->updateDateStart);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum property update date
        // ------------------------------------------------------------------
        if (!empty($filter->updateDatEnd)) {
            $query->whereDate('updated_at', '<=', $filter->updateDatEnd);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property ID
        // ------------------------------------------------------------------
        if (!empty($filter->id)) {
            $id = $filter->id;
            $query->whereHas('property_publish', function ($q) use ($id) {
                $q->where('property_number', 'like', '%' . $id . '%');
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->priceMin) ? $filter->priceMin : '';
        $maxPrice = !empty($filter->priceMax) ? $filter->priceMax : '';

        $query->PriceRange($minPrice, $maxPrice);

        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->landAreaMin) ? $filter->landAreaMin : '';
        $maxLandArea = !empty($filter->landAreaMax) ? $filter->landAreaMax : '';

        $query->LandAreaRange($minLandArea, $maxLandArea);

        // ------------------------------------------------------------------
        // property building condition true
        // ------------------------------------------------------------------
        if (!empty($filter->buildingConditionYes) && empty($filter->buildingConditionNo)) {
            $query->where('building_conditions', '=', 1);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property building condition false
        // ------------------------------------------------------------------
        if (!empty($filter->buildingConditionNo) && empty($filter->buildingConditionYes)) {
            $query->where('building_conditions', '=', 0);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property building conditions
        // ------------------------------------------------------------------
        if (!empty($filter->propertyStatus)) {
            $query->where('property_statuses_id', '=', (int) $filter->propertyStatus);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property photo condition true
        // ------------------------------------------------------------------
        if (!empty($filter->photoTrue) && empty($filter->photoFalse)) {
            $query->whereHas('property_photos');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property photo condition false
        // ------------------------------------------------------------------
        if (!empty($filter->photoFalse) && empty($filter->photoTrue)) {
            $query->whereDoesntHave('property_photos');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property flyer condition true
        // ------------------------------------------------------------------
        if (!empty($filter->flyerTrue) && empty($filter->flyerFalse)) {
            $query->whereHas('property_flyers');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property flyer condition false
        // ------------------------------------------------------------------
        if (!empty($filter->flyerFalse) && empty($filter->flyerTrue)) {
            $query->whereDoesntHave('property_flyers');
        }

        // ------------------------------------------------------------------
        // property flyer condition true
        // ------------------------------------------------------------------
        if (!empty($filter->reportBugTrue) && empty($filter->reportBugFalse)) {
            $query->where('is_bug_report', 1);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property flyer condition false
        // ------------------------------------------------------------------
        if (!empty($filter->reportBugFalse) && empty($filter->reportBugTrue)) {
            $query->where(function ($query) {
                $query->whereNull('is_bug_report')
                    ->orWhere('is_bug_report', 0);
            });
        }

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // locaiton search
        // ------------------------------------------------------------------
        if (!empty($filter->location)) {
            // --------------------------------------------------------------
            $query->where(function ($query) use ($filter) {
                // ----------------------------------------------------------
                $keywords = preg_replace('/\s+/', ' ', $filter->location);
                $keywords = explode(' ', trim($keywords));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search location
                // ----------------------------------------------------------
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->orWhere('location', 'LIKE', "%{$keyword}%");
                    }
                });
                // ----------------------------------------------------------


                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = [
            'updated_at', 'id', 'location', 'minimum_price', 'minimum_land_area',
            'property_status', 'building_conditions_desc', 'photo', 'flyer'
        ];

        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            // --------------------------------------------------------------
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Order by
            // --------------------------------------------------------------
            if ('status' == $filter->order) $order = 'is_active';
            else $order = $filter->order; // Everything else
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Relation based order
            // --------------------------------------------------------------
            $relationBasedOrders = ['property_status'];
            if (in_array($filter->order, $relationBasedOrders)) {
                // ----------------------------------------------------------
                // Order user by the PropertyStatus table
                // ----------------------------------------------------------
                if ('property_status' === $filter->order) {
                    $query->orderBy(
                        PropertyStatus::select('label')
                            ->whereColumn('property_statuses.id', 'properties.property_statuses_id'),
                        $direction
                    );
                }
                // ----------------------------------------------------------

            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // property photo order
            // --------------------------------------------------------------
            else if ($order == 'photo') {
                $query = $query->withCount('property_photos')
                    ->orderBy('property_photos_count', $direction);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // property flyer order
            // --------------------------------------------------------------
            else if ($order == 'flyer') {
                $query = $query->withCount('property_flyers')
                    ->orderBy('property_flyers_count', $direction);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Regular order
            // --------------------------------------------------------------
            else if ($order) $query = $query->orderBy($order, $direction);
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Start date filter
        // ------------------------------------------------------------------
        if (!empty($filter->start)) {
            $start = Carbon::parse($filter->start);
            $query = $query->whereDate('created_at', '>=', $start);
        }
        // ------------------------------------------------------------------
        // End date filter
        // ------------------------------------------------------------------
        if (!empty($filter->end)) {
            $end = Carbon::parse($filter->end);
            $query = $query->whereDate('created_at', '<=', $end);
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Query debugging
        // ------------------------------------------------------------------
        // dd( $query->toSql(), $query->getBindings());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        $query->with($relations->unique()->all());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    public function show($id)
    {
        $data['page_title']     = __('label.details_of_properties');
        $data['page_type']      = 'detail';
        $data['property']       = Property::with(['property_photos.file', 'property_flyers.file'])->findOrFail($id);

        return view('backend.property.detail.detail', $data);
    }

    public function propertyLogActivityFilter(Request $request, $id)
    {
        // ------------------------------------------------------------------
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default configuration
        // ------------------------------------------------------------------
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        $query = PropertyLogActivity::where('properties_id', $id);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // View perpage
        // ------------------------------------------------------------------
        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum Property Created_at
        // ------------------------------------------------------------------
        if (!empty($filter->min)) {
            $query->whereDate('created_at', '>=', $filter->min);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum Property Created_at
        // ------------------------------------------------------------------
        if (!empty($filter->max)) {
            $query->whereDate('created_at', '<=', $filter->max);
        }
        // ------------------------------------------------------------------
        // WhereBetween and WhereDate Created_at
        // ------------------------------------------------------------------
        if (!empty($filter->max) && !empty($filter->min)) {
            if ($filter->max == $filter->min) {
                $query->whereDate('created_at', '=', $filter->max);
            }
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // General search
        // ------------------------------------------------------------------
        if (!empty($filter->search)) {
            // --------------------------------------------------------------
            $query->where(function ($query) use ($filter) {
                // ----------------------------------------------------------
                $keywords = preg_replace('/\s+/', ' ', $filter->search);
                $keywords = explode(' ', trim($keywords));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search user
                // ----------------------------------------------------------
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->orWhere('created_at', 'LIKE', "%{$keyword}%");
                        $query->orWhere('before_update_text', 'LIKE', "%{$keyword}%");
                        $query->orWhere('after_update_text', 'LIKE', "%{$keyword}%");
                    }
                });
                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['created_at', 'before_update_text', 'after_update_text'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            // --------------------------------------------------------------
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Order by
            // --------------------------------------------------------------
            $order = $filter->order; // Everything else
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            if ($order) $query = $query->orderBy($order, $direction);
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        // $query->with( $relations->unique()->all());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }

    public function delivery($id_property)
    {
        $data['item']       = new Property();
        $data['page_title'] = __('label.deliver_property_information');
        $data['form_action'] = route('admin.property.delivery.save', $id_property);
        $data['page_type']  = 'create';


        //get options for company with role 1
        $data['item']->company_options  = Company::where('company_roles_id', 1)->pluck('company_name', 'id');
        //Get first value of Company and make it in option value for town
        $first_company = Company::first();
        $id_first_company = $first_company->id;
        $data['item']->companyuser_options  = User::where('companies_id', $id_first_company)->pluck('name', 'id');

        //get options for company with role 2
        $data['item']->company_options2  = Company::where('company_roles_id', 2)->pluck('company_name', 'id');
        //Get first value of Company and make it in option value for town
        $first_company2 = Company::first();
        $id_first_company2 = $first_company2->id;
        $data['item']->companyuser_options2  = User::where('companies_id', $id_first_company2)->pluck('name', 'id');


        //Get options for customer
        $data['item']->customer_options  = Customer::pluck('name', 'id');

        return view('backend.property.delivery', $data);
    }

    //FUNCTION FOR SAVE ALL FORM DATA FROM DELIVERY
    public function save_delivery($id_property, Request $request)
    {
        try {
            $data = $request->all();
            $delivery = new PropertyDelivery();
            $delivery->properties_id = $id_property;
            $delivery->subject = $request->subject;
            $delivery->text = $request->text;
            $delivery->favorite_registered_area = $request->favorite_registered_area;
            $delivery->exclude_received_over_three = $request->exclude_received_over_three;
            $delivery->exclude_customers_outside_the_budget = $request->exclude_customers_outside_the_budget;
            $delivery->exclude_customers_outside_the_desired_land_area = $request->exclude_customers_outside_the_desired_land_area;
            $delivery->created_at = Carbon::now();

            if ($delivery->save()) {

                //INSERT CUSTOMER COMPANY AND USER TO PUBLISH SETTING
                $delivery_id = $delivery->id;
                $companies_id = $data['companies_id'];
                $counter = 0;
                foreach ($companies_id as $company_id) {
                    $user_id = $data['companies_users_id'][$counter];
                    $customer_id = $data['customers_id'][$counter];

                    //INSERT TO PUBLISH SETTING
                    $publishset = new PropertyPublishingSetting();
                    $publishset->companies_id = $company_id;
                    $publishset->company_users_id = $user_id;
                    $publishset->customers_id = $customer_id;
                    $publishset->save();

                    //INSERT TO CUSTOMER DELIVERIES INFO
                    $deliveryinfo = new CustomerDeliveriesInfo();
                    $deliveryinfo->property_deliveries_id = $delivery_id;
                    $deliveryinfo->customers_id = $customer_id;
                    $deliveryinfo->type = 1;
                    $deliveryinfo->created_at = Carbon::now();
                    $deliveryinfo->save();
                    $counter++;
                }
                //INSERT CUSTOMER COMPANY AND USER TO PUBLISH SETTING
                $companies_id2 = $data['companies_id2'];
                $counter2 = 0;
                foreach ($companies_id2 as $company_id2) {
                    $user_id2 = $data['companies_users_id2'][$counter2];
                    $customer_id2 = $data['customers_id2'][$counter2];

                    $publishset2 = new PropertyPublishingSetting();
                    $publishset2->companies_id = $company_id2;
                    $publishset2->company_users_id = $user_id2;
                    $publishset2->customers_id = $customer_id2;
                    $publishset2->save();

                    //INSERT TO CUSTOMER DELIVERIES INFO
                    $deliveryinfo2 = new CustomerDeliveriesInfo();
                    $deliveryinfo2->property_deliveries_id = $delivery_id;
                    $deliveryinfo2->customers_id = $customer_id2;
                    $deliveryinfo2->type = 1;
                    $deliveryinfo2->created_at = Carbon::now();
                    $deliveryinfo2->save();
                    $counter2++;
                }

                return redirect()->route('admin.property.delivery', $id_property)->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            }
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Backend/PropertyController (save_delivery Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Backend/PropertyController (save_delivery Function), Error: ", $e);
            //------------------------------------------------------
            //dd($e);
        }
    }

    function uploadPhoto(Request $request, $property_id)
    {
        $file = $request->file("file");
        for ($i = 0; $i < count($file); $i++) {
            $newImage[] = self::upload($file[$i], "photos", $property_id);
        }
        return response()->json($newImage);
    }

    function deletePhoto(Request $request, $property_id)
    {
        return self::deleteImages($request->ids, "photos", $property_id);
    }
    function orderPhoto(Request $request, $property_id)
    {
        return self::orderImage($request->id, $request->action, "photos", $property_id);
    }

    function uploadFlyer(Request $request, $property_id)
    {
        $file = $request->file("file");
        for ($i = 0; $i < count($file); $i++) {
            $newImage[] = self::upload($file[$i], "flyers", $property_id);
        }
        return response()->json($newImage);
    }

    function deleteFlyer(Request $request, $property_id)
    {
        return self::deleteImages($request->ids, "flyers", $property_id);
    }
    function orderFlyer(Request $request, $property_id)
    {
        return self::orderImage($request->id, $request->action, "flyers", $property_id);
    }

    private function upload($file, $group, $property_id)
    {
        // ------------------------------------------------------
        // Process the file upload
        // ------------------------------------------------------
        $property = Property::findOrFail($property_id);
        $lastSort = $group == 'photos' ? $property->last_photo_sort_number++ : $property->last_flyer_sort_number++;
        // ------------------------------------------------------
        $file = File::uploadFile($file, $property_id, $group);
        // ------------------------------------------------------
        if ('photos' == $group) {
            $fileable = array(
                'file_id' => $file->id,
                'properties_id' => $property->id,
                'sort_number' => $lastSort + 1,
            );
            $newImage = Photo::create($fileable);
        } elseif ('flyers' == $group) {
            $fileable = array(
                'file_id' => $file->id,
                'properties_id' => $property->id,
                'sort_number' => $lastSort + 1,
            );
            $newImage = Flyer::create($fileable);
        }
        // ------------------------------------------------------
        $return = $newImage;
        $return['file'] = $file;
        return $return;
    }

    private function deleteImages($ids, $group, $property_id)
    {
        $ids = json_decode($ids);
        $property = Property::findOrFail($property_id);
        $images = $group == "photos" ? $property->property_photos() : $property->property_flyers();
        $images->find($ids)->each(function ($image, $key) {
            $file = $image->file;
            // Delete file
            $filename = '/properties/' . $file->name . '.' . $file->extension;
            Storage::delete($filename);

            // Delete data, parent first
            $image->delete();
            $file->delete();
        });
    }

    private function orderImage($id, $action, $group, $property_id)
    {
        $property = Property::findOrFail($property_id);
        $current_image = $group == "photos" ? $property->property_photos() : $property->property_flyers();
        $current_image = $current_image->find($id);

        $current_image_sort = $current_image->sort_number;

        $other_images = $group == "photos" ? $property->property_photos() : $property->property_flyers();
        if ($action == "next") {
            $next_image = $other_images->where('sort_number', '>', $current_image->sort_number)->orderBy('sort_number')->first();
            $next_image_sort = $next_image->sort_number;

            $current_image->update(['sort_number' => $next_image_sort]);
            $next_image->update(['sort_number' => $current_image_sort]);

            $return["other"] = [
                "id" => $next_image->id,
                "sort_number" => $next_image->sort_number
            ];
            $return["type"] = "next";
        } else if ($action == "prev") {
            $prev_image = $other_images->where('sort_number', '<', $current_image->sort_number)
                ->orderBy('sort_number', 'desc')->first();
            $prev_image_sort = $prev_image->sort_number;

            $current_image->update(['sort_number' => $prev_image_sort]);
            $prev_image->update(['sort_number' => $current_image_sort]);

            $return["other"] = [
                "id" => $prev_image->id,
                "sort_number" => $prev_image->sort_number
            ];
            $return["type"] = "prev";
        }

        $return["current"] = [
            "id" => $current_image->id,
            "sort_number" => $current_image->sort_number
        ];

        return $return;
    }


    public function orderImages(Request $request)
    {
        if (!empty($request->type) && !empty($request->items)) {
            try {
                $type = $request->type;
                $isFlyerType = 'flyer' === $type;

                $items = collect($request->items);
                $idList = $items->map(function ($item) {
                    $item = (object) $item;
                    return $item->id;
                });

                $model = Photo::class;
                if ($isFlyerType) $model = Flyer::class;

                $images = $model::whereIn('id', $idList)->get();
                if (!$images->isEmpty()) foreach ($images as $image) {
                    $item = (object) $items->firstWhere('id', $image->id);
                    if (!empty($item->order)) {
                        $image->sort_number = $item->order;
                        $image->save();
                    }
                }

                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                //------------------------------------------------------
                //Send chat to chatwork if failing in function
                //------------------------------------------------------
                Log::info(Carbon::now() . " - Backend/PropertyController (orderImages Function), Error: " . $e->getMessage());
                sendMessageOfErrorReport("Backend/PropertyController (orderImages Function), Error: ", $e);
                //------------------------------------------------------
                return response()->json(['status' => 'error']);
            }
        }
    }
}
