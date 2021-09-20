<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Models\Property;
use App\Models\PropertyLogActivity;
use App\Models\PropertyPublishingSetting;
use App\Models\CompanyUserTeam;
use App\Models\CompanyUserRole;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title']             = __('label.property_search');
        $data['page_type']              = 'detail';
        $data['search_property_url']    = route('manage.property.search');
        $data['property']               = null;
        $data['id'] = null;

        return view('manage.property.detail.detail', $data);
    }

    public function show($id)
    {
        $data['page_title']             = __('label.property_search');
        $data['page_type']              = 'detail';
        $with = ['property_photos.file', 'property_flyers.file', 'property_log_activities.property_scraping_type', 'property_publish'];
        $property                       = Property::with($with)
            ->whereIn('property_statuses_id', [2, 3])
            ->where('id', $id)->first();

        if ($property != null && $property->property_statuses_id == 3) {
            $property = $this->getProperty($with, $id);
        }
        $data['property']               = $property;
        $data['id']                     = $id;
        $data['search_property_url']    = route('manage.property.search');

        return view('manage.property.detail.detail', $data);
    }

    public function searchProperty(Request $request)
    {
        try {
            $with = ['property_photos.file', 'property_flyers.file', 'property_log_activities.property_scraping_type', 'property_publish'];
            $property   = Property::with($with)
                ->whereIn('property_statuses_id', [2, 3])
                ->where('id', $request->id)->first();

            if ($property != null && $property->property_statuses_id == 3) {

                $property = $this->getProperty($with, $request->id);
            }
            $response['property']   = $property;
            $response['url']        = route('manage.property.show', $request->id);
            return response()->json($response, 200);
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Manage/PropertyController (searchProperty Function), Error: ", $e);
            //------------------------------------------------------

            return $e;
        }
    }

    private function getProperty($with, $property_id)
    {
        $companyID = Auth::user()->companies_id;
        $companyUserID = Auth::user()->id;
        $company_user_role = Auth::user()->company_user_roles_id;

        $query = Property::where('property_statuses_id',  3)->where('id', $property_id)->with($with);
        switch ($company_user_role) {
                //===================================================
                // Get company user list from the same company
                //===================================================
            case CompanyUserRole::CORPORATE_MANAGER:
                $query =  $query->whereHas('property_publishing_setting', function (Builder $sale) use ($companyID, $companyUserID) {
                    $sale->where('companies_id', $companyID);
                });
                break;
                //===================================================

                //===================================================
                // Get company user list from the same company leader
                // with the leader itself
                //===================================================
            case CompanyUserRole::TEAM_MANAGER:
                $teamMemberList = CompanyUserTeam::where('leader_id', $companyUserID)->select('member_id')->get()->toArray();
                $teamMemberList[] = $companyUserID;
                $query =  $query->whereHas('property_publishing_setting', function (Builder $sale) use ($companyID, $teamMemberList) {
                    $sale->where('companies_id', $companyID)->whereIn('company_users_id', $teamMemberList);
                });
                break;
                //===================================================

                //===================================================
                // Get company user list from the same company user id (1 list only)
                //===================================================
            case CompanyUserRole::SALES_STAFF:
                $query =  $query->whereHas('property_publishing_setting', function (Builder $sale) use ($companyID, $companyUserID) {
                    $sale->where('companies_id', $companyID)->where('company_users_id', $companyUserID);
                });
                break;
                //===================================================
        }
        return $query->first();
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
}
