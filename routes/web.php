<?php

use App\Http\Controllers\AgreementController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Designation;
use App\Http\Livewire\Employee;
use App\Http\Livewire\Product;
use App\Http\Livewire\Type;
use App\Http\Livewire\Unit;
use App\Http\Livewire\Organization;
use App\Http\Livewire\Payment;
use App\Http\Livewire\Module;
use App\Http\Livewire\Status;
use App\Http\Livewire\Permission;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceTypeController;
use App\Http\Controllers\QuotationTypeController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Livewire\AssignProject\ProjectSendMail;
use App\Http\Livewire\AssignProject\ProjectTable;
use App\Http\Livewire\CpLead\CpTable;
use App\Http\Livewire\Estimate\EstimateConvert;
use App\Http\Livewire\InactiveLead\InactiveTable;
use App\Http\Livewire\LeadCollection\CollectionTable;
use App\Http\Livewire\ServiceLead\ServiceTable;
use App\Http\Livewire\SoldLead\SoldTable;
use App\Http\Livewire\SslPayment\SslPayment;

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

// Route::get('/clear-cache', function () {
//     Artisan::call('cache:clear');
//     Artisan::call('config:cache');
//     Cache::flush();
//     return 'DONE'; //Return anything
// });


Route::prefix('/')->middleware('auth')->group(function () {

    Route::get('/', [DashBoardController::class, 'dashboard'])->name('home');

    // Route::get('user/', Users::class);

    // Route::resource('invoice',InvoiceController::class);
    Route::get('global/item/change', [EstimateController::class, 'global_change']);
    Route::get('price/change/{id}', [EstimateController::class, 'price_change']);
    Route::get('/cart/item/delete/{id}', [EstimateController::class, 'cart_delete']);
    Route::get('add/product', [EstimateController::class, 'add_product']);
    Route::get('/estimate/send/email/{id}', [EstimateController::class, 'send_email'])->name('send.mail');
    Route::get('/invoice/send/email/{id}', [InvoiceController::class, 'send_email'])->name('invoice.send.mail');

    Route::get('/estimate/convert/{id}', [EstimateController::class, 'estimate_convert'])->name('estimate.convert');
    Route::get('/estimate/accepted/{id}', [EstimateController::class, 'estimate_accepted'])->name('estimate.accepted');
    Route::get('/estimate/rejected/{id}', [EstimateController::class, 'estimate_rejected'])->name('estimate.rejected');

    Route::get('/agreement/convert/{id}', [AgreementController::class, 'agreement_convert'])->name('agreement.convert');
    Route::get('/agreement/accepted/{id}', [AgreementController::class, 'agreement_accepted'])->name('agreement.accepted');
    Route::get('/agreement/rejected/{id}', [AgreementController::class, 'agreement_rejected'])->name('agreement.rejected');

    Route::get('/product', Product::class)->name('product'); //livewire
    Route::get('/product/type', Type::class)->name('productType'); //livewire
    Route::get('/unit', Unit::class)->name('unit'); //livewire
    Route::get('/module', Module::class)->name('module'); //livewire
    Route::get('/permission', Permission::class)->name('permission'); //livewire
    Route::get('/organization', Organization::class)->name('organization');
    // Route::get('/setting/configuration',Configuration::class)->name('configuration');
    // Route::get('/invoice',Invoice::class)->name('invoice');//livewire
    // Route::get('/invoice', InvoiceController::class)->name('invoice');
    Route::get('/designation', Designation::class)->name('designation'); //livewire
    Route::get('/employee', Employee::class)->name('employee'); //livewire
    Route::get('/payment', Payment::class)->name('payment'); //livewire
    Route::get('/project', ProjectTable::class)->name('project'); //livewire
    Route::get('/project/send/email/{id}', [ProjectSendMail::class, 'send_email'])->name('project.send.email');
    Route::get('lead-collection', CollectionTable::class)->name('lead_collection'); //livewire
    Route::get('service-lead', ServiceTable::class)->name('service_lead'); //livewire
    Route::get('cp-lead', CpTable::class)->name('cp_lead'); //livewire
    Route::get('sold-lead', SoldTable::class)->name('sold_lead'); //livewire
    Route::get('inactive-lead', InactiveTable::class)->name('inactive_lead'); //livewire

    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('invoice', InvoiceController::class);
    Route::resource('estimate', EstimateController::class);
    Route::resource('agreement', AgreementController::class);

    Route::get('estimate/work-order/{id}', [EstimateController::class, 'work_order_show'])->name('work_order_show');
    Route::get('estimate/work-completion/{id}', [EstimateController::class, 'work_completion_show'])->name('work_completion_show');

    Route::get('agreement/work-order/{id}', [AgreementController::class, 'show_work_order'])->name('show_work_order');
    Route::get('agreement/work-completion/{id}', [AgreementController::class, 'show_work_completion'])->name('show_work_completion');
    // start setting
    Route::resource('invoiceType', InvoiceTypeController::class);
    Route::resource('quotationType', QuotationTypeController::class);
    // end setting

    Route::get('/status', Status::class)->name('status'); //livewire
});

// Route::prefix('/')->middleware('auth')->group(function () {
//     Route::get('/', [DashBoardController::class, 'dashboard'])->name('home');
//     // Route::get('/', [UserDashboardController::class, 'dashboard'])->name('home');
//     Route::get('/payment', SslPayment::class)->name('payment');
// });

// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
