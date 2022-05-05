<?php

Route::get('/', function () {
	return view('login');
});

Auth::routes();

Route::post('/member/save', 'MemberController@save');
Route::post('/log/save', 'MemberActionController@save');
Route::post('/membership/log/save', 'MemberActionController@membershipSave');
Route::post('/sales/save', 'MemberActionController@saveSales');
Route::get('/home', 'CustomerController@customerMembershipsView');

Route::get('/member/data', 'CustomerController@listCustomerMemberships');
Route::get('/member/data/users', 'CustomerController@listUsers');
Route::get('/member/data/members', 'CustomerController@listMembers');
Route::get('/member/data/members/active', 'CustomerController@listActiveMembers');
Route::get('/member/data/revenue', 'CustomerController@listRevenue');
Route::post('/member/deactivate', 'CustomerController@deactivate');
Route::get('member/paa/agents', 'CustomerController@paaAgents');
Route::get('member/paa/agents/data', 'CustomerController@paaAgentsData');
Route::get('member/paa/agents/search/data', 'CustomerController@paaAgentsSearchData');
Route::get('member/paa/agent/{id}', 'CustomerController@paaAgentView');
Route::get('member/paa/agent/get/data', 'CustomerController@paaAgentViewData');
Route::get('member/mpaa/agents', 'CustomerController@mpaaAgents');
Route::get('member/mpaa/agents/data', 'CustomerController@mpaaAgentsData');
Route::get('member/mpaa/agent/{id}', 'CustomerController@mpaaAgentView');
Route::get('member/mpaa/agent/get/data', 'CustomerController@mpaaAgentViewData');
Route::get('member/mpaa/agent/search/data', 'CustomerController@mpaaAgentsSearchData');
Route::get('member/ad/activation', 'CustomerController@adActivation');

Route::get('/member/expire/tobe-expire/{time?}', 'CustomerController@toBeExpire');
Route::get('/member/expire/expired/{time?}', 'CustomerController@expired');
Route::get('/member/none', 'CustomerController@noneMembershipsView');
Route::get('/member/none-data', 'CustomerController@noneMemberships');

Route::get('/payment/expire/tobe-expire/{time?}', 'PaymentController@toBeExpire');
Route::get('/payment/expire/expired/{time?}', 'PaymentController@expired');
Route::get('/payment/expire/hunters', 'PaymentController@expiredHunter');
Route::get('/payment/expire/hunters/data', 'PaymentController@expiredHunterData');

Route::post('/payment/expire/save', 'PaymentController@savePaymentExpire');
Route::get('/payment/invoice/add', 'PaymentController@addInvoiced');

Route::get('/ads/manage/to-be-removed/{user?}', 'AdsController@viewToBeRemoved');
Route::get('/ads/manage/to-be-removed-data/{user?}', 'AdsController@toBeRemoved');
Route::get('/ads/manage/without-payment-data', 'AdsController@withoutPayment');
Route::get('/ads/manage/limit-exceed', 'AdsController@limitExceed');
Route::get('/ads/manage/null-ad-data', 'AdsController@nullADData');
Route::get('/ads/manage/app-ad-data', 'AdsController@appADData');
Route::get('/ads/manage/exp-upgrade-ad-data', 'AdsController@expUpgradeADData');
Route::get('/ads/summary', 'AdsController@viewSummary');
Route::get('/ads/activate_commercial', 'AdsController@activateCommercial');
Route::post('/ads/activate_commercial', 'AdsController@postActivateCommercial');



Route::post('/customer/details', 'CustomerController@getByUID');
Route::get('/customer/details', 'CustomerController@getByUID');
Route::get('/customer/ads/view', 'CustomerController@getAdsView');
Route::get('/customer/activity/data', 'CustomerController@getCustomerActivityData');

// By Sasi Spenzer 2021-06-07 ** WFH
Route::get('/customer/register', 'CustomerController@getCustomerRegister');
Route::get('customer/upload', 'CustomerController@setUploadForm');
Route::get('/hunters/list', 'MemberController@listHunters');

// By Sasi Spenzer 2021-10-27 ** WFH
Route::get('/hunters/pending-payment-system', 'MemberController@pendingPaymentSystem');
Route::get('/hunters/pending-payment-system/data', 'MemberController@systemPendingData');
Route::get('/pvt-sellers-expired/data', 'MemberController@pvtExpData');
Route::get('/hunters/pvt-sellers-expired', 'MemberController@pvtsellersExp');


Route::post('/customer/register/submit', 'CustomerController@getCustomerRegisterData');
Route::post('/customer/upload', 'CustomerController@uploadCustomerData');

// Routes By Sasi Spenzer 2021-04-29 *** WFH
Route::post('/leads/process', 'AdsController@checkLeads');

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/widgets', 'DashboardController@getDetails');
Route::get('/dashboard/users', 'DashboardController@users');
Route::get('/dashboard/members', 'DashboardController@members');
Route::get('/dashboard/my_revenue/{am}', 'DashboardController@revenueMy');
Route::get('/dashboard/revenue/{am?}', 'DashboardController@revenue');
Route::get('/dashboard/pending_payment/{data?}/{am?}', 'DashboardController@pendingPayment');
Route::get('/dashboard/my_target/{data?}', 'TargetController@myTarget');
Route::get('/dashboard/group_target', 'TargetController@groupTarget');
Route::post('/dashboard/group_target/save', 'TargetController@saveTarget');
Route::post('/dashboard/group_mem_target/save', 'TargetController@saveMemTarget');

//For new dashboard
Route::get('/user/dashboard', 'DashboardController@userDashboard');
Route::get('/user/dashboard/widgets', 'DashboardController@getUserDetails');

Route::get('/category/wise/member/count', 'MemberController@getCategoryWiseMemberCount');
Route::get('/get-package-price', 'PaymentController@getRatesInEdit');
Route::get('/list/ads/by/customer/', 'CustomerController@listAdsByCustomer');
Route::get('/view/list/ads/by/customer', 'CustomerController@listAdsByCustomerView');
Route::get('/autocomplete/customer/email', 'CustomerController@AutocompleteCustomerEmail');
Route::post('/activate/ad', 'AdvertisementController@ActivateAd');
Route::post('/autoboost/ad', 'AdvertisementController@AutoboostAd');
Route::get('/customer/ad/count', 'AdvertisementController@getCustomerAdCount');

//Route::post('/sigle-ads/all', 'AdvertisementController@ActivateAd');
Route::get('/sigle-ads/pending-payment', 'AdvertisementController@getSingleAd');
Route::get('/sigle-ads/pending-payment/data', 'AdvertisementController@postSingleAd');
Route::get('/sigle-ads/pending-payment-all', 'AdvertisementController@getSingleAdAll');
Route::get('/sigle-ads/pending-payment-all/data', 'AdvertisementController@postSingleAdAll');
Route::get('/sigle-ads/pending-payment-upgrade', 'AdvertisementController@getSingleAdUpgrade');

Route::get('/single-ads/active_ads', 'AdvertisementController@getActiveAds'); // By Sasi Spenzer 2021-06-03 **
Route::get('/single-ads/active_ads/data', 'AdvertisementController@getActiveSingleAdsAll'); // By Sasi Spenzer 2021-06-03 **

Route::get('/sigle-ads/pending-payment-upgrade/data', 'AdvertisementController@postSingleAdUpgrade');


Route::group( ['middleware' => 'auth' ], function(){
    Route::get('/check/mobile', 'AdminController@getCheckNumbers');
	Route::post('/check/mobile', 'AdminController@postCheckNumbers');
    Route::get('/replace/mobile', 'AdminController@getReplaceNumbers');
	Route::post('/replace/mobile', 'AdminController@postReplaceNumber');
	Route::post('/replace/mobile/ajax', 'AdminController@ajaxReplaceNumber');
	Route::get('/delete/mobile', 'AdminController@getDeleteNumbers');
	Route::post('/delete/mobile', 'AdminController@postDeleteNumbers');
	Route::post('/delete/mobile/{mid}', 'AdminController@ajaxDeleteNumbers');
	Route::get('/delete/user', 'AdminController@getDeleteUser');
	Route::post('/delete/user', 'AdminController@postDeleteUser');
	Route::post('/delete/user/confirm', 'AdminController@confirmDeleteUser');
    Route::get('/password/reset', 'AdminController@resetPassword');
    Route::post('/password/reset', 'AdminController@resetPasswordSubmit');
    Route::post('/password/reset-confirm', 'AdminController@resetPasswordConfirm');
});

Route::get('/test/{test}', 'TestController@index');

Route::get('/cronjob/{script}', 'CronjobController@index');

Route::get('/version/{version}', 'VersionController@index');

//invoice section

Route::get('/invoicecustomer', 'InvoieController@index');

Route::post('/article/upload', 'ArticleController@upload');
Route::resource('/articles', 'ArticleController');
Route::resource('/metatitles', 'MetaController');
Route::resource('/auto-boost', 'AutoboostController');

Route::group( ['middleware' => 'auth' ], function(){

    // Area
    Route::get('edit-city/{id}', 'CityController@editCityData')->name('edit.city');
    Route::post('save-edit-city', 'CityController@saveCityData')->name('submit.edit.city.details');
    Route::get('city-list', 'CityController@listCity')->name('list.city');
    Route::get('convert-div-to-paragraph', 'CityController@convertDivToParagraph')->name('div.to.paragraph');

    // Condo
    Route::get('json-condo-list', 'CondoController@jsonCityList')->name('json.condo.list');
    Route::get('condo-list', 'CondoController@condoList')->name('condo.list');
    Route::get('edit-condo-details/{id}', 'CondoController@editCondoDetailsForm')->name('form.condo.details');
    Route::get('add-condo-details', 'CondoController@editCondoDetailsForm')->name('form.add.condo.details');
    Route::post('save-condo-details', 'CondoController@saveCondoDetails')->name('submit.condo.details');
    Route::get('delete-condo/{id}', 'CondoController@deleteCondo')->name('delete.condo');
    Route::get('move-old-condo-images-to-new-directory', 'CondoController@moveOldCondoImagesToNewDirectory')
                                                                ->name('move.old.condo.images.to.new.directory');

    // Point of Interest
    Route::get('point-of-interest-list', 'PointOfInterest@poiList')->name('point.of.interest.list');
    Route::get('point-of-interest-list-json', 'PointOfInterest@poiListJSON')->name('point.of.interest.list.json');
    Route::get('create-point-of-interest', 'PointOfInterest@editPoi')->name('create.point.of.interest');
    Route::get('edit-point-of-interest/{id}', 'PointOfInterest@editPoi')->name('edit.point.of.interest');
    Route::post('submit-poi-details', 'PointOfInterest@submitPOIDetails')->name('submit.poi.details');
    Route::get('delete-poi/{id}', 'PointOfInterest@deletePointOfInterest')->name('delete.point.of.interest');
    Route::get('delete-poi-image/{id}', 'PointOfInterest@deletePointOfInterestImage')->name('delete.poi.image');

    Route::get('customer/details/approval/{id}', 'MemberController@membershipDataApproval');
    Route::get('customer/details/payment/approval/{id}', 'MemberController@membershipPaymentApproval');

});

Route::get('get-json', 'CityController@json');
Route::get('city-list-json', 'CityController@cityListJson')->name('city.list.json');

//For membership expire cron
Route::get('public/membership/expire', 'CronjobController@membershipExpire');

//For customer list view
Route::get('customer/all', 'CustomerController@listOfCustomer');
Route::get('customer/all/data', 'CustomerController@listOfCustomerData');
Route::get('customer/archive', 'CustomerController@listOfArchiveCustomer');
Route::get('customer/archive/data', 'CustomerController@listOfArchiveCustomerData');

Route::post('/membership/data/save', 'MemberController@membershipSave');
Route::post('/membership/details/save', 'MemberController@membershipDetailsSave');
Route::get('/membership/details/save', 'MemberController@membershipDetailsSave');
Route::get('/membership/pending/approval/save', 'MemberController@membershipPendingApprovalSave');

Route::get('/member/dashboard', 'MemberController@memberDashboardView');
Route::get('/member/dashboard/data', 'MemberController@memberDashboardData');
Route::get('/member/dashboard/store', 'AdsController@memberDashboardStoreData');
Route::get('/member/expire/remove/am', 'AdsController@memberExpireRemoveAm');
Route::get('/customer/payment/{rand_string1}/{customer_id}/{rand_string2}', 'AdsController@customerPayment');

Route::get('/agent/activity/report', 'MemberController@agentActivity');
Route::get('/agent/activity/data', 'MemberController@agentActivityData');

Route::get('/user/available/ad-count', 'CustomerController@userAgentAdCount');
Route::get('/ads/offer/type', 'AdsController@offerType');
Route::post('/ads/offer/type', 'AdsController@offerTypeData');

Route::get('/member/billing/data', 'MemberController@userBillingData');
Route::get('/member/addons/data', 'MemberController@userAddonsData');

Route::get('/my/list', 'MemberController@myListTable');
Route::get('/my/list/data', 'MemberController@myListTableData');

// By Sasi Spenzer 2021-10-27 ** WFH *
Route::get('/hunters_activity', 'MemberController@huntersActivity');

