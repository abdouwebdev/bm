<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {

    // Select2
    Route::prefix('select2')->name('select2.')->group(function () {
        // sales
        Route::post('/get-customer', 'Api\SalesController@getCustomer')->name('get-customer');
        Route::get('/get-customer/{contact}', 'Api\SalesController@customerSelected')->name('get-customer.selected');

        //supplier
        Route::post('/get-supplier', 'Api\BuyController@getSupplier')->name('get-supplier');

        //product
        Route::post('/get-product', 'Api\SalesController@getProduct')->name('get-product');
        Route::get('/get-product/{product}', 'Api\SalesController@selectedProduct')->name('get-product.selected');

        //product buy
        Route::post('/get-buy-product', 'Api\BuyController@getProduct')->name('get-buy-product');
        Route::get('/get-buy-product/{product}', 'Api\BuyController@selectedProduct')->name('get-buy-product.selected');

        //offer
        Route::post('/get-sale-offer', 'Api\SalesController@getOffer')->name('get-sale-offer');
        Route::post('/get-buy-offer', 'Api\BuyController@getOffer')->name('get-buy-offer');

        //order
        Route::post('/get-sale-order', 'Api\SalesController@getOrder')->name('get-sale-order');
        Route::post('/get-buy-order', 'Api\BuyController@getOrder')->name('get-buy-order');

        //invoice
        Route::post('/get-sale-invoice', 'Api\SalesController@getInvoice')->name('get-sale-invoice');
        Route::post('/get-buy-invoice', 'Api\BuyController@getInvoice')->name('get-buy-invoice');

        //invoice
        Route::post('/get-account/invoice', 'Api\SalesController@getAccount')->name('get-account-invoice');
        Route::post('/get-account-sale', 'Api\SalesController@getAccountSale')->name('get-account-sale');
        Route::post('/get-account-supply', 'Api\BuyController@getAccountSupply')->name('get-account-supply');
    });

    /**
     * SALE
     */
    // Get Invoice Detail by:invoice_id
    Route::get('/get-sale-invoice-details/{invoice_id}', 'Api\SalesController@getInvoiceDetails')->name('get-sale-invoice.details');

    /**
     * PURCHASE
     */
    // Get Purchase Detail by:order_id
    Route::get('/get-buy-offer-detail/{offer_id}', 'Api\BuyController@getOfferDetails')->name('get-buy-offer.details');

    // Get Order Detail by:order_id
    Route::get('/get-buy-order-detail/{order_id}', 'Api\BuyController@getOrderDetails')->name('get-buy-order.details');

    // Get Invoice Detail by:invoice_id
    Route::get('/get-buy-invoice-detail/{invoice_id}', 'Api\BuyController@getInvoiceDetails')->name('get-buy-invoice.details');


});
