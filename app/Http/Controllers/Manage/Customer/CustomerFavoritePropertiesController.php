<?php

namespace App\Http\Controllers\Manage\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerFavoriteProperty;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

class CustomerFavoritePropertiesController extends Controller
{
    public function index($id)
    {
        $data = new \stdClass;
        $data->page_title = __('label.list_favorite_properties'); 
        $data->id = $id;       
        $data->customer = Customer::with('company_user.company')->where('id', $id)->first();
        return view('manage.customer.favorite_properties.index', (array) $data);  
    }

    public function show($param)
    {
        // if ($param == 'json') {
        //     $model = Customer::with('company_user')->orderBy('id', 'DESC')->get();
        //     return (new DatatablesHelper)->instance($model, true, true, null, ['current_nest' => 'fav_history', 'style' => 'success', 'icon' => 'users'])->toJson();
        // }
        // abort(404);
    }

    public function favfilter($id, Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');
        // Default configuration
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        // Base query
        $relations = collect(['customer', 'property']);
        $query = CustomerFavoriteProperty::select('*')->where('customers_id', $id);
        // Add the relations, make sure they are unique
        // $query->with($relations);

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // Minimum - Max date filter        
        if (!empty($filter->datestart)) {
            $query->whereDate('created_at', '>=', $filter->datestart);
        }
        if (!empty($filter->datefinish)) {
            $query->whereDate('created_at', '<=', $filter->datefinish);
        }

        // ------------------------------------------------------------------
        // price filter
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->minprice)?$filter->minprice:'';
        $priceMax = !empty($filter->maxprice)?$filter->maxprice:'';

        $query->whereHas('property', function(Builder $sale) use($minPrice, $priceMax){
            $sale->priceRange($minPrice, $priceMax);
        });

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // land area filter
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->minland)?$filter->minland:'';
        $maxLandArea = !empty($filter->maxland)?$filter->maxland:'';

        $query->whereHas('property', function(Builder $sale) use($minLandArea, $maxLandArea){
            $sale->LandAreaRange($minLandArea, $maxLandArea);
        });
        // ------------------------------------------------------------------

        $orders = [ 'property_id', 'location', 'created_at', 'building', 'selling', 'land_area'];        
        if( !empty( $filter->order ) && in_array( $filter->order, $orders )){
            $order = null;
            $direction = $filter->direction ?? 'asc';
            $order = $filter->order;
            if($order == 'property_id'){
                $query = $query->with('property')
                                ->orderBy('id', $direction);
            } elseif($order == 'location'){
                $query = $query->orderBy( 
                    Property::select( 'location' )
                        ->whereColumn( 'properties.id', 'customer_favorite_properties.properties_id' ),
                    $direction
                );
            } elseif($order == 'building'){
                $query = $query->orderBy( 
                    Property::select( 'building_conditions_desc' )
                        ->whereColumn( 'properties.id', 'customer_favorite_properties.properties_id' ),
                    $direction
                );                                                                                                
            } elseif($order == 'selling'){
                $query = $query->orderBy( 
                    Property::select( 'minimum_price' )
                        ->whereColumn( 'properties.id', 'customer_favorite_properties.properties_id' ),
                    $direction
                )->orderBy( 
                    Property::select( 'maximum_price' )
                        ->whereColumn( 'properties.id', 'customer_favorite_properties.properties_id' ),
                    $direction
                );      
            } elseif($order == 'land_area'){
                $query = $query->orderBy( 
                    Property::select( 'minimum_land_area' )
                        ->whereColumn( 'properties.id', 'customer_favorite_properties.properties_id' ),
                    $direction
                )->orderBy( 
                    Property::select( 'maximum_land_area' )
                        ->whereColumn( 'properties.id', 'customer_favorite_properties.properties_id' ),
                    $direction
                );      
            } elseif( $order ) {
                $query = $query->orderBy( $order, $direction );
            }
            // --------------------------------------------------------------
        }        

        if (!empty($filter->location)) {
            $location = $filter->location;
            $query->whereHas('property', function (Builder $sale) use ($location) {
                $sale->where('location','LIKE', "%{$location}%");
            });                         
        }

        if (!empty($filter->build)) {
            $building = $filter->build;
            $query->whereHas('property', function (Builder $sale) use ($building) {
                $sale->where('building_conditions_desc','LIKE', "%{$building}%");
            });                         
        }
        // Paginate the result
        $query->with($relations->unique()->all());
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }

    public function changeFlag($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->flag = !$customer->flag;
        if ($customer->flag) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $customer->save();
        return response()->json(['status' => 200, 'flag' => $flag]);
    }
}
