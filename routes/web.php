<?php

date_default_timezone_set('Europe/Paris');

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\JackpotController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\Purchases\AccountReceivableController as PurchasesAccountReceivableController;
use App\Http\Controllers\Admin\Purchases\InvoiceController as PurchasesInvoiceController;
use App\Http\Controllers\Admin\Purchases\OfferController as PurchasesOfferController;
use App\Http\Controllers\Admin\Purchases\OrderController as PurchasesOrderController;
use App\Http\Controllers\Admin\Purchases\PaymentController as PurchasesPaymentController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\Sales\AccountReceivableController;
use App\Http\Controllers\Admin\Sales\InvoiceController;
use App\Http\Controllers\Admin\Sales\OfferController;
use App\Http\Controllers\Admin\Sales\OrderController;
use App\Http\Controllers\Admin\Sales\PaymentController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\HomeController as SuperAdminHomeController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\PaController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    //auth management
    Route::get('/login', [LoginController::class, 'loginView'])->name('login');
});

//edit profile
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/setting', [ProfileController::class, 'edit'])->name('setting');
    Route::patch('/setting/update', [ProfileController::class, 'update'])->name('update');
});
// change password
Route::prefix('account')->name('password.')->group(function () {
    Route::get('/password', [ProfileController::class, 'changePassword'])->name('edit');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('edit');
});

// --------------------------------Language---------------------------------
Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::group(['middleware' => ['isAdmin']], function () {
    Route::get('/login-redirects', function () {
        return redirect(Redirect::intended()->getTargetUrl());
    })->name('login-redirects');

    Route::get('/', [HomeController::class, 'home'])->name('home');

    Route::prefix('/menu')->name('menu.')->group(function () {
        Route::get('/');
    });

    Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function () {

        Route::prefix('data-store')->group(function () {
            Route::view('/', 'menu')->name('data-store');
            // Contact
            Route::resource('contact', ContactController::class);
            Route::post('/contact/code-contact', [ContactController::class, 'contactCode'])->name('contact.code');
        });
        // Product And API
        Route::resource('product', 'ProductController');
        Route::post('/get-product', [ProductController::class, 'getProduct'])->name('get-product');
        Route::post('/get-buy-product', [ProductController::class, 'getProductBuy'])->name('get-buy-product');
        Route::post('/get-buy-offer', [PurchasesOfferController::class, 'getOfferBuy'])->name('get-buy-offer');
        Route::post('/get-sale-order', [OrderController::class, 'getOrder'])->name('get-sale-order');
        Route::post('/get-buy-order', [PurchasesOrderController::class, 'getOrderBuy'])->name('get-buy-order');

        //members
        Route::prefix('member')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('member.index');
            Route::get('/create', [MemberController::class, 'create'])->name('member.create');
            Route::post('/store', [MemberController::class, 'store'])->name('member.store');
            Route::get('/show/{id}', [MemberController::class, 'show'])->name('member.show');
            Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('member.edit');
            Route::put('/update/{id}', [MemberController::class, 'update'])->name('member.update');
            Route::delete('/destroy/{id}', [MemberController::class, 'destroy'])->name('member.destroy');
            Route::post('/department', [MemberController::class, 'department'])->name('member.department');
            Route::get('/{id}/{session}', [MemberController::class, 'memberList'])->name('member.deptAndSess');
            Route::get('/group', [MemberController::class, 'memberGroup'])->name('member.group');
        });

        //Department
        Route::prefix('department')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('department.index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('department.create');
            Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
            Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
            Route::put('/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
            Route::delete('/destroy/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');
        });

        //Sector
        Route::prefix('sector')->group(function () {
            Route::get('/', [SectorController::class, 'index'])->name('sector.index');
            Route::get('/create', [SectorController::class, 'create'])->name('sector.create');
            Route::post('/store', [SectorController::class, 'store'])->name('sector.store');
            Route::get('/edit/{id}', [SectorController::class, 'edit'])->name('sector.edit');
            Route::put('/update/{id}', [SectorController::class, 'update'])->name('sector.update');
            Route::delete('/destroy/{id}', [SectorController::class, 'destroy'])->name('sector.destroy');
        });

        //accounts
        Route::prefix('accounts')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('account.index');
            Route::get('/create', [AccountController::class, 'create'])->name('account.create');
            Route::post('/store', [AccountController::class, 'store'])->name('account.store');
            Route::get('/show/{id}', [AccountController::class, 'show'])->name('account.show');
            Route::get('/edit/{id}', [AccountController::class, 'edit'])->name('account.edit');
            Route::put('/update/{id}', [AccountController::class, 'update'])->name('account.update');
            Route::delete('/destroy/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
        });

        //jackpots
        Route::prefix('jackpots')->group(function () {
            Route::get('/', [JackpotController::class, 'index'])->name('jackpot.index');
            Route::get('/create', [JackpotController::class, 'create'])->name('jackpot.create');
            Route::post('/store', [JackpotController::class, 'store'])->name('jackpot.store');
            Route::get('/show/{id}', [JackpotController::class, 'show'])->name('jackpot.show');
            Route::get('/edit/{id}', [JackpotController::class, 'edit'])->name('jackpot.edit');
            Route::put('/update/{id}', [JackpotController::class, 'update'])->name('jackpot.update');
            Route::delete('/destroy/{id}', [JackpotController::class, 'destroy'])->name('jackpot.destroy');
        });

        //add-user
        Route::prefix('add-user-manager')->group(function () {
            Route::get('/', [RegisterController::class, 'index'])->name('add-user-manager');
            Route::get('/create', [RegisterController::class, 'create'])->name('add-user-manager.create');
            Route::post('/store', [RegisterController::class, 'store'])->name('add-user-manager.store');
            Route::delete('/destroy/{id}', [RegisterController::class, 'destroy'])->name('add-user-manager.destroy');
        });

        //communication
        Route::prefix('communication')->group(function () {
            Route::get('/', [CommunicationController::class, 'index'])->name('communication.index');
            Route::get('/create', [CommunicationController::class, 'create'])->name('communication.create');
            Route::post('/store', [CommunicationController::class, 'store'])->name('communication.store');
            Route::post('/edit/{id}', [CommunicationController::class, 'edit'])->name('communication.edit');
            Route::delete('/destroy/{id}', [CommunicationController::class, 'destroy'])->name('communication.destroy');
        });

        Route::prefix('sales')->name('sales.')->group(function () {
            Route::view('/', 'menu');
            Route::resource('offer', OfferController::class);
            Route::resource('order', OrderController::class);
            Route::resource('invoice', InvoiceController::class);
            Route::get('receivable', [AccountReceivableController::class, 'index'])->name('receivable.index');
            Route::resource('payment', PaymentController::class);
        });

        Route::prefix('purchase')->name('purchase.')->group(function () {
            Route::view('/', 'menu');
            Route::resource('offer', PurchasesOfferController::class);
            Route::resource('order', PurchasesOrderController::class);
            Route::resource('invoice', PurchasesInvoiceController::class);
            Route::get('receivable', [PurchasesAccountReceivableController::class, 'index'])->name('receivable.index');
            Route::resource('payment', PurchasesPaymentController::class);
        });
    });
});

Route::group(['middleware' => ['isUser']], function () {
    Route::prefix('user')->group(function () {
        Route::get('/home', [UserHomeController::class, 'index'])->name('user.index');
        Route::get('/home/personal-account', [PaController::class, 'index'])->name('user.pa');
    });
});

Route::group(['middleware' => ['isSuperAdmin']], function () {
    Route::prefix('super-admin')->group(function () {
        Route::get('/home', [SuperAdminHomeController::class, 'index'])->name('superadmin.index');
        Route::get('/all-users', [UserController::class, 'index'])->name('superadmin.users');
        Route::post('users-group-create', [UserController::class, 'create'])->name('superadmin.create');
        Route::post('users-group-store', [UserController::class, 'store'])->name('superadmin.store');
        Route::delete('users-delete', [UserController::class, 'destroy'])->name('superadmin.destroy');
    });
});