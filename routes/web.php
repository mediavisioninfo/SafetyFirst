<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PolicyTypeController;
use App\Http\Controllers\PolicySubTypeController;
use App\Http\Controllers\PolicyDurationController;
use App\Http\Controllers\PolicyForController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ClaimLogController;
use App\Http\Controllers\InsuranceDetailController;
use App\Http\Controllers\DlDetailController;
use App\Http\Controllers\GstManagementController;
use App\Http\Controllers\ProfessionalFeeController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Auth\TwoFactorController;

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

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->middleware(
    [
        'XSS',
    ]
);
Route::get('home', [HomeController::class, 'index'])->name('home')->middleware(
    [
        'XSS',
    ]
);
Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware(
    [
        'XSS',
    ]
);

//-------------------------------User-------------------------------------------

Route::resource('users', UserController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// web.php
Route::post('/switch-company/{id}', [HomeController::class, 'switch'])->name('switch.company');

//-------------------------------Subscription-------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/2fa-selection', [TwoFactorController::class, 'selection'])->name('2fa.selection');
    Route::post('/2fa-send', [TwoFactorController::class, 'sendCode'])->name('2fa.sendCode');
    Route::get('/2fa-verify', [TwoFactorController::class, 'verifyForm'])->name('2fa.verifyForm');
    Route::post('/2fa-verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');
});

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::resource('subscriptions', SubscriptionController::class);
    Route::get('coupons/history', [CouponController::class, 'history'])->name('coupons.history');
    Route::delete('coupons/history/{id}/destroy', [CouponController::class, 'historyDestroy'])->name('coupons.history.destroy');
    Route::get('coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
    Route::resource('coupons', CouponController::class);
    Route::get('subscription/transaction', [SubscriptionController::class, 'transaction'])->name('subscription.transaction');
}
);

//-------------------------------Subscription Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::post('subscription/{id}/stripe/payment', [SubscriptionController::class, 'stripePayment'])->name('subscription.stripe.payment');
}
);
//-------------------------------Settings-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::get('settings/account', [SettingController::class, 'account'])->name('setting.account');
    Route::post('settings/account', [SettingController::class, 'accountData'])->name('setting.account');
    Route::delete('settings/account/delete', [SettingController::class, 'accountDelete'])->name('setting.account.delete');
    Route::get('settings/password', [SettingController::class, 'password'])->name('setting.password');
    Route::post('settings/password', [SettingController::class, 'passwordData'])->name('setting.password');
    Route::get('settings/general', [SettingController::class, 'general'])->name('setting.general');
    Route::post('settings/general', [SettingController::class, 'generalData'])->name('setting.general');
    Route::get('settings/smtp', [SettingController::class, 'smtp'])->name('setting.smtp');
    Route::post('settings/smtp', [SettingController::class, 'smtpData'])->name('setting.smtp');
    Route::get('settings/payment', [SettingController::class, 'payment'])->name('setting.payment');
    Route::post('settings/payment', [SettingController::class, 'paymentData'])->name('setting.payment');
    Route::get('settings/company', [SettingController::class, 'company'])->name('setting.company');
    Route::post('settings/company', [SettingController::class, 'companyData'])->name('setting.company');
    Route::get('language/{lang}', [SettingController::class, 'lanquageChange'])->name('language.change');
    Route::post('theme/settings', [SettingController::class, 'themeSettings'])->name('theme.settings');
    Route::get('settings/site-seo', [SettingController::class, 'siteSEO'])->name('setting.site.seo');
    Route::post('settings/site-seo', [SettingController::class, 'siteSEOData'])->name('setting.site.seo');
    Route::get('settings/google-recaptcha', [SettingController::class, 'googleRecaptcha'])->name('setting.google.recaptcha');
    Route::post('settings/google-recaptcha', [SettingController::class, 'googleRecaptchaData'])->name('setting.google.recaptcha');
}
);


//-------------------------------Role & Permissions-------------------------------------------
Route::resource('permission', PermissionController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('role', RoleController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
//-------------------------------Note-------------------------------------------
Route::resource('note', NoticeBoardController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
//-------------------------------Contact-------------------------------------------
Route::resource('contact', ContactController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------logged History-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::get('logged/history', [UserController::class, 'loggedHistory'])->name('logged.history');
    Route::get('logged/{id}/history/show', [UserController::class, 'loggedHistoryShow'])->name('logged.history.show');
    Route::delete('logged/{id}/history', [UserController::class, 'loggedHistoryDestroy'])->name('logged.history.destroy');
});

//-------------------------------Plan Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::post('subscription/{id}/bank-transfer', [PaymentController::class, 'subscriptionBankTransfer'])->name('subscription.bank.transfer');
    Route::get('subscription/{id}/bank-transfer/action/{status}', [PaymentController::class, 'subscriptionBankTransferAction'])->name('subscription.bank.transfer.action');
    Route::post('subscription/{id}/paypal', [PaymentController::class, 'subscriptionPaypal'])->name('subscription.paypal');
    Route::get('subscription/{id}/paypal/{status}', [PaymentController::class, 'subscriptionPaypalStatus'])->name('subscription.paypal.status');
});

//-------------------------------System Setup-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::get('policy/{id}/subtype', [PolicySubTypeController::class, 'getSubType'])->name('policy.subtype');
    Route::resource('policy-type', PolicyTypeController::class);
    Route::resource('policy-sub-type', PolicySubTypeController::class);
    Route::resource('policy-duration', PolicyDurationController::class);
    Route::resource('policy-for', PolicyForController::class);
    Route::resource('document-type', DocumentTypeController::class);
});
//-------------------------------Policy-------------------------------------------

Route::resource('policy', PolicyController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
//-------------------------------Customer-------------------------------------------

Route::resource('customer', CustomerController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Agent-------------------------------------------

Route::resource('agent', AgentController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Insurance-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {
    Route::post('insurance/policy', [InsuranceController::class, 'getPolicy'])->name('insurance.policy');
    Route::post('insurance/user', [InsuranceController::class, 'getUser'])->name('insurance.user');

    Route::get('insurance/{id}/insured/create', [InsuranceController::class, 'insuredCreate'])->name('insurance.insured.create');
    Route::post('insurance/{id}/insured/store', [InsuranceController::class, 'insuredStore'])->name('insurance.insured.store');
    Route::delete('insurance/{id}/insured/{iid}/destroy', [InsuranceController::class, 'insuredDestroy'])->name('insurance.insured.destroy');

    Route::get('insurance/{id}/nominee/create', [InsuranceController::class, 'nomineeCreate'])->name('insurance.nominee.create');
    Route::post('insurance/{id}/nominee/store', [InsuranceController::class, 'nomineeStore'])->name('insurance.nominee.store');
    Route::delete('insurance/{id}/nominee/{nid}/destroy', [InsuranceController::class, 'nomineeDestroy'])->name('insurance.nominee.destroy');

    Route::get('insurance/{id}/document/create', [InsuranceController::class, 'documentCreate'])->name('insurance.document.create');
    Route::post('insurance/{id}/document/store', [InsuranceController::class, 'documentStore'])->name('insurance.document.store');
    Route::delete('insurance/{id}/document/{did}/destroy', [InsuranceController::class, 'documentDestroy'])->name('insurance.document.destroy');

    Route::get('insurance/{id}/payment/create', [InsuranceController::class, 'paymentCreate'])->name('insurance.payment.create');
    Route::post('insurance/{id}/payment/store', [InsuranceController::class, 'paymentStore'])->name('insurance.payment.store');
    Route::delete('insurance/{id}/payment/{pid}/destroy', [InsuranceController::class, 'paymentDestroy'])->name('insurance.payment.destroy');

    Route::get('payment', [InsuranceController::class, 'payment'])->name('payment');
    Route::resource('insurance', InsuranceController::class);
});

//-------------------------------Claim-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {

    Route::get('claim/{id}/document/create', [ClaimController::class, 'documentCreate'])->name('claim.document.create');
    Route::post('claim/{id}/document/store', [ClaimController::class, 'documentStore'])->name('claim.document.store');
    Route::delete('claim/{id}/document/{did}/destroy', [ClaimController::class, 'documentDestroy'])->name('claim.document.destroy');

    Route::post('customer/insurance', [ClaimController::class, 'getInsurance'])->name('customer.insurance');
    Route::resource('claim', ClaimController::class);
});

//-------------------------------Tax-------------------------------------------
Route::resource('tax', TaxController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Claim Upload Document-------------------------
Route::get('/claim/upload-otp/{id}', [ClaimController::class, 'showUploadOtpForm'])->name('claim.upload.otp');
Route::post('/claim/verify-otp', [ClaimController::class, 'verifyOtp'])->name('claim.verifyOtp');
Route::post('/claim/otp-expire/{claim_id}', [ClaimController::class, 'expireOtpSession'])->name('claim.otp.expire');
Route::post('/claim/check-otp-session', [ClaimController::class, 'checkOtpSession'])->name('claim.check.otp');

Route::get('claim/upload/{id}', [ClaimController::class, 'uploadForm'])->name('claim.upload');
Route::get('/s', function (Request $request) {
    // Get the raw query string like "2Xd10L"
    $code = $request->server('QUERY_STRING');

    if (!$code) {
        abort(404);
    }

    $short = ShortUrl::where('code', $code)->first();

    if (!$short) {
        abort(404);
    }

    return redirect($short->original_url);
})->name('short.redirect');

Route::get('/claims/{claim}/photos/pdf',[ClaimController::class, 'downloadPhotosPdf'])->name('claims.photos.pdf');

Route::get('/get-cities/{state}', [ClaimController::class, 'getCities']);

Route::get('/secure-image/{claimHash}/{folderHash}/{folderCode}/{filename}', [ClaimController::class, 'showImage'])->name('secure.image');


Route::post('/upload-document', [ClaimController::class, 'uploadDocument'])->name('upload.document');
Route::get('/claim/report/{id}', [ClaimController::class, 'generateReport'])->name('claim.report');
Route::get('/claim/excel/{id}', [ClaimController::class, 'generateExcel'])->name('claim.excel');
Route::get('/convert-excel-to-pdf/{claim}', [ClaimController::class, 'convertExcelToPdf'])->name('claim.exceltopdf');
Route::get('/claim/viewreport/{id}', [ClaimController::class, 'viewReport'])->name('claim.viewReport');
Route::post('/claim/{id}/add-part', [ClaimController::class, 'addPart'])->name('claim.addPart');
Route::post('/claims/{id}/update-part-details', [ClaimController::class,'updatePartDetails'])->name('claim.updatePartDetails');
Route::post('/claims/{id}/update-part-details1', [ClaimController::class,'updateAllData'])->name('claim.updateAllData');
Route::post('/update-paint-depreciation/{id}', [ClaimController::class,'updatePaintDepreciation'])->name('claim.updatePaintDepreciation');
Route::post('/claim/{id}/remove-part', [ClaimController::class, 'removePart'])->name('claim.removePart');
Route::post('/claim/{id}/remove-part1', [ClaimController::class, 'removeLabourPart'])->name('claim.removeLabourPart');
Route::post('/claims/assign', [ClaimController::class, 'assignClaims'])->name('claim.assign');
//Recheck the claims
Route::get('/recheck/{claimId}', [ClaimController::class, 'checkVehicleInsuranceMatch'])->name('claim.recheck');

//Send Mail to WOrkshop
Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
Route::get('/template/{id}/claim/{claimId}', [EmailController::class, 'getTemplate']);
Route::post('/emails', [EmailController::class, 'store'])->name('emails.store');
Route::post('/ckeditor/image-upload', [EmailController::class, 'uploadDocument'])->name('ckeditor.upload.document');

//Route::get('claim-logs', [ClaimLogController::class, 'index'])->name('claim.logs');
Route::get('/claim/changes/{id}', [ClaimLogController::class, 'showChanges'])->name('claim.changes');
Route::get('/claim-log/{id}', [ClaimLogController::class, 'show'])->name('claim.log');
//insurance update 
Route::put('/insurance-details/{claimId}', [InsuranceDetailController::class, 'update'])->name('insuranceDetails.update');
//fees bill details updated
Route::put('/feebill/{claimId}/{id?}', [ProfessionalFeeController::class, 'createOrUpdate'])->name('claim.feeBillData');
//dl update
Route::put('/dl-details/{claimId}', [DlDetailController::class, 'updateDlDetails'])->name('dlDetails.update');
// admin side upload documnet route
Route::post('/claims/update-document', [ClaimController::class, 'updateDocument'])->name('claims.update-document');
Route::post('/claims/delete-document', [ClaimController::class, 'deleteDocument'])->name('claims.delete-document');
Route::post('/claims/add-document', [ClaimController::class, 'addDocument'])->name('claims.add-document');
// gst management
Route::get('gst-manage', [GstManagementController::class, 'index'])->name('gst.manage');
Route::put('gst-manage', [GstManagementController::class, 'update'])->name('gst.update');

Route::post('/auto-logout', function () {
    \Auth::logout();
    \Session::flush();
    return response()->json(['message' => 'Logged out']);
})->name('auto.logout');