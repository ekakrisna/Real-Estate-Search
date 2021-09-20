<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCompanyUserTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_user_teams', function(Blueprint $table)
		{
			$table->foreign('leader_id')->references('id')->on('company_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('member_id')->references('id')->on('company_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_user_teams', function(Blueprint $table)
		{
            
        });
	}

}
