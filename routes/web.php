<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CloudController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\HostingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ServiceActiveController;
use App\Http\Controllers\Admin\SmtpController;
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Customer\BusinessController;
use App\Http\Controllers\Customer\CloudController as CustomerCloudController;
use App\Http\Controllers\Customer\CustomerServiceController;
use App\Http\Controllers\Customer\DomainController as CustomerDomainController;
use App\Http\Controllers\Customer\EmailController as CustomerEmailController;
use App\Http\Controllers\Customer\HostingController as CustomerHostingController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\RenewServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web Routes for your application. These
| Routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('', [AuthController::class, 'login'])->name('login');
    Route::post('', [AuthController::class, 'authenticate']);
    Route::get('dang-ky-tai-khoan', [AuthController::class, 'register'])->name('register');
    Route::post('dang-ky-tai-khoan', [AuthController::class, 'submitregister'])->name('submit.register');
    Route::get('reset-password', [AuthController::class, 'resetpass'])->name('resetpass');
    Route::post('reset-password', [AuthController::class, 'sendResetPassword'])->name('submit.resetpass');
    Route::get('/activate-account/{token}', [AuthController::class, 'activateAccount'])->name('activate.account');
});

Route::middleware(['auth', 'profile.updated'])->group(function () {


    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('user-service', [DashboardController::class, 'userService'])->name('user.service');

    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('', [PaymentController::class, 'recharge'])->name('recharge');
        Route::post('', [PaymentController::class, 'createPayment'])->name('recharge.add');
        Route::get('cancel', [PaymentController::class, 'cancelUrl'])->name('recharge.cancel');
        Route::get('return/{amount}', [PaymentController::class, 'returnUrl'])->name('recharge.return');
    });


    Route::prefix('admin')->middleware('check.admin')->group(function () {


        Route::prefix('user')->name('user.')->group(function () {
            Route::post('/import', [UserController::class, 'import'])->name('import.users');
            Route::get('', [UserController::class, 'index'])->name('index');
            Route::get('create', [UserController::class, 'create'])->name('create');
            Route::post('', [UserController::class, 'store'])->name('store');
            Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('{id}/edit', [UserController::class, 'update'])->name('update');
            Route::post('{id}', [ClientController::class, 'delete'])->name('delete');
        });

        Route::prefix('client')->name('client.')->group(function () {
            Route::get('', [ClientController::class, 'index'])->name('index');
            Route::get('create', [ClientController::class, 'create'])->name('create');
            Route::post('', [ClientController::class, 'store'])->name('store');
            Route::get('{id}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('{id}/edit', [ClientController::class, 'update'])->name('update');
            Route::post('{id}', [ClientController::class, 'delete'])->name('delete');
        });

        Route::prefix('order')->name('order.')->group(function () {
            Route::get('create', [OrderController::class, 'createOrder'])->name('create');
            Route::get('{status?}', [OrderController::class, 'index'])->name('index');
            Route::get('{id}/show', [OrderController::class, 'show'])->name('show');
            Route::post('delete-{id}', [OrderController::class, 'delete'])->name('delete');
            Route::post('active-{id}', [OrderController::class, 'active'])->name('active');
            Route::post('create-account-{id}', [OrderController::class, 'createAccount'])->name('create.account');

        });

        Route::prefix('hosting')->name('hosting.')->group(function () {
            Route::get('', [HostingController::class, 'index'])->name('index');
            Route::get('create', [HostingController::class, 'create'])->name('create');
            Route::post('', [HostingController::class, 'store'])->name('store');
            Route::get('{id}/edit', [HostingController::class, 'edit'])->name('edit');
            Route::put('{id}/edit', [HostingController::class, 'update'])->name('update');
            Route::post('delete-{id}', [HostingController::class, 'delete'])->name('delete');
        });
        Route::prefix('cloud')->name('cloud.')->group(function () {
            Route::get('add', [CloudController::class, 'create'])->name('create');
            Route::get('{type_id?}', [CloudController::class, 'index'])->name('index');
            Route::post('', [CloudController::class, 'store'])->name('store');
            Route::get('{id}/edit', [CloudController::class, 'edit'])->name('edit');
            Route::put('{id}/edit', [CloudController::class, 'update'])->name('update');
            Route::post('{id}', [CloudController::class, 'delete'])->name('delete');
        });

        Route::prefix('email')->name('email.')->group(function () {
            Route::get('add', [EmailController::class, 'create'])->name('create');
            Route::get('{type_id?}', [EmailController::class, 'index'])->name('index');
            Route::post('', [EmailController::class, 'store'])->name('store');
            Route::get('{id}/edit', [EmailController::class, 'edit'])->name('edit');
            Route::put('{id}/edit', [EmailController::class, 'update'])->name('update');
            Route::post('{id}', [EmailController::class, 'delete'])->name('delete');
        });

        Route::post('delete-{id}', [TransactionHistoryController::class, 'delete'])->name('history.delete');

        Route::prefix('domain')->name('domain.')->group(function () {
            Route::get('', [DomainController::class, 'index'])->name('index');
            Route::get('detail-{domain}', [DomainController::class, 'show'])->name('show');
            Route::get('price', [DomainController::class, 'tableprice'])->name('price');
        });

        Route::prefix('service')->name('service.')->group(function () {
            Route::prefix('list-cloud')->name('cloud.')->group(function () {
                Route::get('{date?}', [ServiceActiveController::class, 'listcloud'])->name('list.cloud');
            });
            Route::prefix('list-hosting')->name('hosting.')->group(function () {
                Route::get('{date?}', [ServiceActiveController::class, 'listhosting'])->name('list.hosting');
            });

            Route::prefix('list-hotel')->name('hotel.')->group(function () {
                Route::get('{date?}', [ServiceActiveController::class, 'listhotel'])->name('list.hotel');
            });

            Route::prefix('list-email')->name('email.')->group(function () {
                Route::get('{date?}', [ServiceActiveController::class, 'listEmail'])->name('list.email');
            });

            Route::get('add-service-{type}', [ServiceActiveController::class, 'addForm'])->name('add');
            Route::post('add-service-{type}', [ServiceActiveController::class, 'addSubmit'])->name('add.submit');
        });

        Route::prefix('company')->name('company.')->group(function () {
            Route::get('', [ConfigController::class, 'index'])->name('index');
            Route::post('', [ConfigController::class, 'store'])->name('store');
        });

        Route::prefix('smtp')->name('smtp.')->group(function () {
            Route::get('email', [SmtpController::class, 'email'])->name('email');
            Route::post('email', [SmtpController::class, 'emailSubmit'])->name('email.save');
            Route::get('template', [SmtpController::class, 'template'])->name('template');
        });

        Route::prefix('business')->name('business.')->group(function () {
            Route::get('', [App\Http\Controllers\Admin\BusinessController::class, 'index'])->name('index');
            Route::get('{id}', [App\Http\Controllers\Admin\BusinessController::class, 'view'])->name('detail');
            Route::post('delete', [App\Http\Controllers\Admin\BusinessController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('customer')->name('customer.')->group(function () {

        Route::prefix('hosting')->name('hosting.')->group(function () {
            Route::get('', [CustomerHostingController::class, 'index'])->name('index');
        });

        Route::prefix('cloud')->name('cloud.')->group(function () {
            Route::get('{type_id?}', [CustomerCloudController::class, 'index'])->name('index');
            Route::get('vi/cloud-{id}', [CustomerCloudController::class, 'vicloud'])->name('vicloud');
            Route::post('add-cart', [CustomerCloudController::class, 'addtocart'])->name('addtocart');
        });

        Route::prefix('email')->name('email.')->group(function () {
            Route::get('{email_type?}', [CustomerEmailController::class, 'index'])->name('index');
            Route::get('vi/email-{id}', [CustomerEmailController::class, 'viemail'])->name('viemail');
            Route::post('add-cart', [CustomerEmailController::class, 'addtocart'])->name('addtocart');
            Route::get('package/type', [CustomerEmailController::class, 'packageType'])->name('package');
        });

        Route::prefix('order')->name('order.')->group(function () {
            Route::get('show/{id?}', [CustomerOrderController::class, 'addorder'])->name('payment');
            Route::get('{status?}', [CustomerOrderController::class, 'index'])->name('index');
            Route::get('{id}/show', [CustomerOrderController::class, 'show'])->name('show');
            Route::post('thanh-toan/don-hang', [CustomerOrderController::class, 'thanhtoan'])->name('thanhtoan');
            Route::get('vi/prcode/{id}/{xsd}', [CustomerOrderController::class, 'createPayment'])->name('create.payment');
            Route::get('return/{id}/{xsd}', [CustomerOrderController::class, 'returnUrl'])->name('return');
            Route::get('vi/pr-code/enews/{id?}', [CustomerOrderController::class, 'createPaymentenews'])->name('create.payment.enews');
            Route::post('vi/pr-code/enews/{id?}', [CustomerOrderController::class, 'paymentenewsSuccess'])->name('create.payment.enews.success');
            Route::post('thanh-toan/gia-han/{id?}', [CustomerOrderController::class, 'thanhtoangiahan'])->name('thanhtoan.giahan');
            Route::get('renew-show/{id?}', [CustomerOrderController::class, 'renewaddorder'])->name('renew.payment');
        });
        Route::prefix('cart')->name('cart.')->group(function () {
            // Route::get('', [CartController::class, 'listcart'])->name('listcart');
            Route::get('', [CartController::class, 'listcart'])->name('listcart');
            Route::post('renews-ervice-{id}', [RenewServiceController::class, 'addrenews'])->name('addrenews');
            Route::get('renews-ervice', [RenewServiceController::class, 'listrenews'])->name('listrenews');
        });

        Route::prefix('service')->name('service.')->group(function () {

            Route::get('list-{type}', [CustomerServiceController::class, 'listServices'])->name('list.service');

            Route::get('list-hotel', [CustomerServiceController::class, 'listHotel'])->name('list.hotel');

        });

        Route::prefix('domain')->name('domain.')->group(function () {
            Route::get('', [CustomerDomainController::class, 'index'])->name('index');
            Route::post('check', [CustomerDomainController::class, 'checkDomain'])->name('check.domain');
            Route::post('cart', [CustomerDomainController::class, 'addToCart'])->name('addTo.cart');
            Route::post('delete-cart', [CustomerDomainController::class, 'deleteToCart'])->name('deleteTo.cart');
            Route::post('remove/{id}', [CustomerDomainController::class, 'delete']);
        });
    });

    Route::get('check-domain', [DomainController::class, 'checkdomain'])->name('check.domain');
    Route::post('submit-check-domain', [DomainController::class, 'submitcheckdomain'])->name('submit.check.domain');


    Route::prefix('business')->name('business.')->group(function () {
        Route::get('registration', [BusinessController::class, 'registration'])->name('registration');
        Route::post('dang-ky', [BusinessController::class, 'registrationSubmit'])->name('registration.submit');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('profile', [ProfileController::class, 'updateprofile'])->name('profile.update');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});



Route::get('/get-districts', [AuthController::class, 'getDistricts']);
Route::get('/get-wards', [AuthController::class, 'getWards']);
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('addToCart');
Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('update.quantity');
Route::post('/update-time', [CartController::class, 'updatetime'])->name('update.time');
Route::post('/delete-item', [CartController::class, 'deleteItem'])->name('delete.item');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout.item');

Route::prefix('history')->name('history.')->group(function () {
    Route::get('{status?}', [TransactionHistoryController::class, 'index'])->name('index');
    Route::get('{id}/show', [TransactionHistoryController::class, 'show'])->name('show');
});

Route::prefix('qrcode')->name('qrcode.')->group(function () {
    Route::get('create', [QrCodeController::class, 'create'])->name('create');
    Route::get('edit/{id}', [QrCodeController::class, 'edit'])->name('edit');
    Route::get('', [QrCodeController::class, 'index'])->name('index');
    Route::post('/imageurl', [QrCodeController::class, 'imageurl'])->name('imageurl');
    Route::post('/save/{id?}', [QrCodeController::class, 'save'])->name('save');

    Route::post('/delete/{id}', [QrCodeController::class, 'delete'])->name('delete');
});


route::get('redirect-url/{url}', function($url){
    $decodedUrl = base64_decode($url);
    return redirect()->to($decodedUrl);
})->name('redirect-url');


Route::post('/clear-pdf-session', [CustomerOrderController::class, 'clearPdfSession'])->name('clear.pdf.session');

Route::post('/renews-delete-item', [RenewServiceController::class, 'deleteItem'])->name('renews.delete.item');
Route::post('/renews-update-time', [RenewServiceController::class, 'updatetime'])->name('renews.update.time');

Route::get('service/getContent/{id}', [ServiceActiveController::class, 'getContentService']);
Route::get('service/status/{id}', [ServiceActiveController::class, 'getStatusService']);
Route::get('service/giahan/{id}', [ServiceActiveController::class, 'getGiaHanService']);
Route::post('/service/update-status/{id}', [ServiceActiveController::class, 'updateStatus'])->name('service.updateStatus');
Route::post('/service/saveContent', [ServiceActiveController::class, 'saveContent']);
Route::post('/service/giahan', [ServiceActiveController::class, 'giaHan']);
Route::delete('/service/delete/{id}', [ServiceActiveController::class, 'destroy'])->name('service.destroy');
Route::get('service/edit/{id}', [ServiceActiveController::class, 'getEditService']);
Route::post('service/edit', [ServiceActiveController::class, 'editService']);
Route::post('password/reset', [ServiceActiveController::class, 'resetPass']);

Route::post('/save-domain', [ServiceActiveController::class, 'saveDomain'])->name('save-domain');

Route::post('/delete-item-client', [ClientController::class, 'deleteItemsClient'])->name('items.client.delete');


// web.php
Route::post('/transfer-domain', [DomainController::class, 'transfer'])->name('transfer.domain');

Route::post('/transfer-service', [ServiceActiveController::class, 'transferService'])->name('transfer.service');

