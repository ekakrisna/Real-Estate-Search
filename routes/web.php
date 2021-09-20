<?php
// --------------------------------------------------------------------------
// Web Routes
// --------------------------------------------------------------------------
// Here is where you can register web routes for your application. These
// routes are loaded by the RouteServiceProvider within a group which
// contains the "web" middleware group. Now create something great!
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// You can view registered routes using command below.
// > php artisan route:list
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Basic auth protection
// --------------------------------------------------------------------------

use Illuminate\Support\Facades\Auth;

    // ----------------------------------------------------------------------
    Route::get('/', function () {
        return redirect()->route('frontend.start');
    })->middleware('guest');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    Route::get('/auth-check', function () {
        dd(Auth::guard("web")->check());
        //dd(Auth::guard("user")->user()->toArray());
    });
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Authentication routes
    // ----------------------------------------------------------------------
    Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('login');
    Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('login.attempt');
    // ----------------------------------------------------------------------
    Route::get('/company/login', 'Auth\CompanyUserLoginController@showLoginForm')->name('company-user-login');
    Route::post('/company/login', 'Auth\CompanyUserLoginController@login')->name('company-user-login-action');
    // ----------------------------------------------------------------------

    // C1 CUSTOMER LOGIN
    // ----------------------------------------------------------------------
    Route::get('/login', 'Auth\CustomerLoginController@showLoginForm')->name('customer-login');
    Route::post('/login', 'Auth\CustomerLoginController@login')->name('customer-login-action');

    // ----------------------------------------------------------------------
    // Password reset routes
    // ----------------------------------------------------------------------
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Email verification routes
    // ----------------------------------------------------------------------
    Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // SOCIALITE LOGIN
    // ----------------------------------------------------------------------
    Route::get('auth/{provider}', 'Auth\SocialiteController@redirectToProvider')->name('login-social');
    Route::get('auth/{provider}/callback', 'Auth\SocialiteController@handleProviderCallback')->name('login-social-action');

    Route::get('auth/instagram/basic', 'Auth\SocialiteController@redirectToInstagramProvider')->name('login-instagram');
    Route::get('auth/instagram/basic/callback', 'Auth\SocialiteController@instagramProviderCallback')->name('login-instagram-action');
    // ----------------------------------------------------------------------

// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// I18n route
// --------------------------------------------------------------------------
Route::get('setlanguage/{language}', 'Backend\LanguageController@SetLanguage')->name('setlanguage');
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Backend route group
// --------------------------------------------------------------------------
Route::group(['middleware' => 'auth:web'], function () {
    // ----------------------------------------------------------------------
    // Dashboard
    // ----------------------------------------------------------------------
    Route::get('dashboard', function () {
        return "@TODO: Create simple dashboard contained count and simple chart";
    })->name('dashboard');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Admin logout
    // ----------------------------------------------------------------------
    Route::get('admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Manage  logout
    // ----------------------------------------------------------------------
    Route::get('manage/logout', 'Auth\AdminLoginController@logout')->name('manage.logout');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Backend namespaced routes
    // ----------------------------------------------------------------------
    Route::namespace('Backend')->prefix('admin')->name('admin.')->group(function () {
        Route::group(['middleware' => ['company_role:ADMIN']], function () {

            // ------------------------------------------------------------------
            // Route group authorized for Super Admin only
            // ------------------------------------------------------------------
            Route::group(['middleware' => ['role:ADMIN']], function () {
                // --------------------------------------------------------------
                Route::resource('', 'DashboardController')->only(['index', 'show']);
                // --------------------------------------------------------------
                Route::get('{customer_id}/change-customer-flag', 'DashboardController@changeCustomerFlag')->name('changeCustomerFlag');
                Route::get('{customer_id}/change-customer-contact-us-flag', 'DashboardController@changeCustomerContactUsFlag')->name('changeCustomerContactUsFlag');
                // --------------------------------------------------------------
                //Route::resource('superadmin', 'SuperAdminController');
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                Route::resource('logactivities', 'LogActivityController')->only(['index', 'show']);
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Customer
                // --------------------------------------------------------------
                Route::namespace('Customer')->group(function () {

                    // --------------------------------------------------------------
                    // ADD on Customer Function
                    // --------------------------------------------------------------
                    Route::post('get_town', 'CustomerResourceController@get_towns')->name('get_town');
                    Route::post('get_company_user', 'CustomerResourceController@get_company_user')->name('get_company_user');
                    Route::post('get_customer', 'CustomerResourceController@get_customer')->name('get_customer');

                    // ----------------------------------------------------------
                    // B9 - History Customer Search
                    // ----------------------------------------------------------
                    Route::get('customer_all_use_history', 'CustomerHistoryController@index')->name('customer_all_use_history');
                    Route::post('customer_all_use_history', 'CustomerHistoryController@filter')->name('customer_all_use_history.filter');

                    // --------------------------------------------------------------
                    // B10 - Customer Search Histories
                    // --------------------------------------------------------------
                    Route::get('customer_all_search_history', 'CustomerAllSearchHistoryController@index')->name('customer_all_search_history');
                    Route::post('customer_all_search_history', 'CustomerAllSearchHistoryController@filter')->name('customer_all_search_history.filter');
                    Route::get('customer_all_search_history/{any}', 'CustomerAllSearchHistoryController@index')->where('any', '.*');
                    // --------------------------------------------------------------

                    // ----------------------------------------------------------
                    // B11 - User Contact History
                    // ----------------------------------------------------------
                    Route::get('customer_all_contact', 'CustomerContactHistoryController@index')->name('customer_all_contact');
                    Route::post('customer_all_contact', 'CustomerContactHistoryController@filter')->name('customer_all_contact.filter');
                    Route::post('customer_all_contact/{id}/flag', 'CustomerContactHistoryController@flag')->name('customer_all_contact.flag');
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // B13 - Customer Contact Detail (Spesific user)
                    // --------------------------------------------------------------
                    Route::get('contact/{contact_id}/detail', 'CustomerContactUsController@detail')->name('contact.detail');
                    Route::resource('contact', 'CustomerContactUsController');
                    // --------------------------------------------------------------

                    Route::prefix('customer')->name('customer.')->group(function () {
                        // ----------------------------------------------------------
                        // B4 Customer List
                        // ----------------------------------------------------------
                        Route::get('/', 'CustomerController@index')->name('list');
                        Route::post('/', 'CustomerController@filter')->name('list.filter');

                        // ----------------------------------------------------------
                        // B2 Customer Create
                        // ----------------------------------------------------------
                        Route::get('create', 'CustomerResourceController@create')->name('create');
                        Route::post('create', 'CustomerResourceController@store')->name('create.store');
                        // ----------------------------------------------------------


                        // --------------------------------------------------------------
                        // Customer Details
                        // --------------------------------------------------------------
                        Route::prefix('{customer_id}')->group(function () {
                            // ----------------------------------------------------------
                            // B5 - Customer Detail
                            // ----------------------------------------------------------
                            Route::get('/', 'CustomerDetailController@index')->name('detail');
                            // ----------------------------------------------------------

                            // ----------------------------------------------------------
                            // B3 Customer Edit
                            // ----------------------------------------------------------
                            Route::get('edit', 'CustomerResourceController@edit')->name('edit');
                            Route::post('edit', 'CustomerResourceController@update')->name('edit.update');

                            // ----------------------------------------------------------
                            // B6 - Customer Favorite History
                            // ----------------------------------------------------------
                            // Route::resource('fav_history', 'CustomerFavoriteController');
                            Route::post('fav_history', 'CustomerFavoriteController@favfilter')->name('fav_history.favfilter');
                            Route::get('fav_history', 'CustomerFavoriteController@index')->name('fav_history');
                            Route::get('change-history-flag', 'CustomerFavoriteController@changeFlag')->name('fav_history.changeFlag');
                            // ----------------------------------------------------------

                            // --------------------------------------------------------------
                            // B7 - Customer Use History
                            // --------------------------------------------------------------
                            Route::any('use_history/data', 'CustomerUseHistoryController@data');
                            Route::resource('use_history', 'CustomerUseHistoryController');
                            Route::post('use_history/filter', 'CustomerUseHistoryController@useruseHistoryFilter')->name('use_history.filter');
                            // --------------------------------------------------------------

                            // --------------------------------------------------------------
                            // B8 - Customer Search History
                            // --------------------------------------------------------------
                            Route::get('search_history', 'CustomerSearchHistoryController@index')->name('search_history');
                            Route::post('search_history', 'CustomerSearchHistoryController@filter')->name('search_history.filter');
                            Route::get('search_history/{any}', 'CustomerSearchHistoryController@index')->where('any', '.*');
                            // --------------------------------------------------------------

                            // ----------------------------------------------------------
                            // B12 - User Contact History (Specific user)
                            // ----------------------------------------------------------
                            Route::get('contact_history', 'CustomerContactHistoryController@contact_history')->name('contact_history');
                            Route::post('contact_history', 'CustomerContactHistoryController@filter_contact')->name('contact_history.filter');
                            Route::get('contact_history/{any}', 'CustomerContactHistoryController@contact_history')->where('any', '.*');
                            // ----------------------------------------------------------

                            // --------------------------------------------------
                            // Customer flag toggle
                            // --------------------------------------------------
                            Route::post('flag', 'CustomerResourceController@flag')->name('flag');
                            // --------------------------------------------------
                        });
                    });
                });
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Company
                // --------------------------------------------------------------
                Route::namespace('Company')->prefix('company')->name('company.')->group(function () {
                    // ----------------------------------------------------------
                    // B14 - Company List
                    // ----------------------------------------------------------
                    Route::get('/', 'CompanyController@index')->name('list');
                    Route::post('filter', 'CompanyController@filter')->name('list.filter');

                    // ----------------------------------------------------------
                    // B15 - Company Create
                    // ----------------------------------------------------------
                    Route::get('create', 'CompanyCreateController@index')->name('create');
                    Route::post('create', 'CompanyCreateController@store')->name('create.store');
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Company Edit
                    // ----------------------------------------------------------
                    Route::get('{company_id}/edit', 'CompanyEditController@index')->name('edit');
                    Route::put('{company_id}/edit', 'CompanyEditController@store')->name('edit.store');

                    // ----------------------------------------------------------
                    // Company user group
                    // ----------------------------------------------------------
                    Route::namespace('User')->prefix('{company_id}/user')->name('user.')->group(function () {
                        // ------------------------------------------------------
                        // B20 - Company user permission
                        // ------------------------------------------------------
                        Route::get('permission', 'CompanyUserPermissionController@index')->name('permission');
                        Route::post('permission', 'CompanyUserPermissionController@detail')->name('detail.filter');
                        Route::post('permission/upload', 'CompanyUserPermissionController@importCsv')->name('permission.upload');
                        Route::post('permission/addteam', 'CompanyUserPermissionController@addTeam')->name('permission.addTeam');

                        // ------------------------------------------------------
                        // ------------------------------------------------------
                        // B19 - Company user edit
                        // ------------------------------------------------------
                        Route::get('{user_id}/edit', 'CompanyUserResourceController@edit')->name('edit');
                        Route::put('{user_id}', 'CompanyUserResourceController@update')->name('update');
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // B18 - Company user create
                        // ------------------------------------------------------
                        Route::get('create', 'CompanyUserResourceController@create')->name('create');
                        Route::post('create', 'CompanyUserResourceController@store')->name('store');
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // B17 - Company User List
                        // ------------------------------------------------------
                        Route::get('/', 'CompanyUserListController@index')->name('list');
                        Route::post('filter', 'CompanyUserListController@filter')->name('list.filter');
                        Route::delete('{user_id}/remove', 'CompanyUserListController@destroy')->name('list.destroy');
                        Route::get('{any}', 'CompanyUserListController@index')->where('any', '.*');

                        // ------------------------------------------------------


                    });
                    // ----------------------------------------------------------
                });
                // --------------------------------------------------------------


                // ----------------------------------------------------------
                // Property routes
                // --------------------------------------------------------------
                Route::namespace('Property')->group(function () {
                    // ----------------------------------------------------------
                    // B21 - Property List
                    // ----------------------------------------------------------
                    Route::get('property', 'PropertyController@index')->name('property');
                    Route::post('property', 'PropertyController@filter')->name('property.filter');

                    // Property Photo
                    Route::post('property/{property}/upload_photo', 'PropertyController@uploadPhoto')->name('property.upload_photo');
                    Route::post('property/{property}/delete_photo', 'PropertyController@deletePhoto')->name('property.delete_photo');
                    Route::post('property/{property}/order_photo', 'PropertyController@orderPhoto')->name('property.order_photo');

                    // Property Flyer
                    Route::post('property/{property}/upload_flyer', 'PropertyController@uploadFlyer')->name('property.upload_flyer');
                    Route::post('property/{property}/delete_flyer', 'PropertyController@deleteFlyer')->name('property.delete_flyer');
                    Route::post('property/{property}/order_flyer', 'PropertyController@orderFlyer')->name('property.order_flyer');
                    // ----------------------------------------------------------

                    Route::post('property/{property}/order-images', 'PropertyController@orderImages')->name('property.order_images');


                    // ----------------------------------------------------------
                    // B25 - Property Create
                    // ----------------------------------------------------------
                    Route::get('property/create', 'PropertyCreateController@index')->name('property.create');
                    Route::post('property/create', 'PropertyCreateController@store')->name('property.create.store');
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // B25 - Property Create
                    // ----------------------------------------------------------
                    Route::get('property/create', 'PropertyCreateController@index')->name('property.create');
                    Route::post('property/create', 'PropertyCreateController@store')->name('property.create.store');
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // B22 - Property Detail
                    // ----------------------------------------------------------
                    Route::get('property/{property}', 'PropertyDetailController@index')->name('property.detail');
                    Route::post('property/{property}', 'PropertyDetailController@filter')->name('property.detail.filter');
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // B23 - Property Delivery
                    // ----------------------------------------------------------
                    Route::get('property/{property}/delivery', 'PropertyDeliveryController@index')->name('property.delivery');
                    Route::post('property/{property}/delivery', 'PropertyDeliveryController@store')->name('property.delivery.store');
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // B24 - Property Edit
                    // ----------------------------------------------------------
                    Route::get('property/{property}/edit', 'PropertyEditController@edit')->name('property.edit');
                    Route::post('property/{property}/update', 'PropertyEditController@update')->name('property.update');
                    // ----------------------------------------------------------
                });
                // --------------------------------------------------------------


                // --------------------------------------------------------------
                // B29 - Company / Homemaker User Log
                // --------------------------------------------------------------
                Route::get('homemaker/use_history', 'Company\User\CompanyUserUseHistoryController@index')->name('company.user_log');
                Route::post('homemaker/use_history', 'Company\User\CompanyUserUseHistoryController@filter')->name('company.user_log.filter');
                Route::get('homemaker/use_history/{any}', 'Company\User\CompanyUserUseHistoryController@index')->where('any', '.*');
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Version2 B26: Approval - List
                // --------------------------------------------------------------
                Route::namespace('Approval')->group(function () {
                    Route::get('approval', 'ApprovalListController@index')->name('approval');
                    Route::post('approval', 'ApprovalListController@filter')->name('approval.filter');

                    Route::get('approval/{any}', 'ApprovalEditController@index')->name('approval.property');
                    Route::post('approval/{any}', 'ApprovalEditController@update')->name('approval.update');
                });

                // --------------------------------------------------------------
                // [B30 : Scraping-Upload]Create view.
                // --------------------------------------------------------------
                Route::namespace('Scraping')->group(function () {
                    Route::get('scraping-upload', 'ScrapingController@index')->name('scraping');
                    Route::post('scraping-upload', 'ScrapingController@uploadFile')->name('scraping.upload');
                });

                //------------------------------------------------------------------
                // Chatwork route was used for develop of sending message funciton via chat work.
                // --------------------------------------------------------------
                //Route::namespace('Chatwork')->group(function () {
                //    Route::get('chatwork', 'ChatworkController@index')->name('chatwork');
                //    Route::get('chatwork/send-message', 'ChatworkController@message')->name('chatwork.message');
                //    Route::post('chatwork/send-message', 'ChatworkController@send')->name('chatwork.send');
                //});

                Route::prefix('lp')->name('lp.')->group(function () {
                    Route::namespace('Property')->group(function () {
                        Route::get('property', 'LpPropertyController@index')->name('property');
                        Route::post('property', 'LpPropertyController@filter')->name('property.filter');

                        Route::get('property/{id}/edit', 'LpPropertyEditController@index')->name('property.edit');
                        Route::post('property/{id}/update', 'LpPropertyEditController@update')->name('property.update');
                    });

                    Route::namespace('Approval')->group(function () {
                        Route::get('approval', 'LpApprovalController@index')->name('approval');
                        Route::post('approval', 'LpApprovalController@filter')->name('approval.filter');

                        Route::get('approval/{any}', 'LpApprovalEditController@show')->name('approval.property');
                        Route::post('approval/{any}', 'LpApprovalEditController@update')->name('approval.update');
                    });

                    Route::namespace('Scraping')->group(function () {
                        Route::get('scraping-upload', 'LpScrapingController@index')->name('scraping');
                        Route::post('scraping-upload', 'LpScrapingController@uploadFile')->name('scraping.upload');
                    });
                });
            });

            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Route group authorized for Super Admin and Company Admin
            // ------------------------------------------------------------------
            Route::group(['middleware' => ['role:super_admin,company_admin']], function () {
                // --------------------------------------------------------------
                // Route::resource('company', 'CompanyController')->only([ 'index', 'update', 'show', 'edit' ]);
                // Route::resource('company.user', 'UserController');
                // --------------------------------------------------------------
            });
            // ------------------------------------------------------------------

            //------------------------------------------------------------------
            // Route group authorized for Super Admin and Admin
            //------------------------------------------------------------------
            Route::group(['middleware' => ['role:super_admin,admin']], function () {
                Route::resource('admins', 'AdminController');
                Route::resource('news', 'NewsController');
            });
            //------------------------------------------------------------------

        });
    });

    // ----------------------------------------------------------------------
    // Manage namespaced routes
    // ----------------------------------------------------------------------
    Route::namespace('Manage')->prefix('manage')->name('manage.')->group(function () {
        Route::group(['middleware' => ['company_role:HOME_MAKER']], function () {

            // ------------------------------------------------------------------
            // Route group authorized for Super Admin only
            // ------------------------------------------------------------------
            Route::group(['middleware' => ['role:HOME_MAKER']], function () {

                Route::resource('/', 'DashboardController')->only(['index', 'show']);
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Customer
                // --------------------------------------------------------------
                Route::namespace('Customer')->group(function () {

                    // --------------------------------------------------------------
                    // A11 User User History All
                    // --------------------------------------------------------------
                    Route::get('customer_all_use_history', 'CustomerAllUseHistoryController@index')->name('customer.customer_all_use_history');
                    Route::post('customer_all_use_history', 'CustomerAllUseHistoryController@filter')->name('customer.customer_all_use_history.filter');

                    // --------------------------------------------------------------
                    // A10 - Customer ALL Search Histories
                    // --------------------------------------------------------------
                    Route::get('customer_all_search_history', 'CustomerAllSearchHistoryController@index')->name('customer_all_search_history');
                    Route::post('customer_all_search_history', 'CustomerAllSearchHistoryController@filter')->name('customer_all_search_history.filter');
                    Route::get('customer_all_search_history/{any}', 'CustomerAllSearchHistoryController@index')->where('any', '.*');
                    // --------------------------------------------------------------

                    Route::prefix('customer')->name('customer.')->group(function () {
                        // ----------------------------------------------------------
                        // A2 Customer Create
                        // ----------------------------------------------------------
                        Route::get('create', 'CustomerResourceController@create')->name('create');
                        Route::post('create', 'CustomerResourceController@store')->name('create.store');
                        // ----------------------------------------------------------

                        // --------------------------------------------------------------
                        // A4 User List
                        // --------------------------------------------------------------
                        Route::get('/', 'CustomerController@index')->name('index');
                        Route::post('/', 'CustomerController@filter')->name('filter');
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // Customer Details
                        // --------------------------------------------------------------
                        Route::prefix('{customer_id}')->group(function () {
                            // ----------------------------------------------------------
                            // A5 - Customer Detail
                            // ----------------------------------------------------------
                            Route::get('/', 'CustomerDetailController@index')->name('detail');
                            // ----------------------------------------------------------

                            // ----------------------------------------------------------
                            // A3 Customer Edit
                            // ----------------------------------------------------------
                            Route::get('edit', 'CustomerResourceController@edit')->name('edit');
                            Route::post('edit', 'CustomerResourceController@update')->name('edit.update');
                            // ----------------------------------------------------------
                            // --------------------------------------------------------------
                            // A6 - List of favorate properties
                            // --------------------------------------------------------------
                            Route::get('fav_history', 'CustomerFavoritePropertiesController@index')->name('fav_history');
                            Route::post('fav_history', 'CustomerFavoritePropertiesController@favfilter')->name('fav_history.favfilter');
                            // --------------------------------------------------

                            // --------------------------------------------------------------
                            // A8 - Customer Search History
                            // --------------------------------------------------------------
                            Route::get('search_history', 'CustomerSearchHistoryController@index')->name('search_history');
                            Route::post('search_history', 'CustomerSearchHistoryController@filter')->name('search_history.filter');
                            Route::get('search_history/{any}', 'CustomerSearchHistoryController@index')->where('any', '.*');
                            // --------------------------------------------------------------

                            // --------------------------------------------------------------
                            // A7 User Use History (Spesific User) List
                            // --------------------------------------------------------------
                            Route::get('use_history', 'CustomerUseHistoryController@index')->name('use_history.specific');
                            Route::post('use_history', 'CustomerUseHistoryController@filter')->name('use_history.specific.filter');
                            // --------------------------------------------------------------

                            // Customer flag toggle
                            // --------------------------------------------------
                            Route::post('flag', 'CustomerResourceController@flag')->name('flag');
                            // --------------------------------------------------

                            // --------------------------------------------------------------
                            // A8 - Customer Search History
                            // --------------------------------------------------------------
                            Route::get('search_history', 'CustomerSearchHistoryController@index')->name('search_history');
                            Route::post('search_history', 'CustomerSearchHistoryController@filter')->name('search_history.filter');
                            Route::get('search_history/{any}', 'CustomerSearchHistoryController@index')->where('any', '.*');
                            // --------------------------------------------------------------
                        });
                    });
                });

                // --------------------------------------------------------------
                // Property
                // --------------------------------------------------------------
                Route::resource('property', 'PropertyController');
                // --------------------------------------------------------------


                // --------------------------------------------------------------
                // A9 - Property detail filter
                // --------------------------------------------------------------
                Route::post('property/search', 'PropertyController@searchProperty')->name('property.search');
                Route::post('property/{property}', 'PropertyController@propertyLogActivityFilter')->name('property.show.filter');
                // --------------------------------------------------------------
            });
            //------------------------------------------------------------------

        });
    });
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // User login
    // ----------------------------------------------------------------------
    Route::group(['middleware' => 'auth:user'], function () {
        // ------------------------------------------------------------------
        Route::get('logout', 'Auth\CompanyUserLoginController@logout')->name('logout');
        Route::group(['middleware' => ['user_role:supervisor,operator']], function () {
            // --------------------------------------------------------------
            Route::get('user', 'Backend\UserController@editAsUserOwner')->name('userowner-edit');
            Route::post('user', 'Backend\UserController@updateAsUserOwner')->name('userowner-update');
            // --------------------------------------------------------------
        });
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Sample group pages
    // ----------------------------------------------------------------------
    Route::namespace('Sample')->prefix('sample')->name('sample.')->group(function () {
        // ------------------------------------------------------------------
        // Table Like sample
        // ------------------------------------------------------------------
        Route::get('tablelike', 'TableLikeController@index')->name('tablelike');
        Route::post('tablelike', 'TableLikeController@filter')->name('tablelike.filter');
        Route::get('tablelike/{any}', 'TableLikeController@index')->where('any', '.*');
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Form component sample
        // ----------------------------------------------------------------------
        //Route::get('form/components', 'FormComponentController@index')->name('form.components');
        // ----------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


Route::namespace('Backend')->group(function () {
    Route::resource('sample', 'SampleController')->only(['index', 'show']);
});

// ----------------------------------------------------------------------
// FRONTEND ROUTE
// ----------------------------------------------------------------------
Route::group(['middleware' => 'FrontendLocale'], function () {
    // ----------------------------------------------------------------------
    Route::get('terms', function () {
        return view('frontend.terms.index');
    })->name('terms');

    Route::get('overview', function () {
        return view('frontend.overview.index');
    })->name('overview');

    Route::get('explanation', function () {
        return view('frontend.explanation.index');
    })->name('explanation');
    // ----------------------------------------------------------------------
    // ------------------------------------------------------------------
    // C20 Description Function
    // ------------------------------------------------------------------

    Route::get('/description/function', function(){
        return view('frontend.description.index');
    })->name('description.function');

    // ----------------------------------------------------------------------
    // C18 Sign-up
    // ----------------------------------------------------------------------
    Route::get('signup', 'Frontend\CustomerController@signup')->name('signup');
    // Store customer email and token (C18-1)
    Route::post('signup', 'Frontend\CustomerController@signupWithEmail')->name('signup.email');
    // Store customer and desired area info (C18-3)
    Route::post('signup/register', 'Frontend\CustomerController@registerCustomer')->name('signup.register');
    // Redirect to disabled token page (C18-6)
    Route::get('disabled_token', 'Frontend\CustomerController@disabledToken')->name('signup.disabled_token');

    Route::get('signup/sendemail', 'Frontend\CustomerController@sendEmail')->name('signup.sendemail');
    Route::get('signup/input', 'Frontend\CustomerController@customerInfo')->name('signup.customer_info');
    // ----------------------------------------------------------------------
    // C16 Password Reissue
    // ----------------------------------------------------------------------
    Route::get('password_reissue', 'Frontend\PasswordResetController@index')->name('password_reissue');

    Route::prefix('password_reissue')->name('password_reissue.')->group(function () {
        Route::get('reset', 'Frontend\PasswordResetController@token')->name('token');
        Route::post('adapt', 'Frontend\PasswordResetController@adapt')->name('adapt');
        Route::post('sendemail', 'Frontend\PasswordResetController@sendemail')->name('sendemail');
        Route::post('forgotemail', 'Frontend\PasswordResetController@forgotemail')->name('forgotemail');
        Route::get('complete', 'Frontend\PasswordResetController@complete')->name('complete');
        Route::get('aftersend', 'Frontend\PasswordResetController@aftersend')->name('aftersend');
        // ----------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
    Route::get('privacy_policy', function () {
        return view('frontend.privacy_policy.index');
    })->name('privacy_policy');

    // ----------------------------------------------------------------------
    Route::get('company_information', function () {
        return view('frontend.company.index');
    })->name('company_information');
    // ----------------------------------------------------------------------

    Route::get('change_email', 'Frontend\EmailChangeController@index')->name('change_email');

    Route::namespace('Frontend')->name('frontend.')->group(function () {
        Route::prefix('change_email')->name('change_email.')->group(function () {
            // ----------------------------------------------------------------------
            // C18 Change password
            // ----------------------------------------------------------------------
            Route::get('adapt', 'EmailChangeController@update')->name('token');

            Route::post('edit', 'EmailChangeController@edit')->name('edit');
            Route::get('complete', 'EmailChangeController@complete')->name('complete');
            Route::get('aftersend', 'EmailChangeController@complete')->name('change_email.complete');
            // ----------------------------------------------------------------------
            // ----------------------------------------------------------------------
        });
    });

    // ----------------------------------------------------------------------
    // C13 Contact
    // ----------------------------------------------------------------------
    Route::get('contact', 'Frontend\ContactusController@index')->name('contact');
    Route::post('contact/store', 'Frontend\ContactusController@store')->name('contact.store');
    // ----------------------------------------------------------------------

    Route::namespace('Frontend')->name('frontend.')->group(function () {
        Route::get('change_email', 'EmailChangeController@index')->name('change_email');

        // ----------------------------------------------------------------------
        // Version2 C10-4: Menu - Account Settings
        // ----------------------------------------------------------------------
        Route::get('change_email/adapt/{token}', 'EmailChangeController@update')->name('change_email.token');
        // ----------------------------------------------------------------------

        Route::post('change_email/edit', 'EmailChangeController@edit')->name('change_email.edit');
        Route::get('change_email/complete', 'EmailChangeController@complete')->name('change_email.complete');
        Route::get('change_email/aftersend', 'EmailChangeController@aftersend')->name('change_email.aftersend');

        // ----------------------------------------------------------------------
        // C3 Map
        // ----------------------------------------------------------------------
        Route::get('map', 'MapController@map')->name('map');
        Route::post('map/store', 'MapController@store')->name('map.store');
        Route::get('old-map', 'MapController@mapOld')->name('map.old');
        // ----------------------------------------------------------------------
        // ----------------------------------------------------------------------
        // C11 - Property
        // ----------------------------------------------------------------------
        Route::namespace('Property')->group(function () {

            // ------------------------------------------------------------------
            Route::post('property/location/desired', 'PropertyListController@toggleDesiredLocation')->name('property.location.desired');
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Route::get('property', 'PropertyController@index')->name('property');
            // Route::post('property', 'PropertyController@addDesired')->name('property.adddesired');
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Property favorite toggle
            // ------------------------------------------------------------------
            Route::post('property/favorite', 'PropertyFavoriteController@toggle')->name('property.favorite');
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            Route::post('property/{id}/add-favorite', 'PropertyController@addFavorite')->name('property.addfavorite');
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // C12 - Property detail
            // ------------------------------------------------------------------
            Route::get('property-detail/{id}', 'PropertyController@show')->name('property.detail');
            Route::post('property-detail/{id}', 'PropertyController@store')->name('property.store');
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // C11 - Property List
            // ------------------------------------------------------------------
            Route::get('property', 'PropertyListController@index')->name('property.list');
            Route::get('property?location={location}', 'PropertyListController@index')->name('property.list.location');
            Route::post('property', 'PropertyListController@filter')->name('property.list.filter');
            Route::get('property/{any}', 'PropertyListController@index')->where('any', '.*');
            // ------------------------------------------------------------------
        });

    });

    Route::group(['middleware' => 'auth:user'], function () {
        // ----------------------------------------------------------------------
        // CUSTOMER logout
        // ----------------------------------------------------------------------
        Route::get('logout', 'Auth\CustomerLoginController@logout')->name('customer.logout');
        // ----------------------------------------------------------------------

        // --------------------------------------------------------------------------
        // Frontend route group
        // --------------------------------------------------------------------------
        Route::namespace('Frontend')->name('frontend.')->group(function () {

            // ----------------------------------------------------------------------
            // C2 User Start
            // ----------------------------------------------------------------------
            Route::get('customer_start', 'CustomerController@start')->name('start');
            // ----------------------------------------------------------------------

            // C3 MAP
            // ----------------------------------------------------------------------
            // Route::get('map', 'MapController@map')->name('map');
            // Route::get('old-map', 'MapController@mapOld')->name('map.old');
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // C10 Account Setting
            // ----------------------------------------------------------------------
            Route::get('account_settings', 'AccountSettingController@index')->name('account_settings');
            Route::post('account_settings/edit', 'AccountSettingController@edit')->name('account_settings.edit');
            Route::post('account_settings/cancel', 'AccountSettingController@cancel')->name('account_settings.cancel');
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // C18 Change password
            // ----------------------------------------------------------------------
            // Route::get('change_email', 'EmailChangeController@index')->name('change_email');
            // Route::post('change_email/edit', 'EmailChangeController@edit')->name('change_email.edit');
            // Route::get('change_email/complete', 'EmailChangeController@complete')->name('change_email.complete');
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // C5 News
            // ----------------------------------------------------------------------
            Route::namespace('News')->group(function () {
                Route::get('news', 'NewsController@index')->name('news');
                Route::post('news', 'NewsController@filter')->name('news.list.filter');
                Route::get('news/{any}', 'NewsController@index')->where('any', '.*');
            });
            // ----------------------------------------------------------------------

            Route::get('news-no-data', function () {
                return view('frontend.news.no-data');
            });
            // ----------------------------------------------------------------------
            // C7 Favorite List
            // ----------------------------------------------------------------------
            Route::get('fav-list', 'FavoriteController@index')->name('fav_list');
            Route::post('fav-list/filter', 'FavoriteController@filter')->name('fav-list.filter');
            Route::post('fav-list', 'FavoriteController@removeFavorite')->name('fav_list.removefavorite');
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // C8 History List
            // ----------------------------------------------------------------------
            Route::get('history', 'Customer\CustomerHistoryController@index')->name('history');
            Route::post('history', 'Customer\CustomerHistoryController@filter')->name('history.filter');
            // Route::post('history', 'HistoryController@removeFavorite')->name('history.removefavorite');
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // C9 My Settings
            // ----------------------------------------------------------------------
            Route::namespace('MenuSetting')->group(function () {
                Route::get('search_settings', 'MenuSettingController@index')->name('search_settings');
                Route::post('search_settings/remove', 'MenuSettingController@remove')->name('search_settings.remove');
                Route::post('search_settings/default', 'MenuSettingController@default')->name('search_settings.default');
                Route::post('search_settings/store', 'MenuSettingController@store')->name('search_settings.store');
                Route::post('search_settings/customer', 'MenuSettingController@customer')->name('search_settings.customer');
            });

            // ----------------------------------------------------------------------
            Route::get('confirm', function () {
                return view('frontend._base.confirm');
            });
            // ----------------------------------------------------------------------
            Route::get('error_page', function () {
                return view('frontend._base.error');
            });
            // ----------------------------------------------------------------------

        });
    });

    Route::namespace('Frontend')->name('frontend.')->group(function () {
        Route::prefix('lp')->name('lp.')->group(function () {
            Route::get('map', 'LpMapController@map')->name('map');
        });
    });
    // --------------------------------------------------------------------------
});
// ----------------------------------------------------------------------
