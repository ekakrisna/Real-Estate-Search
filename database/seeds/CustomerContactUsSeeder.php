<?php

use Carbon\Carbon;
use App\Models\CustomerContactUs;
use Illuminate\Database\Seeder;

class CustomerContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        CustomerContactUs::truncate();
        $CustomerContactUs = new CustomerContactUs();
        $CustomerContactUs->insert([
            [
                'id'                    =>  1,
                'customers_id'          =>  1,
                'properties_id'         =>  1,
                'contact_us_types_id'   =>  1,
                'subject'               =>  'dummy how to?',
                'text'                  =>  'Lorizzle pot dolor sit amizzle, im in the shizzle adipiscing elit. Shit sapien velizzle, volutpizzle, suscipit quis, gravida vizzle, arcu. Pellentesque pizzle tortizzle. Sizzle erizzle. Dawg owned phat dapibus thats the shizzle tempizzle shizzle my nizzle crocodizzle. Dawg pellentesque nibh et turpizzle. Fizzle izzle tortor. Pellentesque eleifend rhoncizzle pimpin. In break it down yippiyo platea dictumst. Away dapibus. Curabitur tellus urna, pretizzle eu, mattis ac, eleifend vitae, nunc. Stuff check out this. Shit mofo velizzle sizzle owned.',
                'flag'                  =>  1,
                'is_finish'             =>  0,
                'person_in_charge'      =>  null,
                'note'                  =>  null,
                'name'                  =>  'dummy confused sender',
                'email'                 =>  'confused@sender.com',
                'company_name'          =>  'dummy first company',
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  2,
                'customers_id'          =>  2,
                'properties_id'         =>  2,
                'contact_us_types_id'   =>  101,
                'subject'               =>  'forgot my email!',
                'text'                  =>  'Etizzle im in the shizzle urna hizzle nisl., im in the shizzle adipiscing elit. Shit sapien velizzle, volutpizzle, suscipit quis, gravida vizzle, arcu. Pellentesque pizzle tortizzle. Sizzle erizzle. Dawg owned phat dapibus thats the shizzle tempizzle shizzle my nizzle crocodizzle. Dawg pellentesque nibh et turpizzle. Fizzle izzle tortor. Pellentesque eleifend rhoncizzle pimpin. In break it down yippiyo platea dictumst. Away dapibus. Curabitur tellus urna, pretizzle eu, mattis ac, eleifend vitae, nunc. Stuff check out this. Shit mofo velizzle sizzle owned.',
                'flag'                  =>  1,
                'is_finish'             =>  0,
                'person_in_charge'      =>  null,
                'note'                  =>  null,
                'name'                  =>  'dummy paniced sender',
                'email'                 =>  'paniced@sender.com',
                'company_name'          =>  'dummy second company',
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  3,
                'customers_id'          =>  3,
                'properties_id'         =>  3,
                'contact_us_types_id'   =>  901,
                'subject'               =>  'property inquiry!',
                'text'                  =>  'Lorem urna hizzle nisl., im in the shizzle adipiscing elit. Shit sapien velizzle, volutpizzle, suscipit quis, gravida vizzle, arcu. Pellentesque pizzle tortizzle. Sizzle erizzle. Dawg owned phat dapibus thats the shizzle tempizzle shizzle my nizzle crocodizzle. Dawg pellentesque nibh et turpizzle. Fizzle izzle tortor. Pellentesque eleifend rhoncizzle pimpin. In break it down yippiyo platea dictumst. Away dapibus. Curabitur tellus urna, pretizzle eu, mattis ac, eleifend vitae, nunc. Stuff check out this. Shit mofo velizzle sizzle owned.',
                'flag'                  =>  0,
                'is_finish'             =>  0,
                'person_in_charge'      =>  null,
                'note'                  =>  null,
                'name'                  =>  'dummy sender',
                'email'                 =>  'dummy@sender.com',
                'company_name'          =>  'dummy thrid company',
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  4,
                'customers_id'          =>  3,
                'properties_id'         =>  3,
                'contact_us_types_id'   =>  1,
                'subject'               =>  'dummy how to',
                'text'                  =>  'How to urna hizzle nisl., im in the shizzle adipiscing elit. Shit sapien velizzle, volutpizzle, suscipit quis, gravida vizzle, arcu. Pellentesque pizzle tortizzle. Sizzle erizzle. Dawg owned phat dapibus thats the shizzle tempizzle shizzle my nizzle crocodizzle. Dawg pellentesque nibh et turpizzle. Fizzle izzle tortor. Pellentesque eleifend rhoncizzle pimpin. In break it down yippiyo platea dictumst. Away dapibus. Curabitur tellus urna, pretizzle eu, mattis ac, eleifend vitae, nunc. Stuff check out this. Shit mofo velizzle sizzle owned.',
                'flag'                  =>  0,
                'is_finish'             =>  0,
                'person_in_charge'      =>  null,
                'note'                  =>  null,
                'name'                  =>  'dummy sender',
                'email'                 =>  'dummy@sender.com',
                'company_name'          =>  'dummy thrid company',
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  5,
                'customers_id'          =>  2,
                'properties_id'         =>  1,
                'contact_us_types_id'   =>  101,
                'subject'               =>  'forgot my email again!',
                'text'                  =>  'Again Etizzle im in the shizzle urna hizzle nisl., im in the shizzle adipiscing elit. Shit sapien velizzle, volutpizzle, suscipit quis, gravida vizzle, arcu. Pellentesque pizzle tortizzle. Sizzle erizzle. Dawg owned phat dapibus thats the shizzle tempizzle shizzle my nizzle crocodizzle. Dawg pellentesque nibh et turpizzle. Fizzle izzle tortor. Pellentesque eleifend rhoncizzle pimpin. In break it down yippiyo platea dictumst. Away dapibus. Curabitur tellus urna, pretizzle eu, mattis ac, eleifend vitae, nunc. Stuff check out this. Shit mofo velizzle sizzle owned.',
                'flag'                  =>  1,
                'is_finish'             =>  0,
                'person_in_charge'      =>  null,
                'note'                  =>  null,
                'name'                  =>  'dummy paniced sender',
                'email'                 =>  'paniced@sender.com',
                'company_name'          =>  'dummy second company',
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
        ]);
    }
}
