<?php

namespace App\Http\Controllers\Backend\Company\User;

use App\Models\CompanyUser;
use Illuminate\Http\Request;
use App\Models\CompanyUserTeam;
use App\Models\Company;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyUserPermissionController extends Controller
{
    public function index($companyID)
    {
        $data = new \stdClass;
        $userID = Auth::user()->id;

        $data->company_id = $companyID;
        $data->user_id = $userID;
        $data->page_title = __('label.set_user_permission');
        return view('backend.company.user.permission.index', (array) $data);
    }

    public function detail($companyID, Request $request)
    {
        $company = Company::find($companyID);
        if ($company === null) {
            return;
        }
        if ($company->company_roles_id === 1) {
            return;
        }

        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        $query = CompanyUser::select('*')->with(["company_user_teams_leader.company_user"])->where([
            ['company_users.companies_id', '=', $companyID],
            ['company_users.company_user_roles_id', '=', '2'],
        ]);

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // Result order
        $orders = ['team_leader', 'email_leader', 'team_member', 'email_member'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = $filter->order;
            $direction = $filter->direction ?? 'asc';

            if ($order == 'team_leader') {
                $query = $query->orderBy('name', $direction);
            } elseif ($order == 'email_leader') {
                $query = $query->orderBy('email', $direction);
            }
        }
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }

    public function importCsv($companyID, Request $request)
    {
        if ($request->ajax()) {
            // PROSES READ CSV FILE
            $leader_id = $request->leader_id;
            $file = $request->file('file');
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $dataFile = $data;
                }
                fclose($handle);
            } else {
                $dataFile = Null;
            }
            // PROSES READ CSV FILE
            // ------------------------------------------------
            // PROSES READ CSV FILE TO DATABASE
            foreach (array_unique($dataFile) as $companyUser) {
                $query[] = CompanyUser::select('*')->where([
                    ['email', '=', $companyUser],
                    ['companies_id', '=', $companyID],
                ])->get();
            }
            // PROSES READ CSV FILE TO DATABASE
            // ------------------------------------------------
            // PROSES CHECK CONDITION LEADER ID
            $leaderCheck = CompanyUserTeam::with('company_user')->where([
                ['leader_id', '=', $leader_id],
            ]);
            // dd($query[0]->get());
            // PROSES CHECK CONDITION LEADER ID
            // ------------------------------------------------
            // PROSES CHECK CONDITION LEADER ID IF LEADER ID NOT NULL
            if ($leaderCheck->count() != 0 && count($query) > 1) {
                // ------------------------------------------------
                // PROSES GET DATA LEADER ID
                foreach ($leaderCheck->get() as $key => $value) {
                    $user['id'][] = $value->company_user->id;
                }
                // PROSES GET DATA LEADER ID
                // ------------------------------------------------
                // PROSES CHECK READ FILE TO LEADER ID
                foreach ($query as $key => $dataUsers) {
                    if ($dataUsers->count() != 0) {
                        foreach ($dataUsers as $dataUser) {
                            if ($leader_id != $dataUser->id && $dataUser->company_user_roles_id == 3) {
                                $dataCheck['company_user_id'][] = $dataUser->id;
                            }
                        }
                    } else {
                        // foreach ($dataUsers as $dataUser) {
                        $dataCheck['company_user_id'][] = null;
                        // }
                    }
                }
                // PROSES CHECK READ FILE TO LEADER ID
                // ------------------------------------------------
                // PROSES SORT DATA
                foreach ($dataCheck['company_user_id'] as $key => $value) {
                    if ($value != null) {
                        sort($dataCheck['company_user_id']);
                        sort($user['id']);
                    }
                }
                // PROSES SORT DATA
                // ------------------------------------------------
                // PROSES CHECK IF ID COMPANY_USER AND MEMBER COMPANY_USER_TEAM
                // if ($dataCheck['company_user_id'] != $user['id']) {
                foreach ($query as $key => $dataUsers) {
                    if ($dataUsers->count() != 0) {
                        foreach ($dataUsers as $dataUser) {
                            if ($leader_id != $dataUser->id && $dataUser->company_user_roles_id == 3) {
                                $data['name'][] = $dataUser->name;
                                $data['email'][] = $dataUser->email;
                                $data['company_user_id'][] = $dataUser->id;
                            }
                        }
                    } else {
                        foreach ($dataUsers as $dataUser) {
                            $data['name'] = null;
                            $data['email'] = null;
                            $data['company_user_id'] = null;
                        }
                    }
                }
                // }
                // PROSES CHECK IF ID COMPANY_USER AND MEMBER COMPANY_USER_TEAM
                // ------------------------------------------------
            } else {
                foreach ($query as $key => $dataUsers) {
                    if ($dataUsers->count() != 0) {
                        foreach ($dataUsers as $dataUser) {
                            $companyCheck = CompanyUserTeam::with('company_user')->where([
                                ['leader_id', '=', $leader_id],
                                ['member_id', '=', $dataUser->id],
                            ])->count();
                            if ($companyCheck === 0 && $leader_id != $dataUser->id && $dataUser->company_user_roles_id == 3) {
                                $data['name'][] = $dataUser->name;
                                $data['email'][] = $dataUser->email;
                                $data['company_user_id'][] = $dataUser->id;
                            }
                        }
                    } else {
                        foreach ($dataUsers as $dataUser) {
                            $data['name'] = null;
                            $data['email'] = null;
                            $data['company_user_id'] = null;
                        }
                    }
                }
            }
            // PROSES CHECK CONDITION LEADER ID
            // ------------------------------------------------
            return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function addTeam($companyID, Request $request)
    {
        try {
            $response = new \stdClass;
            $member_id = $request->member_id;
            $leader_id = $request->leader_id;
            if ($member_id == null && $leader_id == null) {
                return response()->json('failed', 200, [], JSON_NUMERIC_CHECK);
            } else {
                $leaderCheck = CompanyUserTeam::where([['leader_id', '=', $leader_id]]);
                if ($leaderCheck->count() != 0) {
                    foreach ($leaderCheck->get() as $key => $value) {
                        $user['id'][] = $value->company_user->id;
                    }
                    sort($user['id']);
                    sort($member_id);
                    if ($user['id'] != $member_id) {
                        $companyCheck = CompanyUserTeam::with('company_user')->where([
                            ['leader_id', '=', $leader_id],
                        ])->delete();
                        if ($companyCheck) {
                            foreach ($member_id as $input) {
                                $contractFile = new CompanyUserTeam();
                                $contractFile->fill([
                                    'leader_id'   => $leader_id,
                                    'member_id'   => $input,
                                    'create_at'   => Carbon::now(),
                                    'upadate_at'  => Carbon::now(),
                                ])->save();
                            }
                        }
                    }
                } else {
                    foreach ($member_id as $input) {
                        $contractFile = new CompanyUserTeam();
                        $contractFile->fill([
                            'leader_id'   => $leader_id,
                            'member_id'   => $input,
                            'create_at'   => Carbon::now(),
                            'upadate_at'  => Carbon::now(),
                        ])->save();
                    }
                }
                $response->data = CompanyUser::select('*')->with(["company_user_teams_leader.company_user"])->where([
                    ['company_users.companies_id', '=', $companyID],
                    ['company_users.company_user_roles_id', '=', '2'],
                ])->get();
                $response->success = "success";
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Backend/CompanyUserPermissionController (addTeam Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Backend/CompanyUserPermissionController (addTeam Function), Error: ", $e);
            //------------------------------------------------------
            //dd($e);
        }
    }
}
