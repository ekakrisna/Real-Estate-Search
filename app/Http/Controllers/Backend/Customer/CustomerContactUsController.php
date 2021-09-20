<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Customer;
use App\Models\CustomerContactUs;
use App\Models\PropertyLogActivity;

class CustomerContactUsController extends Controller
{
    protected function validator( array $data, $type ){
        return Validator::make($data, [
            'is_finish'         => 'required',
            'person_in_charge'  => 'required|string|max:45',
            'note'              => 'required|string',
        ]);
    }

    public function detail($contact)
    {
        $data['page_title']     = __('label.inquiry_detail');

        // B13-2 SECTION
        $data['customer_contact_us'] = CustomerContactUs::with('property.property_photos.file', 'property.property_flyers.file', 'property.property_log_activities.property_scraping_type')
        ->findOrFail($contact);

        // B13-1 SECTION
        $user = $data['customer_contact_us']->customers_id;
        if ($user == null) {            
            $data['customer']       = factory( Customer::class )->state('init')->make();
        }else {
            $data['customer']       = Customer::with('company_user.company')->findOrFail($user);
        }

        $data['form_action']        = route('admin.contact.update', $contact);

        $data['page_type']      = 'edit';

        return view('backend.customer.contact_detail.index', $data);
    }

    public function update(Request $request, $contact)
    {
        $data               = $request->all();
        if($data['is_finish'] == "未対応" || $data['is_finish'] == "Not compatible" ) {
            $data['is_finish'] = 0;
        } else {
            $data['is_finish'] = 1;
        }

        $this->validator($data, 'update')->validate();

        $edit = CustomerContactUs::find($contact);

        $edit->update($data);

        return redirect()->route('admin.contact.detail', $contact)->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
    }
}
