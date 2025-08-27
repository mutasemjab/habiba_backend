<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\DashboardNotification;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProductSyncController;
use App\Exports\ProductsExport;
use App\Http\Controllers\ProductController;
use Maatwebsite\Excel\Facades\Excel;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::get('/', function () {
            return view('welcome');
        });
        Route::get('/get-subcategories/{category_id}', 'ProductController@getSubcategories')->name('get.subcategories');
        Auth::routes();


        Route::get('/export-products', function () {
            return Excel::download(new ProductsExport, 'products.xlsx');
        })->name('products.export');


        Route::get('/contact-us', 'ContactUsController@contact')->name('contact');
        Route::post('/contact-us', 'ContactUsController@store')->name('contact.store');
        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('/profile', 'ProfileController@index')->name('profile');
        Route::put('/profile', 'ProfileController@update')->name('profile.update');

        Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::any('/admin/logout', function () {
            Auth::logout();
            return redirect()->route('admin.login');
        })->name('admin.logout');

        Route::middleware(['auth','isActive'])->group(function () {
            Route::prefix('roles')->group(function () {
                Route::get('/index', 'RoleController@index')->name('roles.index');
                Route::get('/create', 'RoleController@create')->name('roles.create');
                Route::get('/edit/{id}', 'RoleController@edit')->name('roles.edit');
                Route::post('/store', 'RoleController@store')->name('roles.store');
                Route::post('/update/{id}', 'RoleController@update')->name('roles.update');
                Route::POST('/delete/{id}', 'RoleController@destroy')->name('roles.destroy');
            });
            Route::prefix('users')->group(function () {
                Route::get('/index', 'UserController@index')->name('users.index');
                Route::get('/create', 'UserController@create')->name('users.create');
                Route::get('/edit/{id}', 'UserController@edit')->name('users.edit');
                Route::post('/store', 'UserController@store')->name('users.store');
                Route::post('/update/{id}', 'UserController@update')->name('users.update');
                Route::post('/delete/{id}', 'UserController@destroy')->name('users.destroy');
                Route::post('/disable/{id}', 'UserController@disable')->name('users.disable');
                Route::post('/activate/{id}', 'UserController@activate')->name('users.activate');
            });
            Route::prefix('clients')->group(function () {
                Route::get('/index', 'ClientController@index')->name('clients.index');
                Route::get('/create', 'ClientController@create')->name('clients.create');
                Route::get('/edit/{id}', 'ClientController@edit')->name('clients.edit');
                Route::post('/store', 'ClientController@store')->name('clients.store');
                Route::post('/update/{id}', 'ClientController@update')->name('clients.update');
                Route::post('/delete/{id}', 'ClientController@destroy')->name('clients.destroy');
                Route::post('/disable/{id}', 'ClientController@disable')->name('clients.disable');
                Route::post('/activate/{id}', 'ClientController@activate')->name('clients.activate');
            });
            Route::prefix('brands')->group(function () {
                Route::get('/index', 'BrandController@index')->name('brands.index');
                Route::get('/create', 'BrandController@create')->name('brands.create');
                Route::get('/edit/{id}', 'BrandController@edit')->name('brands.edit');
                Route::post('/store', 'BrandController@store')->name('brands.store');
                Route::post('/update/{id}', 'BrandController@update')->name('brands.update');
                Route::post('/delete/{id}', 'BrandController@destroy')->name('brands.destroy');
            });
          
            Route::prefix('categories')->group(function () {
                Route::get('/index', 'CategoryController@index')->name('categories.index');
                Route::get('/create', 'CategoryController@create')->name('categories.create');
                Route::get('/edit/{id}', 'CategoryController@edit')->name('categories.edit');
                Route::post('/store', 'CategoryController@store')->name('categories.store');
                Route::post('/update/{id}', 'CategoryController@update')->name('categories.update');
                Route::post('/delete/{id}', 'CategoryController@destroy')->name('categories.destroy');
            });

            Route::prefix('sub_categories')->group(function () {
                Route::get('/index', 'SubCategoryController@index')->name('sub_categories.index');
                Route::get('/create', 'SubCategoryController@create')->name('sub_categories.create');
                Route::get('/edit/{id}', 'SubCategoryController@edit')->name('sub_categories.edit');
                Route::post('/store', 'SubCategoryController@store')->name('sub_categories.store');
                Route::post('/update/{id}', 'SubCategoryController@update')->name('sub_categories.update');
                Route::post('/delete/{id}', 'SubCategoryController@destroy')->name('sub_categories.destroy');
            });
            Route::prefix('products')->group(function () {
                Route::get('/index', 'ProductController@index')->name('products.index');
                Route::get('/create', 'ProductController@create')->name('products.create');
                Route::get('/edit/{id}', 'ProductController@edit')->name('products.edit');
                Route::post('/store', 'ProductController@store')->name('products.store');
                Route::post('/update/{id}', 'ProductController@update')->name('products.update');
                Route::post('/delete/{id}', 'ProductController@destroy')->name('products.destroy');
                Route::post('/toggle/{id}', 'ProductController@toggle')->name('products.toggle');
                 Route::post('/update-barcode', 'ProductController@updateBarcode')->name('products.updateBarcode');
            });
            Route::prefix('offers')->group(function () {
                Route::get('/index', 'OfferController@index')->name('offers.index');
                Route::get('/create', 'OfferController@create')->name('offers.create');
                Route::get('/edit/{id}', 'OfferController@edit')->name('offers.edit');
                Route::post('/store', 'OfferController@store')->name('offers.store');
                Route::post('/update/{id}', 'OfferController@update')->name('offers.update');
                Route::post('/delete/{id}', 'OfferController@destroy')->name('offers.destroy');
            });
            Route::prefix('drivers')->group(function () {
                Route::get('/index', 'DriverController@index')->name('drivers.index');
                Route::get('/create', 'DriverController@create')->name('drivers.create');
                Route::get('/edit/{id}', 'DriverController@edit')->name('drivers.edit');
                Route::post('/store', 'DriverController@store')->name('drivers.store');
                Route::post('/update/{id}', 'DriverController@update')->name('drivers.update');
                Route::post('/delete/{id}', 'DriverController@destroy')->name('drivers.destroy');
                Route::post('/toggle/{id}', 'DriverController@toggle')->name('drivers.toggle');
            });
            Route::prefix('orders')->group(function () {
                Route::get('/index', 'OrderController@index')->name('orders.index');
                Route::get('/assign/{id}', 'OrderController@assign')->name('orders.assign');
                Route::post('/assign/{id}', 'OrderController@assignOrderToDriver')->name('orders.assign.driver');
                Route::get('/show/{id}', 'OrderController@show')->name('orders.show');
                Route::post('/status/change/{id}', 'OrderController@change_status')->name('orders.change.status');
                Route::delete('/{orderId}/items/{itemId}', 'OrderController@deleteOrderItem')->name('orders.delete.item');
                Route::get('/orders/{order}/preparation', 'OrderController@preparationInvoice')->name('orders.preparation');
                Route::get('/products/search', 'OrderController@searchProducts')->name('products.search');

                // Order management routes
                Route::put('/orders/{orderId}/delivery-cost', 'OrderController@updateDeliveryCost')->name('orders.update.delivery.cost');
                Route::post('/orders/{orderId}/add-product', 'OrderController@addProductToOrder')->name('orders.add.product');
                Route::put('/orders/{orderId}/items/{itemId}', 'OrderController@updateOrderItem')->name('orders.update.item');
            });
            Route::prefix('slider/images')->group(function () {
                Route::get('/index', 'SliderImageController@index')->name('slider_images.index');
                Route::get('/create', 'SliderImageController@create')->name('slider_images.create');
                Route::get('/edit/{id}', 'SliderImageController@edit')->name('slider_images.edit');
                Route::post('/store', 'SliderImageController@store')->name('slider_images.store');
                Route::post('/update/{id}', 'SliderImageController@update')->name('slider_images.update');
                Route::post('/delete/{id}', 'SliderImageController@destroy')->name('slider_images.destroy');
            });
            Route::prefix('slider/products')->group(function () {
                Route::get('/index', 'ProductSliderController@index')->name('slider_products.index');
                Route::get('/create', 'ProductSliderController@create')->name('slider_products.create');
                Route::get('/edit/{id}', 'ProductSliderController@edit')->name('slider_products.edit');
                Route::post('/store', 'ProductSliderController@store')->name('slider_products.store');
                Route::post('/update/{id}', 'ProductSliderController@update')->name('slider_products.update');
                Route::post('/delete/{id}', 'ProductSliderController@destroy')->name('slider_products.destroy');
            });
            Route::prefix('coupones')->group(function () {
                Route::get('/index', 'CouponController@index')->name('coupons.index');
                Route::get('/create', 'CouponController@create')->name('coupons.create');
                Route::post('/store', 'CouponController@store')->name('coupons.store');
                Route::get('/edit/{id}', 'CouponController@edit')->name('coupons.edit');
                Route::post('/update/{id}', 'CouponController@update')->name('coupons.update');
                Route::post('/delete/{id}', 'CouponController@destroy')->name('coupons.destroy');
            });
            Route::prefix('branches')->group(function () {
                Route::get('/index', 'BranchController@index')->name('branches.index');
                Route::get('/create', 'BranchController@create')->name('branches.create');
                Route::post('/store', 'BranchController@store')->name('branches.store');
                Route::get('/edit/{id}', 'BranchController@edit')->name('branches.edit');
                Route::post('/update/{id}', 'BranchController@update')->name('branches.update');
                Route::post('/delete/{id}', 'BranchController@destroy')->name('branches.destroy');
            });
            Route::prefix('site/generals')->group(function () {
                Route::get('/index', 'HomeController@site_generals_index')->name('site_generals.index');
                Route::get('/create', 'HomeController@site_generals_create')->name('site_generals.create');
                Route::post('/store', 'HomeController@site_generals_store')->name('site_generals.store');
                Route::get('/edit/{id}', 'HomeController@site_generals_edit')->name('site_generals.edit');
                Route::post('/update/{id}', 'HomeController@site_generals_update')->name('site_generals.update');
            });

            Route::prefix('contactus')->group(function () {
                Route::get('/index', 'ContactUsController@index')->name('contactus.index');
            });
            Route::prefix('app/rate')->group(function () {
                Route::get('/index', 'AppRateController@index')->name('app_rate.index');
            });
            Route::prefix('account/delete/requests')->group(function () {
                Route::get('/index', 'AccountDeleteRequestController@account_delete_requests_index')->name('account_delete_requests.index');
            });
            
            Route::get('/products/sync-prices', 'ProductSyncController@sync')->name('admin.products.sync-prices');

            Route::get('send/user/notification', 'UserFCMTokensController@create')->name('send_client_notification_form');
            Route::post('send/notification', 'UserFCMTokensController@send_notification')->name('send_client_notification');

            Route::get('send/global/notification', 'UserFCMTokensController@globalcreate')->name('send_global_notification_form');
            Route::post('send/user/notification', 'UserFCMTokensController@global_send_notification')->name('send_global_notification');

            Route::get('get/driver/petty/cash','HomeController@get_petty_cash')->name('get_petty_cash');
            Route::post('empty/driver/petty/cash','HomeController@empty_petty_cash')->name('empty_petty_cash');
            Route::get('/notification/read/{id}',function($id){
                $notification = DashboardNotification::find($id);
                $notification->update([
                    'is_read'=>true,
                ]);
                return redirect()->route('orders.show',$notification->order_id);
            })->name('read_notification');
            Route::get('notifications/count',function(){
                $count = DashboardNotification::where('is_read', false)->count();
                return response()->json(['count' => $count]);
                        })->name('notification_count');
        });
    }
);
