<?php
// --------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// API Routes
// --------------------------------------------------------------------------
// Here is where you can register API routes for your application. These
// routes are loaded by the RouteServiceProvider within a group which
// is assigned the "api" middleware group. Enjoy building your API!
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
Route::namespace('Api')->prefix('v1')->group(function () {
    // ----------------------------------------------------------------------
    // Company routes
    // ----------------------------------------------------------------------
    Route::post('companies', 'ApiCompanyController@all')->name('api.company.all');
    Route::post('company', 'ApiCompanyController@single')->name('api.company.single');
    Route::post('company/persons', 'ApiCompanyController@users')->name('api.company.persons');
    Route::post('company/persons/users', 'ApiCompanyController@persons')->name('api.company.persons.users');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Property Edit (B24) ( for deleting photos and flyers ) routes
    // ----------------------------------------------------------------------
    Route::post('property/photos', 'ApiPropertyController@deletePhotos')->name('api.property.photos');
    Route::post('property/flyers', 'ApiPropertyController@deleteFlyers')->name('api.property.flyers');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Company users
    // Accepts data format of { company: companyID }
    // ----------------------------------------------------------------------
    Route::post('company/users', 'ApiCompanyController@users')->name('api.company.users');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // CITY routes
    // ----------------------------------------------------------------------
    Route::post('city/citiesAreas', 'ApiCityController@cityAreaList')->name('api.company.cityAreaList');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /* PLACE */
    // ----------------------------------------------------------------------
    Route::get('places', 'ApiPlaceController@all');
    Route::get('place/{id}', 'ApiPlaceController@single');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /* USER */
    // ----------------------------------------------------------------------
    Route::get('users', 'ApiUserController@all');
    Route::get('user/{id}', 'ApiUserController@single');
    Route::get('company_users/{id}', 'ApiUserController@companyUsers');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // User customers
    // Accepts data format of { user: userID }
    // ----------------------------------------------------------------------
    Route::post('user/customers', 'ApiUserController@customers')->name('api.user.customers');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Check if user email exists
    // ----------------------------------------------------------------------
    Route::post('user/email/exists', 'ApiUserController@emailExists')->name('api.email.exists');
    Route::post('customer/email/exists', 'ApiCustomerController@customerEmailExists')->name('api.customer_email.exists');
    Route::post('customer/email/check', 'ApiCustomerController@customerEmailCheck')->name('api.customer_email.check');
    Route::post('customer/email/password', 'ApiCustomerController@customerEmailCheckPassword')->name('api.customer_email.check.password');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /*POSTCODE*/
    // ----------------------------------------------------------------------
    Route::get('postcode/{postcode}', 'ApiPostcodeController@address');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    Route::post('map/getpropertylist', 'MapController@getPropertyList')->name('api.propertylist');
    Route::post('map/getUserSetting', 'MapController@getUserSetting');
    // ----------------------------------------------------------------------
    Route::post('map/customer_search_history_store', 'CustomerSearcHistoryController@store')->name('api.customer_search_history.post');

    //-----------------------------------------------------------------------
    // C7 Favorite update
    //-----------------------------------------------------------------------
    //Route::post('fav-list/{id}', 'FavoriteController@updateData')->name('api.fav_list.update');
    //-----------------------------------------------------------------------

    //-----------------------------------------------------------------------
    // C9 Location
    //-----------------------------------------------------------------------    
    Route::post('prefecture', 'ApiLocationController@prefecture')->name('api.location.prefecture');
    Route::post('prefecture-areas', 'ApiLocationController@prefecture_areas')->name('api.location.prefecture_areas');
    Route::post('prefecture-area', 'ApiLocationController@prefecture_area')->name('api.location.prefecture_area');
    Route::post('city', 'ApiLocationController@city')->name('api.location.city');    
    Route::post('city/town', 'ApiLocationController@town')->name('api.location.town');
    Route::post('cities_areas', 'ApiLocationController@cities_areas')->name('api.location.cities_areas');
    //-----------------------------------------------------------------------

    //-----------------------------------------------------------------------
    // C18-3 Get Location
    //-----------------------------------------------------------------------
    Route::post('signup/location', 'ApiSignUpController@getLocation')->name('api.signup.get_location');
    //-----------------------------------------------------------------------

    //-----------------------------------------------------------------------
    // B21 : Property list - Back end
    //-----------------------------------------------------------------------    
    // Upload Photo and Update Photo
    //-----------------------------------------------------------------------    
    Route::post('upload-photos/{propertyID}', 'ApiPropertyDialogController@uploadPhotos')->name('api.property.upload.photos');
    Route::post('update-photos/{propertyID}', 'ApiPropertyDialogController@updatePhotos')->name('api.property.update.photos');
    Route::post('delete-photos', 'ApiPropertyDialogController@deletePhotos')->name('api.property.delete.photos');
    //-----------------------------------------------------------------------    
    // Upload Flyers and Update Flyers
    //-----------------------------------------------------------------------    
    Route::post('upload-flyers/{propertyID}', 'ApiPropertyDialogController@uploadFlyers')->name('api.property.upload.flyers');
    Route::post('update-flyers/{propertyID}', 'ApiPropertyDialogController@updateFlyers')->name('api.property.update.flyers');
    Route::post('delete-flyers', 'ApiPropertyDialogController@deleteFlyers')->name('api.property.delete.flyers');
    //-----------------------------------------------------------------------
    // Property Status
    //-----------------------------------------------------------------------   
    Route::post('update-status', 'ApiPropertyDialogController@updateStatus')->name('api.property.update.status');
    //-----------------------------------------------------------------------
    // Property Arrow Button Photo
    //-----------------------------------------------------------------------   
    Route::post('order-photos/next/{photoID}', 'ApiPropertyDialogController@orderPhotoNext')->name('api.property.arrow.right.photos');
    Route::post('order-photos/prev/{photoID}', 'ApiPropertyDialogController@orderPhotoPrev')->name('api.property.arrow.left.photos');
    //-----------------------------------------------------------------------
    // Property Arrow Button Flyers
    //-----------------------------------------------------------------------   
    Route::post('order-flyers/next/{photoID}', 'ApiPropertyDialogController@orderFlyerNext')->name('api.property.arrow.right.flyers');
    Route::post('order-flyers/prev/{photoID}', 'ApiPropertyDialogController@orderFlyerPrev')->name('api.property.arrow.left.flyers');
    //-----------------------------------------------------------------------
    // Property Order Photos & Flyers
    // Property ID has to be passed along with the order data
    //-----------------------------------------------------------------------
    Route::post('asset-order', 'ApiPropertyDialogController@assetOrder')->name('api.property.asset.order');    
    //-----------------------------------------------------------------------
    //-----------------------------------------------------------------------
    // Property Publication Publish    
    //-----------------------------------------------------------------------
    Route::post('publish', 'ApiPropertyDialogController@publishRange')->name('api.property.publish.company');
    //-----------------------------------------------------------------------
    //-----------------------------------------------------------------------
    // [Version3.2 C40: Map LP] Create view
    //-----------------------------------------------------------------------
    Route::post('map/getLpPropertylist', 'LpMapController@getPropertyList')->name('api.lp.propertylist');
    Route::post('map/getLpUserSetting', 'LpMapController@getUserSetting');
    //-----------------------------------------------------------------------
    // Device UUID
    //-----------------------------------------------------------------------
    Route::post('property/uuid/{id}', 'ApiPropertyController@deviceUuid')->name('api.property.uuid');
    //-----------------------------------------------------------------------
    // Checking UUID
    //-----------------------------------------------------------------------
    Route::post('property/uuid', 'ApiPropertyController@checkUuidProperty')->name('api.property.check.uuid');
    //-----------------------------------------------------------------------
    // API GET PROPERTY INFO FOR LP MAP
    //-----------------------------------------------------------------------
    Route::get('get-property-info', 'LpMapController@getProperty')->name('api.property.get.info');
});
