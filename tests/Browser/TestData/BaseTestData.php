<?php

namespace Tests\Browser\TestData;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;


class BaseTestData extends DuskTestCase
{
    /*
     * input data
     */


    const SUCCESS_ADMIN_EMAIL = 'admin@admin.com';
    const FAILURE_ADMIN_EMAIL = 'failure@admin.com';
    const SUCCESS_PASSWORD    = '12345678';
    const FAILURE_PASSWORD    = '11111111';

    const SYMBOL_7            = '!"#$%&\'';
    const SYMBOL_8            = '!"#$%&\'(';

    const INTEGER_5          = '12345';
    const INTEGER_10          = '1234567890';

    const INTEGER_7           = '1234567';
    const INTEGER_8           = '12345678';
    const INTEGER_9           = '123456789';
    
    const STRING_10           = '１２３４５６７８９０';
    const STRING_11           = '１２３４５６７８９０１';

    const INTEGER_12          = '123456789012';
    const INTEGER_13          = '1234567890123';

    const PHONE               = '123321321';

    const INTEGER_20          = '12345678901234567890';
    const INTEGER_21          = '123456789012345678901';

    const STRING_20           = '１２３４５６７８９０１２３４５６７８９０';
    const STRING_21           = '１２３４５６７８９０１２３４５６７８９０１';

    const STRING_30           = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０';
    const STRING_31           = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１';

    const SYMBOL_30           = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_';
    const SYMBOL_31           = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<';

    const STRING_KANA_50      = 'ホショウニンフリガナセイニホショウニンフリガナセイニホショウニンフリガナセイニホショウニンフリガナセ';
    const STRING_50           = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０';
    const STRING_51           = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０1';

    const SYMBOL_50           = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<>?_\\１２３４５６７８９０１２３４５';
    const SYMBOL_51           = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<>?_\\１２３４５６７８９０１２３４５６';

    const EMAIL_50            = '12345678901234567890123456789012345678901@test.com';
    const EMAIL_51            = '123456789012345678901234567890123456789012@test.com';

    const STRING_80           = '12345678901234567890123456789012345678901234567890123456789012345678901234567890';
    const STRING_81           = '123456789012345678901234567890123456789012345678901234567890123456789012345678901';

    const STRING_100          = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０';
    const STRING_101          = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０1';

    const SYMBOL_100          = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<>?_\\１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５';
    const SYMBOL_101          = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<>?_\\１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６';

    const STRING_200          = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０';
    const STRING_201          = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１';

    const STRING_255          = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５';
    const STRING_256          = '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６';

    const SYMBOL_200          = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<>?_\\１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５';
    const SYMBOL_201          = '!"#$%&\'()0-^¥=~|@[`{;:]+*},./_<>?_\\１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６';


    /* message */

    const SUCCESS_CREATE_MESSAGE_EN     = 'Your entry has been successfully saved!';
    const SUCCESS_CREATE_MESSAGE_JA     = '入力した内容を保存しました。';

    const FAILED_CREATE_MESSAGE_EN      = 'Sorry, we were unable to save your entry. Please check your entry and try again later';
    const FAILED_CREATE_MESSAGE_JA      = '入力した内容を保存できませんでした。';

    const SUCCESS_UPDATE_MESSAGE_EN     = 'Your update has been successfully saved!';
    const SUCCESS_UPDATE_MESSAGE_JA     = '編集した内容を保存しました。';

    const FAILED_UPDATE_MESSAGE_EN      = 'Sorry, we were unable to save your update. Please check your update and try again later';
    const FAILED_UPDATE_MESSAGE_JA      = '編集した内容を保存できませんでした。';

    const SUCCESS_DELETE_MESSAGE_EN     = 'Data has been successfully deleted!';
    const SUCCESS_DELETE_MESSAGE_JA     = '対象のデータを削除しました。';

    const FAILED_DELETE_MESSAGE_EN      = 'Sorry, the data could not be deleted';
    const FAILED_DELETE_MESSAGE_JA      = '対象のデータを削除できませんでした。';

    const FAILED_DELETE_SELF_MESSAGE_EN ='Sorry, we could not delete data of currently logged in person';
    const FAILED_DELETE_SELF_MESSAGE_JA = '現在ログインされている方のデータを削除できませんでした。';

    const jsInfoDeletedData_EN             = 'Data has been successfully deleted!';
    const jsInfoDeletedData_JP             = 'データが削除されました！';


    /*
     * validation text
     */
    const VALIDATION_TEXT_REQUIRED_EN = 'This value is required.';
    const VALIDATION_TEXT_REQUIRED_JA = 'この値は必須です。';

    const VALIDATION_TYPE_EMAIL_EN = 'This value should be a valid email.';
    const VALIDATION_TYPE_EMAIL_JA = '有効なメールアドレスを入力してください。';

    const VALIDATION_MINLEN_8_EN = 'This value is too short. It should have 8 characters or more.';
    const VALIDATION_MINLEN_8_JA = '8 文字以上で入力してください。';


    const REQUIRED_LABEL_EN = 'Required';
    const REQUIRED_LABEL_JA = '必須';

    const OPTIONAL_LABEL_EN = 'Optional';
    const OPTIONAL_LABEL_JA = '任意';

    /*
     * admin login
     */
    public function executeAdminLoginTest()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->assertSee('ADMINISTRATION LOGIN')
                ->type('email',self::SUCCESS_ADMIN_EMAIL)
                ->type('password',self::SUCCESS_PASSWORD)
                ->press('Login')
                ->waitForText('Admin')
                ->assertPathIs('/admin/superadmin')
                ->press('#admin-logout')
                ->assertSee('ADMINISTRATION LOGIN');
        });
    }

    public function adminLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->assertSee('ADMINISTRATION LOGIN')
                ->type('email',self::SUCCESS_ADMIN_EMAIL)
                ->type('password',self::SUCCESS_PASSWORD)
                ->press('Login')
                ->assertPathIs('/admin/superadmin')
                ->pause(3000);
        });
    }

    public function adminLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->click('#admin-logout')
                    ->assertSee('ADMINISTRATION LOGIN');
        });
    }

    /* to do: create user and login */

}
