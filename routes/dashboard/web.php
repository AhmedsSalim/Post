<?php


use App\Models\CategoryTranslation;
use App\Models\Category;


Route::group(
    ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]],
     function(){


    Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function (){
        Route::get('/','WelcomeController@index')->name('welcome');


         // Route catogry

         Route::resource('categories', CategoryController::class)->except(['show']);


         // Route product

         Route::resource('products', ProductController::class)->except(['show']);


        // Route Client

        Route::resource('clients', ClientController::class)->except(['show']);

        // Route Client Order

        Route::resource('clients.orders', Client\OrderController::class)->except(['show']);



        // Route  Order

        Route::resource('orders', OrderController::class);

        Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');



        // Route User

        Route::resource('users', UserController::class)->except(['show']);


    });//end of dashboard

});
