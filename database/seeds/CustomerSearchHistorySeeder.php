<?php

use Carbon\Carbon;
use App\Models\CustomerSearchHistory;
use Illuminate\Database\Seeder;

use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;

class CustomerSearchHistorySeeder extends Seeder
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
        CustomerSearchHistory::truncate();
        $CustomerSearchHistory = new CustomerSearchHistory();
        $CustomerSearchHistory->insert([
            [
                'id'                    =>  1,
                'customers_id'          =>  1,
                'location'              =>  'dummy location',
                'minimum_price'         =>  null,
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  null,
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  2,
                'customers_id'          =>  2,
                'location'              =>  'seconnd dummy location',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  null,
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  null,
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  3,
                'customers_id'          =>  3,
                'location'              =>  'third dummy location',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  4,
                'customers_id'          =>  3,
                'location'              =>  'fourth dummy location',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  5,
                'customers_id'          =>  2,
                'location'              =>  'fifth dummy location',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  null,
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  6,
                'customers_id'          =>  1,
                'location'              =>  'dummy location 2',
                'minimum_price'         =>  null,
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  7,
                'customers_id'          =>  1,
                'location'              =>  'dummy location 3',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  8,
                'customers_id'          =>  1,
                'location'              =>  'dummy location 4',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  9,
                'customers_id'          =>  1,
                'location'              =>  'dummy location 5',
                'minimum_price'         =>  null,
                'maximum_price'         =>  null,
                'minimum_land_area'     =>  null,
                'maximum_land_area'     =>  null,
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  10,
                'customers_id'          =>  2,
                'location'              =>  'dummy location 2',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  11,
                'customers_id'          =>  2,
                'location'              =>  'dummy location 3',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  12,
                'customers_id'          =>  2,
                'location'              =>  'dummy location 4',
                'minimum_price'         =>  null,
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  null,
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  13,
                'customers_id'          =>  2,
                'location'              =>  'dummy location 5',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  14,
                'customers_id'          =>  3,
                'location'              =>  'dummy location 2',
                'minimum_price'         =>  null,
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  null,
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  15,
                'customers_id'          =>  3,
                'location'              =>  'dummy location 3',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  null,
                'minimum_land_area'     =>  null,
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  16,
                'customers_id'          =>  3,
                'location'              =>  'dummy location 4',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  null,
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  17,
                'customers_id'          =>  3,
                'location'              =>  'dummy location 5',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  null,
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  18,
                'customers_id'          =>  1,
                'location'              =>  'dummy location 6',
                'minimum_price'         =>  null,
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  null,
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  19,
                'customers_id'          =>  2,
                'location'              =>  'dummy location 6',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  null,
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ],
            [
                'id'                    =>  20,
                'customers_id'          =>  3,
                'location'              =>  'dummy location 6',
                'minimum_price'         =>  $this->min_price(),
                'maximum_price'         =>  $this->max_price($this->min),
                'minimum_land_area'     =>  $this->min_land(),
                'maximum_land_area'     =>  $this->max_land($this->min_land),
                'created_at'            =>  Carbon::now(),
                'updated_at'            =>  Carbon::now(),
            ]
        ]);
    }
}
