<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
      //h - chromeoptions comes from php-webdriver, bundled with dusk
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            // '--headless',
            '--window-size=1366,1024',
        ]);
        // switch(env('DUSK_BROWSER')) {
        //   case 'chrome'
        //   return RemoteWebDriver::create(
        //     'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options)
        //   )
        // }

        //using selenium
        return RemoteWebDriver::create(
          'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
              ChromeOptions::CAPABILITY, $options
          ), 60*1000, 60*1000
        );
    }
}
