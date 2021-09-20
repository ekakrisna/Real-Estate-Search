<?php

use Carbon\Carbon;
use App\Models\ContactUsType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ContactUsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        // ContactUsType::truncate();
        Schema::disableForeignKeyConstraints();
        ContactUsType::truncate();
        Schema::enableForeignKeyConstraints();
        $ContactUsType = new ContactUsType();
        $ContactUsType->insert([
            [
                'id'        =>  1,
			    'label'     =>  '使い方に関して'
            ],
            [
                'id'        =>  101,
			    'label'     =>  'メールアドレスを忘れた場合'
            ],
            [
                'id'        =>  901,
			    'label'     =>  '物件問い合わせ'
            ]
        ]);
    }
}
