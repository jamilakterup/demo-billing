<?php

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

use RealRashid\SweetAlert\Facades\Alert;


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');



Route::get('/instalment/member/filter/{id}', 'InstalmentController@member_filter');///ajax
Route::get('/report/loan/filter/{id}', 'ReportController@loan_filter');///ajax
Route::get('/instalment/loan/select/{id}', 'InstalmentController@loan_select');///ajax


Route::group(['prefix' => '',  'middleware' => 'admin'], function()
{

Route::get('/', function () {

    $data['totalUser']=\App\User::all()->count();
    $data['totalMember']=\App\Member::all()->count();
    $data['paymentDates']=\App\Loan_payment_calender::where('payment_date',date('Y-m-d'))->where('status',0)->get();
    $data['receivable_amount']=\App\Loan_payment_calender::where('payment_date',date('Y-m-d'))->sum('amount');
    $data['total_savings']=\App\Saving::whereDate('date', '=', date('Y-m-d'))->where('type','savings')->sum('amount');
    $data['total_withdrawal']=\App\Saving::whereDate('date', '=', date('Y-m-d'))->where('type','withdrawal')->sum('amount');
    $data['received_amount']=\App\Instalment::where('date',date('Y-m-d'))->sum('payment');
    $data['runningLoan']=\App\loan::where('paid',0)->count();
    $data['completeLoan']=\App\loan::where('paid',1)->count();

    return view('backend.index',$data);
});




Route::get('/receivable/amount/user', 'UserController@receivable_amount_user');///ajax
Route::get('/received/amount/user', 'UserController@received_amount_user');///ajax
Route::get('/total/savings/amount/user', 'UserController@total_savings_amount_user');///ajax
Route::get('/total/withdrawal/amount/user', 'UserController@total_withdrawal_amount_user');///ajax




///members

Route::resource('user', 'UserController');
Route::resource('member', 'MembersController');
Route::resource('loan', 'LoanController');

Route::resource('instalment', 'InstalmentController');
Route::get('/instalment/create2/{id}', 'InstalmentController@instalment_create');
Route::resource('savings', 'SavingsController');
Route::get('/savings/create2/{id}', 'SavingsController@create2')->name('savings.create2');
Route::resource('savings_withdrawal', 'Savings_withdrawalController');


///report
/// loan
Route::get('/report/loan/create', 'ReportController@loan_report_create')->name('report.loan.create');
Route::post('/report/loan/search', 'ReportController@loan_report_search')->name('report.loan.search');
Route::post('/report/loan/download', 'ReportController@loan_report_download')->name('report.loan.download');
/// Instalment
Route::get('/report/daily/create', 'ReportController@daily_report_create')->name('report.daily.create');
Route::post('/report/daily/search', 'ReportController@daily_report_search')->name('report.daily.search');
/// Savings
Route::get('/report/savings/create', 'ReportController@savings_report_create')->name('report.savings.create');
Route::post('/report/savings/search', 'ReportController@savings_report_search')->name('report.savings.search');











});

///profile

Route::group(['middleware' => ['user']], function () {
    Route::resource('profile', 'ProfileController');
    Route::get('/profile/member/index','ProfileController@profile_member_index')->name('profile.member.index');
    Route::get('/profile/member/create','ProfileController@profile_member_create')->name('profile.member.create');
    Route::post('/profile/member/store','ProfileController@profile_member_store')->name('profile.member.store');
    Route::get('/profile/member/show/{member_id}','ProfileController@profile_member_show')->name('profile.member.show');
    Route::get('/profile/member/edit/{member_id}','ProfileController@profile_member_edit')->name('profile.member.edit');
    Route::put('/profile.member.update/{member_id}','ProfileController@profile_member_update')->name('profile.member.update');
   
    Route::resource('profileLoan', 'Profile_loanController');
    
    Route::resource('profileInstalment', 'Profile_instalmentController');
    Route::get('profile/instalment/create2/{id}', 'Profile_instalmentController@profile_instalment_create2');

    Route::resource('profileSavings', 'Profile_savingsController');
    Route::get('profile/savings/create2/{id}', 'Profile_savingsController@profile_saving_create2');

    Route::resource('profileWithdrawal', 'Profile_withdrawalController');


    Route::get('/profile/loan/index','ProfileController@profile_loan_index')->name('profile.loan.index');
    Route::get('/profile/instalment/index','ProfileController@profile_instalment_index')->name('profile.instalment.index');
    Route::get('/profile/savings/index','ProfileController@profile_savings_index')->name('profile.savings.index');
    Route::post('/profile/withdrawal/index','ProfileController@profile_withdrawal_index')->name('profile.withdrawal.index');

    
    Route::get('/receivable/amount/member', 'ProfileController@receivable_amount_member');///ajax
    Route::get('/received/amount/member', 'ProfileController@received_amount_member');///ajax
    Route::get('/savings/amount/member', 'ProfileController@savings_amount_member');///ajax
    Route::get('/withdrawal/amount/member', 'ProfileController@withdrawal_amount_member');///ajax

    
});


