<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Request;

use Illuminate\Support\Facades\Log;
use App\Models\CompanyUser;

/**
 * Class CompanyUserLogActivity
 *
 * @property int $id
 * @property int|null $company_users_id
 * @property string $activity
 * @property string $detail
 * @property string $ip
 * @property Carbon $access_time
 *
 * @property CompanyUser|null $company_user
 *
 * @package App\Models
 */
class CompanyUserLogActivity extends Model
{
    const BE_CREATED = 1;
    const LOGIN = 2;
    const VIEW_PROPERTY = 4;
    const BE_STOPPED = 7;
    const CHANGE_USER_INFO = 9;
    const CREATE_NEW_CUSTOMER = 10;

    protected $table = 'company_user_log_activities';
    protected $appends = ['url', 'ja'];
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'company_users_id' => 'int'
    ];

    protected $dates = [
        'access_time'
    ];

    protected $fillable = [
        'company_users_id',
        'activity',
        'detail',
        'ip',
        'access_time'
    ];

    public function company_user()
    {
        return $this->belongsTo(CompanyUser::class, 'company_users_id');
    }


    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute()
    {
        $result = new \stdClass;
        $format = "Y年m月d日 h:i";
        if (!empty($this->access_time)) $result->access_time = Carbon::parse($this->access_time)->format($format);
        return $result;
    }

    // ----------------------------------------------------------------------
    // @TODO: Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute()
    {
        if (empty($this->id)) return null;
        $getcompanyid = CompanyUser::where('id', $this->company_users_id)->first();
        $url = new \stdClass;
        $url->edit = '#';
        $url->view = '#';
        $url->detail = route('admin.company.user.edit', [$getcompanyid->companies_id, $this->company_users_id]);
        $url->list = route('admin.company.user.list', $getcompanyid->companies_id);
        return $url;
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // @ Function to store process of companyUserLogActivity
    // ----------------------------------------------------------------------
    public static function storeCompanyUserLog($companyUserOrm, $actionType, $data)
    {
        try {
            //------------------------------------------------------
            //Create explanation for "detail" column
            //------------------------------------------------------
            $logDetail = null;
            switch ($actionType) {
                case CompanyUserLogActivity::BE_CREATED:
                    $logDetail = "Created By: " . $companyUserOrm->name . " - User ID:" . $data->id . ", User Name:" . $data->name;
                    break;

                case CompanyUserLogActivity::LOGIN:
                    $logDetail = "Ip Address: " . Request::ip();
                    break;

                case CompanyUserLogActivity::VIEW_PROPERTY:
                    //
                    break;

                case CompanyUserLogActivity::BE_STOPPED:
                    $logDetail = "-";
                    break;

                case CompanyUserLogActivity::CHANGE_USER_INFO:
                    //generate text "before and after of all changed column"
                    $logDetail = "Before Update: " . json_encode($data['before']) . ", After Update: " . json_encode($data['after']);
                    break;

                case CompanyUserLogActivity::CREATE_NEW_CUSTOMER:
                    //ID: {customer id} 名前: {customer name} メール: {customer's email} 担当者: {customer's in charge(company user name with company_users_id)}
                    $logDetail = "ID: " . $data['id'] . ", 名前: " . $data['name'] . ", メール: " . $data['email'] . ", 担当者: " . $data['company_user']['name'] . "(" . $data['company_users_id'] . ")";
                    break;

                default:
                    return;
            }
            //------------------------------------------------------

            //------------------------------------------------------
            //Storing Process of Company User Log Activity
            //------------------------------------------------------
            $actiontype = ActionType::find($actionType);

            $CompanyUserLogActivity = CompanyUserLogActivity::create([
                'company_users_id' => $companyUserOrm->id,
                'activity' => $actiontype->label,
                'detail' => $logDetail,
                'ip' => Request::ip(),
                'access_time' => Carbon::now(),
            ]);

            //------------------------------------------------------
            // SAVE LOG INFO
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Created Company User Log Activity: ", ['id' => $CompanyUserLogActivity->id, 'company_users_id' => $companyUserOrm->id, 'activity' => $actiontype->label, 'detail' => $logDetail]);
            //------------------------------------------------------

        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Failed Store Company User Log Activity: ", ['error' => $e->getMessage()]);
            //sendMessageOfErrorReport("Failed Store Company User Log Activity: ", $e );
            //------------------------------------------------------
        }
    }
}
