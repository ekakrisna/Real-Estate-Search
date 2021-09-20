<?php

use App\Models\ListConsiderAmount;
use Illuminate\Database\Seeder;

class ListConsiderAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // All delete.
         ListConsiderAmount::truncate();
         $ListConsiderAmount = new ListConsiderAmount();
         $ListConsiderAmount->insert([
             [
                'id'       =>  1,
                'value'    =>  5000000
             ],
             [
                'id'       =>  2,
                'value'    =>  10000000
             ],
             [
                'id'       =>  3,
                'value'    =>  15000000
             ],
             [
                'id'       =>  4,
                'value'    =>  20000000
             ],
             [
                'id'       =>  5,
                'value'    =>  25000000
             ],
             [
                'id'       =>  6,
                'value'    =>  30000000
             ],
             [
                'id'       =>  7,
                'value'    =>  35000000
             ],
             [
                'id'       =>  8,
                'value'    =>  40000000
             ],
             [
                'id'       =>  9,
                'value'    =>  45000000
             ],
             [
                'id'       =>  10,
                'value'    =>  50000000
             ],
             [
                'id'       =>  11,
                'value'    =>  55000000
             ],
             [
                'id'       =>  12,
                'value'    =>  60000000
             ],
             [
                'id'       =>  13,
                'value'    =>  65000000
             ],
             [
                'id'       =>  14,
                'value'    =>  70000000
             ],
         ]);
    }
}
