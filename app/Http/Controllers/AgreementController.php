<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\AgreementCounter;
use App\Models\AgreementDetail;
use App\Models\AgreementType;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceType;
use App\Models\Product;
use App\Models\SendMail;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\Estimate;
use App\Models\Configuration;
use App\Models\Organization;
use App\Models\EstimateCounter;
use App\Models\EstimateDetail;
use App\Models\QuotationType;
use App\Models\InvoiceCounter;
use App\Models\InvoiceDetail;
use App\Mail\EstimateMail;
use App\Models\User;
use Darryldecode\Cart\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Response;
use Auth;
use PDF;
use DB;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Cart::clear();
        $agreements = Agreement::orderBy('id', 'desc')->get();
        $invoiceTypes = InvoiceType::all();
        return view('agreement.agreement-index', compact('agreements', 'invoiceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter = AgreementCounter::find(1);
        $customers = ['' => '-Please select customer-'] + Customer::where('status', 1)->pluck('name', 'id')->toArray();
        $products = ['' => '-Please select product-'] + Product::where('is_visible', 1)->pluck('name', 'id')->toArray();
        $units = ['' => '-Please select unit-'] + Unit::all()->pluck('name', 'id')->toArray();
        $quotationTypes = ['' => '-Please select-'] + AgreementType::all()->pluck('quotation_type_name', 'id')->toArray();
        return view('agreement.agreement-create', compact('customers', 'products', 'units', 'counter', 'quotationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Agreement $agreement)
    {
        $agreement_details = AgreementDetail::where('agreement_id', $agreement->id)->get();
        $is_converted = Invoice::where('estimate_id', $agreement->id)->count();
        $send_mail_count = SendMail::where('estimate_id', $agreement->id)->count();
        $employee = Employee::find($agreement->employee_id);


        $file_path = public_path() . '/pdf/' . $agreement->file;
        if (file_exists($file_path) && !is_null($agreement->file)) {
            unlink($file_path);
        }

        $upload_dir = public_path();
        $newFileName = 'quotation_' . time() . '_' . $agreement->number . '.pdf';
        $filename = $upload_dir . '/pdf/' . $newFileName . '';
        $agreement->file = $newFileName;
        $agreement->update();


        $mpdf = PDF::loadView('agreement.agreement-pdf', compact('agreement_details', 'agreement', 'employee'), [], [
            'title'             => 'agreement',
            'format'            => 'A4',
            'orientation'       => 'P',
            'default_font_size' => '12',
            'margin_top'        => 24,
            'margin_right'      => 12,
            'margin_bottom'     => 25,
            'margin_left'       => 27,
            'margin_header'     => 0,
            'margin_footer'     => 0,
            'show_watermark'           => false,
            'display_mode'             => 'fullpage',
            'show_watermark_image'     => true,
            'watermark_image_alpha'    => 1,
            'watermark_image_path'       => asset('bg/pad.jpg'),
        ])->save($filename);

        return view('agreement.agreement-show', compact('agreement', 'send_mail_count', 'is_converted'));
    }


    public function show_work_order($id)
    {
        $estimate = Estimate::findOrFail($id);
        $work_order_pdf = $estimate->work_order;
        return view('estimate.work_order-show', compact('work_order_pdf'));
    }

    public function show_work_completion($id)
    {
        $estimate = Estimate::findOrFail($id);
        $work_completion_pdf = $estimate->work_completion;
        return view('estimate.work_completion-show', compact('work_completion_pdf'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Agreement $agreement)
    {
        $counter = AgreementCounter::find(1);
        $agreementDetails = AgreementDetail::where('agreement_id', $agreement->id)->get();
        \Cart::clear();
        foreach ($agreementDetails as $agreementDetail) {
            \Cart::add([
                'id' => $agreementDetail->product_id,
                'name' => $agreementDetail->product->name,
                'quantity' => $agreementDetail->quantity,
                'price' => $agreementDetail->price,
                'attributes' => ['unit' => $agreementDetail->product->unit_id, 'unit_name' => $agreementDetail->product->unit->name, 'total' => $agreementDetail->sum('total')],

            ]);
        }

        $customers = ['' => '-Please select customer-'] + Customer::where('status', 1)->pluck('name', 'id')->toArray();
        $products = ['' => '-Please select product-'] + Product::where('is_visible', 1)->pluck('name', 'id')->toArray();
        $units = ['' => '-Please select unit-'] + Unit::all()->pluck('name', 'id')->toArray();
        $quotationTypes = ['' => '-Please select-'] + QuotationType::all()->pluck('quotation_type_name', 'id')->toArray();
        return view('agreement.agreement-edit', compact('counter', 'agreement', 'agreementDetails', 'customers', 'products', 'units', 'quotationTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    // go to livewire component
    public function agreement_convert($agreement_id)
    {
        return view('agreement.agreement-convert', compact('agreement_id'));
    }


    public function agreement_accepted($agreement_id)
    {
        $agreement = Agreement::find($agreement_id);
        $agreement->status = 'accepted';
        $agreement->update();
        Alert::success('Success', 'Agreement has been accepted successfully.');
        return redirect()->back();
    }


    public function agreement_rejected($agreement_id)
    {
        $agreement = Agreement::find($agreement_id);
        $agreement->status = 'rejected';
        $agreement->update();
        Alert::success('Success', 'Agreement has been rejected successfully.');
        return redirect()->back();
    }
}
