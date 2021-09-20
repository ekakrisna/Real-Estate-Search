<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class Select2AjaxHelper
{
    public static function set($eloquent, $value = 'id', $display = 'display_name') {
        $request = Request::capture();
        if($request->has('q')) {
            $items = $eloquent->where($display, 'like', '%' . $request->q . '%')->paginate(10);
        } else {
            $items = $eloquent->paginate(10);
        }
        $result['items']    = $items;
        $result['value']    = $value;
        $result['display']  = $display;
        return response()->json($result);
    }
}
