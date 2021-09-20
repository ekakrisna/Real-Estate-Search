<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Postcode;
use Illuminate\Http\Request;

class ApiPostcodeController extends Controller
{
    public function address($postcode)
    {
        $item = Postcode::where('postcode', $postcode)->first();
        $address = $item->prefecture . $item->city . $item->local;
        return response()->json(['status' => 200, 'address' => $address]);
    }
}
