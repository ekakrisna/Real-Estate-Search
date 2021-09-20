<?php

use Carbon\Carbon;
use App\Models\CustomerLogActivity;
use Illuminate\Database\Seeder;

class CustomerLogActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        CustomerLogActivity::truncate();
        $CustomerLogActivity = new CustomerLogActivity();
        $CustomerLogActivity->insert([
            [
                'id'                => 1,
                'customers_id'      => 1,
                'properties_id'     => 1,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 2,
                'customers_id'      => 1,
                'properties_id'     => 2,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 3,
                'customers_id'      => 1,
                'properties_id'     => 3,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 4,
                'customers_id'      => 3,
                'properties_id'     => 3,
                'action_types_id'   => 5,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 5,
                'customers_id'      => 3,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 6,
                'customers_id'      => 3,
                'properties_id'     => 2,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(30)->toDateTimeString(),
            ],
            [
                'id'                => 7,
                'customers_id'      => 4,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(11)->toDateTimeString(),
            ],
            [
                'id'                => 8,
                'customers_id'      => 5,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(10)->toDateTimeString(),
            ],
            [
                'id'                => 9,
                'customers_id'      => 6,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(9)->toDateTimeString(),
            ],
            [
                'id'                => 10,
                'customers_id'      => 7,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(8)->toDateTimeString(),
            ],
            [
                'id'                => 11,
                'customers_id'      => 8,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(7)->toDateTimeString(),
            ],
            [
                'id'                => 12,
                'customers_id'      => 9,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(6)->toDateTimeString(),
            ],
            [
                'id'                => 13,
                'customers_id'      => 10,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(5)->toDateTimeString(),
            ],
            [
                'id'                => 14,
                'customers_id'      => 11,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(4)->toDateTimeString(),
            ],
            [
                'id'                => 15,
                'customers_id'      => 12,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(3)->toDateTimeString(),
            ],
            [
                'id'                => 16,
                'customers_id'      => 13,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(2)->toDateTimeString(),
            ],
            [
                'id'                => 17,
                'customers_id'      => 14,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(1)->toDateTimeString(),
            ],
            [
                'id'                => 18,
                'customers_id'      => 15,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 19,
                'customers_id'      => 16,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 20,
                'customers_id'      => 17,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 21,
                'customers_id'      => 18,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 22,
                'customers_id'      => 3,
                'properties_id'     => 2,
                'action_types_id'   => 5,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->subDays(30)->toDateTimeString(),
            ],
            [
                'id'                => 23,
                'customers_id'      => 19,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 24,
                'customers_id'      => 20,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 25,
                'customers_id'      => 21,
                'properties_id'     => null,
                'action_types_id'   => 1,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 26,
                'customers_id'      => 3,
                'properties_id'     => 1,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 27,
                'customers_id'      => 3,
                'properties_id'     => 7,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
            [
                'id'                => 28,
                'customers_id'      => 3,
                'properties_id'     => 8,
                'action_types_id'   => 4,
                'ip'                => '127.0.0.1',
                'access_time'       => Carbon::now()->toDateTimeString(),
            ],
        ]);
    }
        
}