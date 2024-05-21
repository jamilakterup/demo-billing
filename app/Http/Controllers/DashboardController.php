<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\CpLead;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\LeadCollection;
use App\Models\ServiceLead;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $totalUser = User::count();
        $totalInvoice = Invoice::count();
        $totalCustomer = Customer::count();
        $totalQuotation = Estimate::count();
        $totalNewLead = LeadCollection::where('status', 'pending')
            ->where('type', 'new')->count();
        $totalServiceLead = LeadCollection::where('status', 'pending')
            ->where('type', 'service')->count();
        $totalCpLead = LeadCollection::where('status', 'pending')
            ->where('type', 'cp')->count();
        $totalAgreement = Agreement::count();

        $monthlyInvoiceAmount = Invoice::whereYear('date', '=', date('Y'))
            ->whereMonth('date', '=', date('m'))
            ->get();

        $monthlyInvoicePayment = Payment::whereYear('date', '=', date('Y'))
            ->whereMonth('date', '=', date('m'))
            ->get();


        return view('home', compact('totalUser', 'totalInvoice', 'totalCustomer', 'totalQuotation', 'totalNewLead', 'totalServiceLead', 'totalCpLead', 'totalAgreement', 'monthlyInvoiceAmount', 'monthlyInvoicePayment'));
    }
}
