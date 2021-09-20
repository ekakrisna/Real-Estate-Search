<?php

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Database\Seeder;

use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $min = 0;
    private $max = 0;
    private $min_land = 0;
    private $max_land = 0;

    public function min_price(){
        $min_price = ListConsiderAmount::inRandomOrder()->first();
        $this->min = $min_price->value;
        return $this->min;
    }
    
    public function max_price($min_price){
        $max_price = ListConsiderAmount::where('value', '>=', $min_price)->inRandomOrder()->first();
        $this->max = $max_price->value;
        return $this->max;
    }

    public function min_land(){
        $min_land = ListLandArea::inRandomOrder()->first();
        $this->min_land = $min_land->value;
        return $this->min_land;
    }
    
    public function max_land($min_lands){
        $max_land = ListLandArea::where('value', '>=', $min_lands)->inRandomOrder()->first();
        $this->max_land = $max_land->value;
        return $this->max_land;
    }

    public function run()
    {
        // All delete.
        Customer::truncate();
        $Customer = new Customer();
        $Customer->insert([
            [
                'id'       					=> 1,
                'company_users_id' 			=> 3,
                'name'      				=> 'customer',
                'email'      				=> 'customer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 1,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(14),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 2,
                'company_users_id' 			=> 5,
                'name'      				=> 'second customer',
                'email'      				=> 'secondcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 1,

                'minimum_price'      	    => null,
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => null,
                'minimum_land_area'     	=> null,
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(13),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 3,
                'company_users_id' 			=> 6,
                'name'      				=> 'third customer',
                'email'      				=> 'thirdcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => null,
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => null,
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => null,

                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(12),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 4,
                'company_users_id' 			=> 3,
                'name'      				=> 'fourth customer',
                'email'      				=> 'fourthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(11),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 5,
                'company_users_id' 			=> 5,
                'name'      				=> 'fifth customer',
                'email'      				=> 'fifthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'maximum_land_area'         => null,
                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(10),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 6,
                'company_users_id' 			=> 6,
                'name'      				=> 'sixth customer',
                'email'      				=> 'sixthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(9),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 7,
                'company_users_id' 			=> 5,
                'name'      				=> 'seventh customer',
                'email'      				=> 'seventhcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),
                
                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(8),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 8,
                'company_users_id' 			=> 3,
                'name'      				=> 'eighth customer',
                'email'      				=> 'eighthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),
                
                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(7),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 9,
                'company_users_id' 			=> 5,
                'name'      				=> 'nineth customer',
                'email'      				=> 'ninethcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'             => null,
                'maximum_price'             => null,
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'         => null,
                'maximum_land_area'         => null,

                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(6),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 10,
                'company_users_id' 			=> 6,
                'name'      				=> 'tenth customer',
                'email'      				=> 'tenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(5),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 11,
                'company_users_id' 			=> 5,
                'name'      				=> 'eleventh customer',
                'email'      				=> 'eleventhcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(4),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 12,
                'company_users_id' 			=> 3,
                'name'      				=> 'twelfth customer',
                'email'      				=> 'twelfthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(3),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 13,
                'company_users_id' 			=> 6,
                'name'      				=> 'thirteenth customer',
                'email'      				=> 'thirteenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now()->subDays(2),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 14,
                'company_users_id' 			=> 5,
                'name'      				=> 'fourteenth customer',
                'email'      				=> 'fourteenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 0,
                'created_at'        		=> Carbon::now()->subDays(1),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 15,
                'company_users_id' 			=> 3,
                'name'      				=> 'fifteenth customer',
                'email'      				=> 'fifteenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 16,
                'company_users_id' 			=> 7,
                'name'      				=> 'sixteenth customer',
                'email'      				=> 'sixteenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 17,
                'company_users_id' 			=> 7,
                'name'      				=> 'seventeenth customer',
                'email'      				=> 'seventeenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 18,
                'company_users_id' 			=> 2,
                'name'      				=> 'eightteenth customer',
                'email'      				=> 'eightteenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 19,
                'company_users_id' 			=> 8,
                'name'      				=> 'nineteenth customer',
                'email'      				=> 'nineteenthcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 20,
                'company_users_id' 			=> 1,
                'name'      				=> 'twentieth customer',
                'email'      				=> 'twentiethcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 0,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 1,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
            [
                'id'       					=> 21,
                'company_users_id' 			=> 9,
                'name'      				=> 'twenty first customer',
                'email'      				=> 'twentyfirstcustomer@grune.com',
                'password'      			=> bcrypt('12345678'),
                'phone'      				=> '',
                'flag'      				=> 1,
                'is_cancellation'      		=> 0,
                'not_leave_record'      	=> 0,

                'minimum_price'      	    => $this->min_price(),
                'maximum_price'      	    => $this->max_price($this->min),
                'minimum_price_land_area'   => $this->min_price(),
                'maximum_price_land_area'   => $this->max_price($this->min),
                'minimum_land_area'     	=> $this->min_land(),
                'maximum_land_area'         => $this->max_land($this->min_land),

                'license'    				=> 0,
                'created_at'        		=> Carbon::now(),
                'updated_at'        		=> Carbon::now(),
            ],
        ]);
    }
}