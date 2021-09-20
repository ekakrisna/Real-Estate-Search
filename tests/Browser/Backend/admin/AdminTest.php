<?php

namespace Tests\Browser\Backend\admin;

use App\Models\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Traits\DatabaseMigrationsTrait;
use Tests\Browser\TestData\BaseTestData;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminTest extends DuskTestCase
{
    protected $common;
    use DatabaseMigrationsTrait;

    public function __construct()
    {
      parent::__construct();
      $this->common = new BaseTestData();
    }

    /**
    * @group admin
    */

    public function test_required()
    {
      // add conditional for refreshDatabase
      // search how to wait until migrations finished!
      $this->common->adminLogin();
      $this->browse(function (Browser $browser){
        $browser->click('.span-locale')
                ->press('#set-english')
                ->assertSeeIn('.info', 'Super Admin')
                ->click('#tree_admins')
                ->click('#create_admin')
                ->type('[name="display_name"]', '')
                ->type('[name="email"]', '')
                ->type('[name=password', '')
                ->press('#input-submit')
                ->assertSeeIn('#form-group--display_name',$this->common::VALIDATION_TEXT_REQUIRED_EN)
                ->assertSeeIn('#form-group--email',$this->common::VALIDATION_TEXT_REQUIRED_EN)
                ->assertSeeIn('#form-group--password',$this->common::VALIDATION_TEXT_REQUIRED_EN);
      });

    }

    /**
    *@group admin
    */
    public function test_validation()
    {
      $this->browse(function(Browser $browser){
        $browser->visit('/admin/admins/create')
                ->type('[name="email"]', 'invalid@invalid')
                ->type('[name=password', $this->common::SYMBOL_7)
                ->press('#input-submit')
                ->assertSeeIn('#form-group--email', $this->common::VALIDATION_TYPE_EMAIL_EN)
                ->assertSeeIn('#form-group--password',$this->common::VALIDATION_MINLEN_8_EN);
      });
    }


    /**
    *@group admin
    */
    public function test_insert()
    {
      $this->browse(function (Browser $browser){
        $browser->visit('/admin/admins/create')
                ->type('[name="display_name"]', 'Hilmi Al Fatih')
                ->type('[name="email"]', 'hilmi@grune.com')
                ->type('[name=password', '12345678')
                ->press('#input-submit')
                ->pause(3000)
                ->assertSee($this->common::SUCCESS_CREATE_MESSAGE_EN);
      });

      $this->assertDatabaseHas('admins',[
        'display_name' => 'Hilmi Al Fatih',
        'email'        => 'hilmi@grune.com',
      ]);
    }


    /**
    *@group admin
    */
    public function test_update()
    {
      $last_admin = Admin::latest()->first();
      $this->browse(function (Browser $browser) use ($last_admin) {
        $browser->visit('/admin/admins/'. $last_admin->id . '/edit')
                ->assertSee('Edit Admin')
                ->type('[name="display_name"]', 'Updated Name')
                ->type('[name="email"]', 'updated_email@grune.com')
                ->click('#reset-button')
                ->type('[name=password', '87654321')
                ->press('#input-submit')
                ->assertSee($this->common::SUCCESS_UPDATE_MESSAGE_EN);
      });

      $this->assertDatabaseHas('admins',[
        'display_name' => 'Updated Name',
        'email'        => 'updated_email@grune.com',
      ]);
    }
    /**
    *@group admin
    */
    public function test_delete()
    {
      $last_admin = Admin::latest()->first();
      $this->browse(function(Browser $browser) use ($last_admin){
        $browser->visit('/admin/admins')
                ->assertSeeIn('.content-header', 'Admin')
                ->waitUntilMissing('.dataTables_processing')
                ->click('[data-remote$="' . $last_admin->id . '"]')
                ->acceptDialog()
                ->waitFor('.toast-message')
                ->assertSeeIn('.toast-message', $this->common::jsInfoDeletedData_EN);

      });

    }
}