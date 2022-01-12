<?php

use App\Http\Controllers\admin\CountryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\ManagerController as ManagerController;
use App\Http\Controllers\Manager\PartnerController as ManagerPartnerController;
use App\Http\Controllers\TrajetController as TrajetController;
use App\Http\Controllers\HoraireController as HoraireController;
use App\Http\Controllers\GroupController as GroupController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\TrackingController as TrackingController;
use App\Http\Controllers\OfferController as OfferController;
use App\Http\Controllers\ProspectCommentsController as ProspectCommentsController;
use App\Http\Controllers\RelationshipController as RelationshipController;
use App\Http\Controllers\MarketingSearchController as MarketingSearchController;
use App\Http\Controllers\MailController as MailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/resources/app/uploads/{filename}', function($filename){
    $path = resource_path() . '/app/uploads/' . $filename;

    if(!File::exists($path)) {
        return response()->json(['message' => 'Image not found.'], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/test', [TrajetController::class, 'test']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/addmanagers', [App\Http\Controllers\Admin\ManagerController::class, 'savemanager'])->name('addmanagers');

/// USER WITHOUT RIGHTS ROUTES ///
Route::group(['as'=>'user.','prefix' => 'user','namespace'=>'User','middleware'=>['auth','user']], function () {
    Route::get('dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
});

/// MANAGER ROUTES ///
Route::group(['as'=>'manager.','prefix' => 'manager','middleware'=>['auth','manager']], function () {
    
    Route::get('dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'index'])->name('dashboard');
    
    /// Trajets (routes) ///
    Route::resource('trajets',TrajetController::class);
    Route::delete('trajets/destroyer/{id}', 'App\Http\Controllers\TrajetController@destroyer');
    Route::get('searchcity', [TrajetController::class, 'searchcity'])->name('searchcity');
    Route::get('duplicate', [TrajetController::class, 'duplicate'])->name('duplicate');
    Route::get('matching', [TrajetController::class, 'matching'])->name('matching');
    Route::post('trajets/getRouteList', [TrajetController::class, 'getRouteList'])->name('getRouteList');

    /// Partners ///
    // Partner routes
    Route::resource('partners',ManagerPartnerController::class, [
      'only' => ['index', 'store'],
      'except' => ['create', 'edit', 'destroy', 'show', 'update']
    ]);
    Route::get('partners', [ManagerPartnerController::class, 'index'])->name('partners.index');
    Route::get('partners/getPartners', [ManagerPartnerController::class, 'getPartners'])->name('partners.getPartners');
    Route::get('partners/{id}', 'App\Http\Controllers\Manager\PartnerController@show');

    /// Emails ///
    // Email routes
    Route::resource('mails', MailController::class, [
        'only' => ['index', 'store'],
        'except' => ['create', 'edit', 'destroy', 'show', 'update']
    ]);

    Route::get('mails', [MailController::class, 'index'])->name('mails.index');
    Route::get('mails/getMails', [MailController::class, 'getMails'])->name('mails.getMails');
    Route::delete('mails/destroyer/{id}', 'App\Http\Controllers\MailController@destroyer');
    Route::get('mails/{id}', 'App\Http\Controllers\MailController@show');
    Route::get('mails/edit/{id}', 'App\Http\Controllers\MailController@edit');
    Route::post('mails/update/{id}', 'App\Http\Controllers\MailController@update');
    Route::post('mails/sendMail/', 'App\Http\Controllers\MailController@sendMail')->name('mails.sendMail');

    // Group routes
    Route::resource('groups',GroupController::class, [
      'only' => ['index', 'store'],
      'except' => ['create', 'edit', 'destroy', 'show', 'update']
    ]);

    Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('groups/getGroups', [GroupController::class, 'getGroups'])->name('groups.getGroups');
    Route::get('groups/openModalNew', [GroupController::class, 'openModalNew'])->name('groups.openModalNew');
    Route::delete('groups/destroyer/{id}', 'App\Http\Controllers\GroupController@destroyer');
    Route::get('groups/{id}', 'App\Http\Controllers\GroupController@show');
    Route::get('groups/edit/{id}', 'App\Http\Controllers\GroupController@edit');
    Route::post('groups/update/{id}', 'App\Http\Controllers\GroupController@update');

    /* /// Are these even used routes ? Because everything looks handled in the Group Controller.
    Route::get('showGroup/{showGroup}', [RelationshipController::class, 'showGroup']);
    Route::get('groups/showPartner/{group_id}', [RelationshipController::class, 'showPartner'])->name('groups.showPartner');
    Route::post('groups/savePartnerToGroup', [RelationshipController::class, 'savePartnerToGroup'])->name('groups.savePartnerToGroup');
    Route::get('groups/deletePartnerFromGroup/{group_id}/{partner_id}', [RelationshipController::class, 'deletePartnerFromGroup'])->name('groups.deletePartnerFromGroup');
    */

    /// Prospects ///
    // Marketing Search routes
    Route::resource('prospect/marketingsearch', MarketingSearchController::class, [
        'except' => ['show']
    ]);
    Route::get('prospect/marketingsearch', [MarketingSearchController::class, 'index']);
    Route::get('prospect/marketingsearch/getMarketingSearches', [MarketingSearchController::class, 'getMarketingSearches'])->name('marketingsearch.getMarketingSearches');
    Route::get('prospect/transform/{id}', [MarketingSearchController::class, 'transform'])->name('transform');
    /*
    Route::get('prospect/transform/{id}', function ($id) {
        return view('manager/prospects/marketingsearches/create_prospect', ['id' => $id]);
    })->name('transform');
    */

    // Prospect routes
    Route::put('prospect/book/{id}', 'App\Http\Controllers\ProspectController@book')->name('prospect.book');
    Route::resource('prospect', ProspectController::class);
    Route::get('prospects', [ProspectController::class, 'index']);
    Route::get('prospects/getProspects', [ProspectController::class, 'getProspects'])->name('prospects.getProspects');
    
    Route::get('formBooking/{id}', function ($id) {
        return view('manager.prospects.booking', ['id' => $id]);
    })->name('formBooking');

    Route::get('result/{id}', function ($id) {
        return view('manager/prospects/trackings/create', ['id' => $id]);
    })->name('result');

    // Tracking routes
    Route::resource('prospect/tracking', TrackingController::class, [
        'only' => ['create', 'store', 'edit', 'update'],
        'except' => ['index', 'show', 'destroy']
    ]);

    // Offer routes
    Route::resource('prospect/offer', OfferController::class, [
        'only' => ['create', 'store', 'edit', 'update'],
        'except' => ['index', 'show', 'destroy']
    ]);
    
    // Comments routes
    Route::resource('prospect/comment', ProspectCommentsController::class, [
        'only' => ['create', 'store', 'edit', 'update'],
        'except' => ['index', 'show', 'destroy']
    ]);

    // Prospect FAQ
    Route::get('prospects/faq',function() {
        return view('manager.prospects.faq');
    });

});

/// ADMIN ROUTES ///
Route::group(['as'=>'admin.','prefix' => 'admin','middleware'=>['auth','admin']], function () {
    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    // Route::get('managers', [App\Http\Controllers\Admin\ManagerController::class, 'index'])->name('managers');
    Route::resource('managers',ManagerController::class);
    Route::resource('horaires',HoraireController::class);
    Route::get('searchhour', [HoraireController::class, 'searchhour'])->name('searchhour');
    Route::get('manager/activate',[ManagerController::class,'activatemanager']);
    Route::put('users/password',[ManagerController::class,'editpassword']);
    
    /// Partners ///
    // Partner routes
    Route::resource('partners',AdminPartnerController::class, [
      'only' => ['index', 'store'],
      'except' => ['create', 'edit', 'destroy', 'show', 'update']
    ]);
    Route::get('partners', [AdminPartnerController::class, 'index'])->name('partners.index');
    Route::get('partners/getPartners', [AdminPartnerController::class, 'getPartners'])->name('partners.getPartners');
    Route::get('partners/countryAuto', [AdminPartnerController::class, 'countryAuto'])->name('partners.countryAuto');
    Route::get('partners/managerAuto', [AdminPartnerController::class, 'managerAuto'])->name('partners.managerAuto');
    Route::delete('partners/destroyer/{id}', 'App\Http\Controllers\Admin\PartnerController@destroyer');
    Route::get('partners/{id}', 'App\Http\Controllers\Admin\PartnerController@show');
    Route::get('partners/edit/{id}', 'App\Http\Controllers\Admin\PartnerController@edit');
    Route::post('partners/update/{id}', 'App\Http\Controllers\Admin\PartnerController@update');    
    // Route::post('partners/partnerStatus/{partnerStatus}', [AdminPartnerController::class, 'partnerStatus'])->name('partners.partnerStatus');    

    /// Countries ///
    Route::get('country', [CountryController::class, 'index'])->name('index');
    Route::get('country/getCountries', [CountryController::class, 'getCountries'])->name('country.getCountries');
});
 