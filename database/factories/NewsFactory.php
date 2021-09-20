<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\News::class, function (Faker $faker) {

    /** Image faker using storage, works for s3 driver
     * Image faker server seems down
     *
    $image = $faker->image(null, 400,300, 'abstract', true);
    $imageFile = new File($image);
    $img = Storage::putFile('/', $imageFile, 'public');

    $pdf = $faker->image(null, 400,300, 'abstract', true);
    $pdfFile = new File($pdf);
    $pdf = Storage::putFile('/', $pdfFile, 'public');
    **/

    return [
        'admin_id'      => 2,
        'company_id'    => rand(1, 10),
        'title'         => $faker->words(rand(3, 5), true),
        'body'          => $faker->paragraph,
        /**
         * below does't using storage, does't works for s3 driver
         * 'image'         => 'uploads/' . $faker->image(public_path('uploads'),400,300, 'abstract', false),
         */
        //'image'         => $img,
        //'pdf_file'      => $pdf,
        'radius'        => rand(10, 50),
        'publish_date'  => Carbon::now()->subMonths(rand(1,3))->subDays(rand(1,30)),
        'status'        => 'publish'
    ];

});
