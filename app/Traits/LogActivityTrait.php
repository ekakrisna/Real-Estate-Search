<?php

namespace App\Traits;

use App\Models\LogActivity;
use Carbon\Carbon;

trait LogActivityTrait
{
    /**
     * saving admin logging to database.
     * @param $activity
     * @param $detail
     * @param $admin_id
     */
    private function saveLog($activity, $detail, $current_admin_id){
        $log = new LogActivity();
        $log->admin_id = $current_admin_id;
        $log->activity = $activity;
        $log->detail = $detail;
        $log->ip = request()->ip();
        $log->access_time = Carbon::now();
        $log->save();
    }
}
