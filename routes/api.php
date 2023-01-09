<?php

use Illuminate\Support\Facades\Route;

// auth
Route::match(['GET', 'POST'], '/auth/{action}', 'Accounts\AuthController@index');

//google
Route::match(['GET', 'POST'], '/google/{action}', 'Accounts\GoogleController@index');

//Settings
Route::group(['namespace' => '\App\Http\Controllers\Settings'], function () {
    Route::get('/setting', 'SettingController@index');
});

// ppob
Route::group([
    'prefix' => '',
], function () {
    Route::POST('/autopayment/add', 'Ppob\AutoPaymentController@addSchedule');
    Route::get('/autopayment/schedules', 'Ppob\AutoPaymentController@schedules');
    Route::get('/autopayment/schedules/{id}', 'Ppob\AutoPaymentController@schedule');
    Route::delete('/autopayment/schedules/{id}', 'Ppob\AutoPaymentController@cancelSchedule');

    Route::get('/ppob/categories', 'Ppob\AutoPaymentController@categories');
    // Route::get('/productsss/{id}', 'Ppob\AutoPaymentController@productInCategory');
    // Route::get('/productsss/{id}', 'Ppob\ProductController@show');
    Route::get('/product-categories/{id}', 'Ppob\ProductController@productCategory');
    Route::get('/product/{id}', 'Ppob\ProductController@products');

    Route::get('/user/bookmarks', 'Ppob\AutoPaymentController@bookmarks');
    Route::delete('/user/bookmarks/{id}', 'Ppob\AutoPaymentController@removeBookmark');
    Route::post('/user/bookmarks', 'Ppob\AutoPaymentController@addBookmark');
});

Route::middleware(['auth:api'])->group(function () {
    // track
    Route::namespace('Track')
        ->controller(TrackController::class)
        ->prefix('track')
        ->group(function () {
            Route::post('/', 'track');
        });

    // payroll
    Route::namespace('Payroll')
        ->controller(PayslipController::class)
        ->prefix('payroll')
        ->middleware(['check-user-merchant'])
        ->group(function () {
            Route::get('get_pin', 'getPinForPdf');
            Route::get('pin_latest', 'getLatestPin');
            Route::get('send_otp', 'sendOTP');
            Route::post('pin_pdf', 'setPin');
            Route::post('auth_otp', 'authOTP');
        });

    // wallet withdraw
    Route::namespace('Wallet')
        ->controller(WithdrawController::class)
        ->prefix('wallet/{walletId}')
        ->group(function () {
            Route::get('/withdraw', 'fetchWithdrawByStatus');
            // Route::post('/withdraw-test', 'checkPGA');
            Route::get('/withdraw-fee', 'fetchWithdrawFee');
            Route::post('/withdraw', 'createWithdraw');
            Route::post('/withdraw-group', 'createGroupWithdraw');
            Route::post('/withdraw/0/preview', 'createWithdrawPreview');
            Route::post('/withdraw-group/preview', 'createGroupWithdrawPreview');
        });

    // ticket
    Route::namespace('Ticket')
        ->controller(TicketController::class)
        ->prefix('ticket')
        ->group(function () {
            Route::get('/', 'getTickets');
            Route::post('/', 'createTicket');
            Route::get('/{id}/comment', 'getCommentTicket');
            Route::get('/{id}', 'getOneTicket');
            Route::post('/{id}/comment', 'createComment');
        });

    // ticket category
    Route::namespace('Ticket')
        ->controller(TicketController::class)
        ->prefix('category')
        ->group(function () {
            Route::get('/', 'getTicketCategories');
            Route::get('/{id}', 'getTicketCategory');
        });

    // connects
    Route::namespace('Connect')
        ->controller(UserConnectController::class)
        ->prefix('connects')
        ->group(function () {
            Route::get('list_apps', 'getAppsConnected');
            Route::delete('revoke_pass/{merchant_id}', 'revokeUserSavedPin');
        });

    //user
    Route::match(['GET', 'POST'], '/user/{action}', 'Accounts\UserController@index');
    Route::match(['GET', 'POST'], '/user-information/{action}', 'Accounts\UserInformationController@index');
    Route::match(['GET', 'POST'], '/register-device', 'Accounts\UserInformationController@registerDevice');

    //Promotions
    Route::group(['middleware' => ['logger'], 'namespace' => '\App\Http\Controllers\Promotions'], function () {
        Route::group(['prefix' => 'v1/promotion'], function () {
            Route::get('/merchant/{id}', 'PromotionController@merchant');
        });

        Route::group(['prefix' => 'v1/rewards'], function () {
            Route::get('/cashback', 'RewardController@cashback');
            Route::get('/rewards', 'RewardController@rewards');
        });
    });

    //Currencies
    Route::group(['namespace' => '\App\Http\Controllers\Currencies'], function () {
        Route::get('/activation_purpose', 'CurrencyController@getActivationPurposes');
        Route::get('/purpose/check', 'CurrencyController@getUserPurpose');
        Route::post('/purpose/save', 'CurrencyController@saveUserPurpose');
        Route::post('/region/save', 'CurrencyController@registerUserRegion');
    });

    //password
    Route::post('/password/{action}', 'Accounts\PasswordController@index');

    //banks
    Route::match(['GET', 'POST'], '/bank/{action}', 'Accounts\BanksController@index');

    //security-questions
    Route::match(['GET', 'POST'], '/security-questions/{action}', 'Accounts\SecurityQuestionsController@index');

    //location
    Route::match(['GET', 'POST'], '/locations/{action}', 'Accounts\LocationController@index');

    //captcha
    Route::match(['GET', 'POST'], '/captcha/{action}', 'Accounts\CaptchaController@index');

    //close account
    Route::match(['GET', 'POST'], '/close-account/{action}', 'Accounts\CloseAccountController@index');

    //captcha
    Route::match(['GET', 'POST'], '/user-contacts/{action}', 'Accounts\UserContactsController@index');

    //captcha
    Route::match(['GET', 'POST'], '/asset/store', 'Media\AssetController@store');

    // notification
    Route::group([
        'prefix' => 'notification',
    ], function () {
        Route::get('/inbox', 'Notification\InboxController@inbox');
        // Route::get('/inbox/{category}', 'Notification\InboxController@inboxCategory');
        Route::get('/inbox/{category}', 'Notification\InboxController@inboxCategoryV2');
        Route::get('/{id}', 'Notification\InboxController@read');
    });

    // payment - card
    Route::group([
        'prefix' => '',
    ], function () {
        Route::get('card/', 'Payment\PaymentController@cards');
        Route::get('card/{id}', 'Payment\PaymentController@card');
        Route::get('card-info/{id}', 'Payment\PaymentController@cardInfo');
        Route::POST('card/', 'Payment\PaymentController@cardCreate');
        Route::DELETE('card/{id}', 'Payment\PaymentController@cardDelete');
    });

    // payment - bill
    Route::group([
        'prefix' => 'bill',
    ], function () {
        Route::get('', 'Payment\PaymentController@bills');
        Route::get('/{billId}/payment', 'Payment\PaymentController@bill');
        Route::get('/{billId}/payment/{paymentId}', 'Payment\PaymentController@billPayment');
        Route::get('/{billId}/method', 'Payment\PaymentController@getPaymentMethod');
        Route::POST('/{billId}/payment', 'Payment\PaymentController@payment');

        Route::get('/{billId}/bill-info', 'Payment\PaymentController@getBillData');
    });

    // cart - onprogress
    // Route::POST('/cart/pay', 'CartController@pay');
    // Route::POST('/cart/ncash', 'CartController@ncash');
    // Route::POST('/cart/active', 'CartController@active');
    //
    // Route::get('/cart', 'CartController@getCarts');
    // Route::DELETE('/cart', 'CartController@deleteCart');
    // Route::POST('/cart', 'CartController@createCart');
    //
    // Route::PATCH('/cart', 'CartController@updateCart');
    // Route::POST('/cart/item', 'CartController@addToCart');
    // Route::PUT('/cart/item', 'CartController@updateInCart');
    // Route::DELETE('/cart/item', 'CartController@deleteInCart');
    // Route::POST('/cart/pay/pg', 'CartController@toPaymentMethod');
    //
    //
    // Route::get('/g/cart/{id}/item', 'CartController@cartItem');
    // Route::GET('/g/cart/{id}', 'CartController@gCartWallet');
    // Route::GET('/g/cart/qr/scan/{id}', 'CartController@qrPayment');
	
     Route::get('/media/group/{id}', 'Media\MediaController@filesInGroup');
     Route::post('upload-media', 'Media\MediaController@upload');

    //feeds
    Route::get('/media/group/{id}', 'Media\MediaController@filesInGroup');
    Route::get('feed_category', 'Feed\FeedController@getListCategory');
    Route::get('detail_feed_published', 'Feed\FeedController@getDetalFeedPublished');
    Route::post('post-user-react', 'Feed\FeedController@postUserReact');
    Route::get('get_all_feed', 'Feed\FeedController@all_get');

    //Wallet
    Route::group(['namespace' => '\App\Http\Controllers\Wallet'], function () {
        Route::get('/wallet', 'WalletController@index');
        Route::get('/wallet/{id}', 'WalletController@getById');
        Route::get('/wallet/{id}/limit', 'WalletController@getWalletLimit');
        Route::post('/wallet/{id}/transfer', 'WalletTransfersController@transferAction');
        Route::get('/wallet/{id}/transaction', 'WalletController@getTransaction');
        Route::post('/wallet/topup', 'WalletTopupController@topupWallet');
        Route::delete('/wallet/topup/{id}', 'WalletTopupController@cancelTopup');
        Route::get('/wallet/topup/{id}', 'WalletTopupController@getTopupTransaction');
        Route::get('/topup-instruction/{bank_code}/lang/{lang}', 'WalletTopupController@getTopupInstruction');
        Route::get('/wallet/{id}/validate-transaction', 'WalletController@validateTransaction');

        // wallet-top
        Route::post('/wallet-otp', 'WalletOtpController');
    });

    // referall
    Route::prefix('/referral')->group(function () {
        Route::post('/register', 'Accounts\ReferralController@register');
        Route::get('/referral-list', 'Accounts\ReferralController@getReferallList');
    });

    // jobs
    Route::prefix('/jobs')->group(function () {
        Route::get('/', 'Accounts\JobController@getJobs');
        Route::post('/add-job', 'Accounts\JobController@addJob');
    });

    // QR
    Route::post('/qr/read', "Accounts\UserController@readQrCode");

    // address
    Route::prefix('/address')->group(function () {
        Route::post('/add-address', 'Accounts\AddressController@addAddress');
    });

    // password
    Route::prefix('/password')->group(function () {
        Route::get('/last-pin-change', 'Accounts\PasswordController@getLastPasswordChange');
        Route::get('/pin-block-status', 'Accounts\PasswordController@pinBlockStatus');
    });

    Route::namespace('\App\Http\Controllers\Ppob')->group(function () {
        Route::prefix('/')->group(function () {
            Route::get('/pulsa/products', ['uses' => 'ProductController@pulsa', 'as' => 'getProductPulsa']);
            // Route::post('/pulsa/add-order', ['uses' => 'Cellular\CellularController@addOrder', 'as' => 'addOrderPulsa']);
            Route::post('/pulsa/add-order', ['uses' => 'Cellular\CellularController@addOrderV2', 'as' => 'addOrderPulsa']);
            Route::post('/pulsa/get-product', ['uses' => 'Cellular\CellularController@getProduct', 'as' => 'getProductCelluler']);
            Route::post('/pulsa/topup', ['uses' => 'Cellular\CellularController@topupPulsa', 'as' => 'topupPulsa']);
            Route::get('/pdam/list', ['uses' => 'ProductController@pdam', 'as' => 'getPdam']);
            Route::get('/games', ['uses' => 'ProductController@gameCategory', 'as' => 'getGames']);
            Route::get('/e-voucher', ['uses' => 'ProductController@evoucherCategory', 'as' => 'geteVoucher']);
            Route::post('/emoney/add-order', ['uses' => 'Emoney\EmoneyController@addOrder', 'as' => 'addOrderEmoney']);
            Route::get('/games/{game_id}', ['uses' => 'Games\GameFlashController@gameDetail', 'as' => 'getDetailGames']);
            Route::post('/games/topup', ['uses' => 'Games\GameFlashController@addOrder', 'as' => 'topUpGame']);
            Route::post('/games/topup-tes', ['uses' => 'Games\GameFlashController@topUpRajaBiller', 'as' => 'topUpRajaBiller']);

            Route::get('/transactions', ['uses' => 'ServiceController@userTransaction', 'as' => 'getTrans']);
            Route::post('/gopay/topup', ['uses' => 'Gopay\GopayController@Topup', 'as' => 'addInquiryGames']);

            Route::post('/coin/inquiry', ['uses' => 'Coin\CoinController@inquiry', 'as' => 'addInquiryCoin']);
            Route::post('/evoucher/inquiry', ['uses' => 'EVoucher\EVoucherController@inquiry', 'as' => 'addInquiryeVoucher']);
            Route::post('/cellular/inquiry', ['uses' => 'Cellular\CellularController@inquiry', 'as' => 'addInquiryPln']);
            Route::post('/games/inquiry', ['uses' => 'Games\GameFlashController@addOrder', 'as' => 'addInquiryGames']);
            
            Route::post('/pln/inquiry', ['uses' => 'Pln\PlnController@inquiry', 'as' => 'addInquiryPln']);
            Route::post('/kai/inquiry', ['uses' => 'Kai\KaiController@inquiry', 'as' => 'addInquiryKai']);
            Route::post('/pdam/inquiry', ['uses' => 'Pdam\PdamController@inquiry', 'as' => 'addInquiryPdam']);
            Route::post('/tv/inquiry', ['uses' => 'Tv\TvController@inquiry', 'as' => 'addInquiryTv']);
            Route::post('/bpjs/inquiry', ['uses' => 'Bpjs\BpjsController@inquiry', 'as' => 'addInquiryBpjs']);
            Route::post('/bpjs/payment', ['uses' => 'Bpjs\BpjsController@payment', 'as' => 'addPaymentBpjs']);
            Route::post('/telkom/inquiry', ['uses' => 'Telkom\TelkomController@inquiry', 'as' => 'addInquiryTelkom']);
            Route::post('/gas/inquiry', ['uses' => 'Gas\GasController@inquiry', 'as' => 'addInquiryGas']);
            Route::post('/multifinance/inquiry', ['uses' => 'Multifinance\MultifinanceController@inquiry', 'as' => 'addInquiryMultifinance']);
            Route::post('/subscription/inquiry', ['uses' => 'Subscription\SubscriptionController@inquiry', 'as' => 'addInquirySubscription']);

            Route::get('/pln/token/{billId}', ['uses' => 'Pln\PlnController@token', 'as' => 'plnToken']);
        });
    });
});

Route::namespace('\App\Http\Controllers\Banner')->group(function () {
    Route::prefix('/banner')->group(function () {
        Route::get('/', ['uses' => 'BannerController@indexAction', 'as' => 'getBanners']);
        Route::get('/{id}', ['uses' => 'BannerController@singleAction', 'as' => 'getDetailBanner']);
    });
});

Route::namespace('\App\Http\Controllers\Docs')->group(function () {
    Route::prefix('/document')->group(function () {
        Route::get('/', ['uses' => 'DocsController@indexDocs', 'as' => 'getDocument']);
        Route::get('/{slug}', ['uses' => 'DocsController@singleActionDocs', 'as' => 'getUniqueCodeDocument']);
        // getTermsAndConditions
    });

    Route::get('help-search', ['uses' => 'HelpController@searchHelp', 'as' => 'getSeatchHelp']);
    Route::prefix('/help')->group(function () {
        Route::get('/', ['uses' => 'HelpCategoryController@indexAction', 'as' => 'getHCategory']);
        Route::get('/{slug}', ['uses' => 'HelpCategoryController@singleAction', 'as' => 'getDetailHCategory']);
        Route::get('/{slug}/content', ['uses' => 'HelpController@searchHelpbySlug', 'as' => 'getDetailHelp']);
        Route::get('/{slug}/content/{helpId}', ['uses' => 'HelpController@singleActionHCategory', 'as' => 'getDetailHelpIdHelp']);
    });
});

Route::namespace('\App\Http\Controllers\Lang\PublicApi')->group(function () {
    Route::prefix('/project')->group(function () {
        Route::get('/', ['uses' => 'ProjectController@actionGetProject', 'as' => 'getProject']);
        Route::get('/{projectId}', ['uses' => 'ProjectController@actionViewProject', 'as' => 'getDetailProject']);

        Route::get('/{projectId}/screen', ['uses' => 'ScreenController@actionGetScreen', 'as' => 'getScreen']);
        Route::get('/{projectId}/screen/{screenId}', ['uses' => 'ScreenController@actionViewScreen', 'as' => 'getDetailScreen']);
    });
});

Route::get('atm', 'ATM\ATMController@index');

Route::post('send-email', 'Notification\InboxController@sendEmail');

Route::get('label', 'Wallet\WalletLabelsController@index');
Route::get('label/{id}', 'Wallet\WalletLabelsController@show');

Route::post('send-email', 'Notification\InboxController@sendEmail');

Route::post('/wallet/topup-callback', 'Wallet\WalletTopupController@topupCallback');

Route::namespace('\App\Http\Controllers\Ppob')->group(function () {
    Route::prefix('/')->group(function () {
        Route::POST('portal-callback', 'ServiceController@callbackPortalPulsa');
        Route::GET('ppob/update-product', 'ProductController@updateProductService');
    });
});

Route::group(['middleware' => ['auth:merchant'], 'namespace' => '\App\Http\Controllers\Merchant'], function () {
    Route::prefix('merchant')->group(function () {
        Route::post('/request-bill', 'MerchantController@createBill');
    });
});