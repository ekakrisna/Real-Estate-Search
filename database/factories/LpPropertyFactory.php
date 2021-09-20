<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\City;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\LpProperty;
use App\Models\LpPropertyConvertStatus;
use App\Models\LpPropertyScrapingType;
use App\Models\LpPropertyStatus;
use App\Models\LpScraping;
use App\Models\Prefecture;
use App\Models\Property;
use App\Models\PropertyScrapingType;
use App\Models\Scraping;
use App\Models\Town;
use Carbon\Carbon;
use Faker\Generator as Faker;

// --------------------------------------------------------------------------
// LpProperty factory
// --------------------------------------------------------------------------
$factory->define(LpProperty::class, function (Faker $faker) {
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    // Add Japanese faker
    // ----------------------------------------------------------------------
    $faker->addProvider(new \Faker\Provider\Internet($faker));
    $faker->addProvider(new \Faker\Provider\Japanese($faker));
    // ----------------------------------------------------------------------
    $data->lp_scrapings_id = LpScraping::inRandomOrder()->first();
    $data->lp_property_scraping_type_id = LpPropertyScrapingType::select('id')->inRandomOrder()->first()->id;
    $data->lp_property_status_id = LpPropertyStatus::select('id')->inRandomOrder()->first()->id;
    $data->lp_property_convert_status_id = LpPropertyConvertStatus::select('id')->inRandomOrder()->first()->id;
    $data->property_no = Property::inRandomOrder()->first(); // "property_no" is abolished!! please refer "lp_property_publish.property_number"
    // $data->scraping_id = Scraping::inRandomOrder()->first();
    // $data->scraping_type_id = PropertyScrapingType::inRandomOrder()->first();
    // ----------------------------------------------------------------------
    $prefecture = Prefecture::select('name')->inRandomOrder()->first()->name  ?? null;
    $city = City::select('name')->inRandomOrder()->first()->name  ?? null;
    $town = Town::select('name')->inRandomOrder()->first()->name  ?? null;
    // ----------------------------------------------------------------------
    $data->location = $prefecture . $city . $town;
    // ----------------------------------------------------------------------
    // ----------------------------------------------------------------------
    $data->minimum_price = ListConsiderAmount::inRandomOrder()->first()->value;
    $data->maximum_price = ListConsiderAmount::where('value', '>=', ListConsiderAmount::inRandomOrder()->first()->value)->inRandomOrder()->first()->value;
    // ----------------------------------------------------------------------
    $data->minimum_land_area = ListLandArea::inRandomOrder()->first()->value;
    $data->maximum_land_area = ListLandArea::where('value', '>=', ListLandArea::inRandomOrder()->first()->value)->inRandomOrder()->first()->value;
    $data->building_area = ListLandArea::where('value', '>=', ListLandArea::inRandomOrder()->first()->value)->inRandomOrder()->first()->value;
    // ----------------------------------------------------------------------
    $data->building_age = $faker->numberBetween(100, 500);
    // ----------------------------------------------------------------------
    $data->house_layout = $faker->name;
    $data->connecting_road = $faker->address;
    $data->traffic = $faker->address;
    // ----------------------------------------------------------------------
    $data->publish_date = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(1, 30));
    $data->contracted_years = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(1, 30));
    $data->created_at = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(1, 30));
    $data->updated_at = Carbon::now()->subMonths(rand(1, 3))->subDays(rand(1, 30));
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Property initial state
// --------------------------------------------------------------------------
$factory->state(Property::class, 'init', function () {
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    $data->lp_scrapings_id = null;
    $data->lp_property_scraping_type_id = null;
    $data->lp_property_status_id = null;
    $data->lp_property_convert_status_id = null;
    $data->property_no = null;
    // $data->scraping_id = null;
    // $data->scraping_type_id = null;
    // ----------------------------------------------------------------------
    $data->minimum_price = null;
    $data->maximum_price = null;
    // ----------------------------------------------------------------------
    $data->minimum_land_area = null;
    $data->maximum_land_area = null;
    $data->building_area = null;
    // ----------------------------------------------------------------------
    $data->building_age = null;
    // ----------------------------------------------------------------------
    $data->house_layout = null;
    $data->connecting_road = null;
    $data->traffic = null;
    // ----------------------------------------------------------------------
    $data->publish_date = null;
    $data->contracted_years = null;
    $data->created_at = null;
    $data->updated_at = null;

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
