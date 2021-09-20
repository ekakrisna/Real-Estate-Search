<?php

namespace Tests\Browser\Backend\admin;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\TestData\BaseTestData; 

use App\Traits\DatabaseMigrationsTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class AdminLoginTest extends DuskTestCase
{
    protected $common;

    use DatabaseMigrationsTrait;

    public function __construct()
    {
        parent::__construct();
        $this->common = new BaseTestData();
    }

    public function testLogin()
    {
        // $this->common->executeAdminLoginTest();
    }
}
