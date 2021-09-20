<?php


use Carbon\Carbon;
use App\Models\CustomerFavoriteProperty;
use App\Models\Property;
use Illuminate\Database\Seeder;

class CustomerFavoritePropertySeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      // All delete.
      CustomerFavoriteProperty::truncate();
      $CustomerFavoriteProperty = new CustomerFavoriteProperty();
      $CustomerFavoriteProperty->insert([
         [
            'id'            => 1,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 2,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 3,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 4,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 5,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 6,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 7,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 8,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 9,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 10,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 11,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 12,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 13,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 14,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 15,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 16,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 17,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 18,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 19,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 20,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 21,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 22,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 23,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 24,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 25,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 26,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 27,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
         [
            'id'            => 28,
            'customers_id'  => rand(1, 5),
            'properties_id' => Property::inRandomOrder()->first()->id,
            'created_at'    => Carbon::now()->subDays(30)->toDateTimeString(),
         ],
      ]);
   }
}
