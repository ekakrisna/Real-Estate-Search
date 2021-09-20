<?php

namespace App\Http\Controllers\Frontend\News;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $data = new \stdClass;
        $id = Auth::guard('user')->user()->id;                     
        // $data->customer_news  = Customer::with(['customer_news.property_deliveries.property.property_photos.file.property_photos',
        //     'customer_news.customer_news_lands.town',
        //     'customer_news.customer_news_lands.city',
        //     'customer_news.customer_news_lands.prefectures','customer_news'=> function($q) use($id){            
        //         $q->where([['customers_id', $id]]);
        //     }])->where('id',$id)->first();             

        $data->customer_news = CustomerNew::with(['property_deliveries.property.property_photos.file','customer','customer_news_property.property.property_photos.file'])->where( 'customers_id', $id )->orderBy('created_at','desc')->get();
        foreach($data->customer_news as $value){                
            foreach($value->customer as $customer){                    
                $value->is_show = 1;
                $value->save();                
            }                                  
        }            
        $data->title = __('label.front_news');                      
        return view( 'frontend.news.index', (array) $data );
    }

    // ----------------------------------------------------------------------
    // Page filters
    // ----------------------------------------------------------------------
    public function filter( Request $request ){        
        $response = new \stdClass;
        $response->status = 'success';        
        $filter = (object) $request->filter ?? null;            
        $id = Auth::guard('user')->user()->id;  
        // --------------------------------------------------------------
        // Process pagination
        // --------------------------------------------------------------
        $perpage = 10;
        $page = $filter->page ?? 1;
        // --------------------------------------------------------------
        $relations = collect(['property_deliveries.property.property_photos.file','customer','customer_news_property.property.property_photos.file']);
        // $query = Customer::with(['customer_news.property_deliveries.property.property_photos.file.property_photos',
        // 'customer_news.customer_news_lands.town',
        // 'customer_news.customer_news_lands.city',
        // 'customer_news.customer_news_lands.prefectures','customer_news'=> function($q) use($id,$perpage,$page){            
        //     $q->where([['customers_id', $id]]);
        // }])->where('id',$id);   

        $query = CustomerNew::where( 'customers_id', $id )->orderBy('created_at','desc');
                        
        $query->with( $relations->unique()->all());
        $paginator = $query->paginate( $perpage, ['*'], 'page', $page );
        // dd($paginator);
        $response->result = $paginator;                                    

        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
