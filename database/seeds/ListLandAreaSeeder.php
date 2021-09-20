<?php

use App\Models\ListLandArea;
use Illuminate\Database\Seeder;

class ListLandAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        ListLandArea::truncate();
        $ListLandArea = new ListLandArea();
        $ListLandArea->insert([
                [
                    'id'       =>  1,
                    'value'    =>  66.1157
                 ],
                 [
                    'id'       =>  2,
                    'value'    =>  82.6446
                 ],
                 [
                    'id'       =>  3,
                    'value'    =>  99.1736
                 ],
                 [
                    'id'       =>  4,
                    'value'    =>  115.702
                 ],
                 [
                    'id'       =>  5,
                    'value'    =>  132.231
                 ],
                 [
                    'id'       =>  6,
                    'value'    =>  148.76
                 ],
                 [
                    'id'       =>  7,
                    'value'    =>  165.289
                 ],
                 [
                    'id'       =>  8,
                    'value'    =>  181.818
                 ],
                 [
                    'id'       =>  9,
                    'value'    =>  198.347
                 ],
                 [
                    'id'       =>  10,
                    'value'    =>  214.876
                 ],
                 [
                    'id'       =>  11,
                    'value'    =>  231.405
                 ],
                 [
                    'id'       =>  12,
                    'value'    =>  247.934
                 ],
                 [
                    'id'       =>  13,
                    'value'    =>  264.463
                 ],
                 [
                    'id'       =>  14,
                    'value'    =>  297.521
                 ],
                 [
                    'id'       =>  15,
                    'value'    =>  314.05
                 ],
                 [
                    'id'       =>  16,
                    'value'    =>  330.579
                 ]
        ]);
    }
}
