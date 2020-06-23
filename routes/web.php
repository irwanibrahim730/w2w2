<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//user
Route::get('/user', 'UserController@index');
Route::get('/user/detail', 'UserController@show');
Route::post('/user/add', 'UserController@store');
Route::post('/user/update', 'UserController@update');
Route::delete('/user/delete', 'UserController@destroy');
Route::post('/user/addpackage', 'UserController@addpackage');
Route::get('/ver12asdaasaas/verabcsadasdsdfss', 'UserController@verificationemail');
Route::get('/user/resendverification', 'UserController@resendverification');
Route::post('/user/updatestatus', 'UserController@updatestatus');

//package
Route::get('/packages', 'PackageController@index');
Route::get('/packages/detail', 'PackageController@show');
Route::post('/packages/add', 'PackageController@store');
Route::post('/packages/update', 'PackageController@update');
Route::delete('/packages/delete', 'PackageController@destroy');

//token
Route::get('/token', 'TokenController@index');
Route::get('/token/detail', 'TokenController@show');
Route::post('/token/add', 'TokenController@store');
Route::post('/token/update', 'TokenController@edit');
Route::delete('/token/delete', 'TokenController@destroy');
Route::post('/token/addtoken', 'TokenController@addtoken');
Route::post('/token/give', 'TokenController@givetoken');
Route::get('/token/checkbalance', 'TokenController@checkbalance');
Route::get('/token/mytoken', 'TokenController@mytoken');


//inbox
Route::get('/inbox', 'InboxController@index');
Route::get('/inbox/detail', 'InboxController@show');
Route::post('/inbox/add', 'InboxController@store');
Route::post('/inbox/update', 'InboxController@edit');
Route::delete('/inbox/delete', 'InboxController@destroy');

//news
Route::get('/news', 'NewsController@index');
Route::get('/news/detail', 'NewsController@show');
Route::get('/news/status', 'NewsController@status');
Route::post('/news/add', 'NewsController@store');
Route::post('/news/update', 'NewsController@update');
Route::delete('/news/delete', 'NewsController@destroy');

//login
Route::post('/login', 'LoginController@signin');

//product
Route::get('/product', 'ProductController@index');
Route::get('/product/listcategory', 'ProductController@listcategory');
Route::get('/product/listuserproduct', 'ProductController@listuserproduct');
Route::get('/product/productstatus', 'ProductController@productstatus');
Route::post('/product/add','ProductController@store');
Route::post('/product/update','ProductController@update');
Route::delete('/product/delete','ProductController@destroy');
Route::get('/product/detail','ProductController@show');
Route::get('/product/type','ProductController@mainstatus');
Route::get('/product/usertype','ProductController@usertype');
Route::get('/listing','ProductController@premiumlist');
Route::post('product/publish','ProductController@publishstatus');
Route::post('product/availability','ProductController@availability');
Route::get('product/premium','ProductController@listpremium');
Route::get('product/renewproduct', 'ProductController@renewproduct');

Route::get('/product/userproduct','ProductController@userproduct');

//admin
Route::post('/admin/approve','AdminController@approve');
Route::post('/admin/reject','AdminController@reject');
Route::get('/admin/list','AdminController@listapproval');
Route::post('/admin/block','UserController@block');
Route::post('/admin/unblock','UserController@unblock');
Route::get('/admin/listproduct', 'AdminController@listproduct');

//category
Route::post('/category/add','CategoryController@addcategory');
Route::get('/category','CategoryController@listcategory');
Route::get('/category/sub','CategoryController@listsubcategory');
Route::delete('/category/delete','CategoryController@delete');
Route::get('/category/mainstatus','CategoryController@mainstatus');
Route::get('/category/substatus','CategoryController@substatus');
Route::post('/category/edit','CategoryController@edit');
Route::get('/category/detail','CategoryController@detail');

//comment
Route::post('/comment','CommentController@addcomment');
Route::get('/comment/list','CommentController@listcomment');
Route::delete('/comment/delete','CommentController@delete');
Route::post('/comment/status','CommentController@status');
Route::get('/comment/all','CommentController@list');
Route::get('/comment/detail','CommentController@detail');
Route::get('/comment/listing','CommentController@listid');

//enquiry
Route::post('/enquiry','EnquiryController@store');
Route::get('/enquiry/list','EnquiryController@list');
Route::delete('/enquiry/delete','EnquiryController@delete');
Route::get('/enquiry/detail','EnquiryController@detail');

//banner
Route::post('/banner','BannerController@store');
Route::get('/banner/list','BannerController@list');
Route::get('/banner/status','BannerController@status');
Route::delete('/banner/delete','BannerController@delete');
Route::get('/banner/detail', 'BannerController@detail');
Route::post('/banner/edit','BannerController@edit');

//notification
Route::get('/notification','ProductController@listapproved');
Route::get('/notification/expired','ProductController@listexpired');

//reservation
Route::post('/reserve','ReserveController@reserve');
Route::get('/reserve/list','ReserveController@listreserved');
Route::post('reserve/approve','ReserveController@approve');
Route::get('/reserve/listapprove','ReserveController@listapproved');
Route::post('reserve/reject','ReserveController@reject');
Route::get('/reserve/listrejected','ReserveController@listrejected');
Route::post('reserve/confirm','ReserveController@confirm');
Route::post('reserve/cancel','ReserveController@cancel');
Route::get('/reserve/listconfirmed','ReserveController@listconfirmed');
Route::get('/reserve/listcanceled','ReserveController@listcanceled');
Route::post('reserve/complete','ReserveController@complete');
Route::get('/reserve/listcompleted','ReserveController@listcompleted');
Route::get('/reserve/all','ReserveController@listall');
Route::get('/reserve/seller','ReserveController@liststatusseller');
Route::get('/reserve/buyer','ReserveController@liststatusbuyer');
Route::get('/reserve/detail','ReserveController@detail');
Route::delete('/reserve/delete','ReserveController@delete');

//DummyRerservation
Route::post('dumreserve/reserveproduct', 'DummyReserveController@reserveproduct');
Route::get('dumreserve/listreserve', 'DummyReserveController@listreserve');
Route::get('dumreserve/sellerreject', 'DummyReserveController@sellerreject');
Route::get('dumreserve/sellerapprove', 'DummyReserveController@sellerapprove');
Route::get('dumreserve/buyercancel', 'DummyReserveController@buyercancel');
Route::get('dumreserve/buyerconfirm', 'DummyReserveController@buyerconfirm');
Route::get('dumreserve/sellersold', 'DummyReserveController@sellersold');
Route::get('dumreserve/buyercomplete', 'DummyReserveController@buyercomplete');

//review 
Route::post('/review','ReviewController@store');
Route::delete('/review/delete','ReviewController@delete');
Route::get('/review/list','ReviewController@list');
Route::get('/review/listuser','ReviewController@listuser');

//mailing
Route::post('/email','EmailController@mail');

//log
Route::get('/log','LogController@list');
Route::get('/log/detail','LogController@detail');
Route::delete('/log/delete','LogController@delete');
Route::get('/log/type','LogController@type');

//notification
Route::get('/notification','NotificationController@list');
Route::delete('/notification/delete','NotificationController@delete');
Route::get('/notification/status','NotificationController@liststatus');
Route::get('/notification/detail','NotificationController@detail');
Route::get('/notification/listid','NotificationController@listid');
Route::get('/notification/notificationuser', 'NotificationController@notifiuser');

//paidpackage
Route::get('/paidpackage/user','PackageController@paiduser');
Route::get('/paidpackage/all','PackageController@paidall');
Route::delete('/paidpackage/delete','PackageController@deletepaid');

//dashboard
Route::get('/admin/dash','AdminController@dashboard');
Route::get('/admin/statedash','AdminController@dashstate');
Route::get('/admin/statecategory','AdminController@statecategory');


//history
Route::get('/history/all','HistoryController@all');
Route::get('/history/id','HistoryController@listid');
Route::delete('/history/delete','HistoryController@delete');

Route::get('/latest','ProductController@latest');









 
