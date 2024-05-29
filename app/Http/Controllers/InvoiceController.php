<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\InvoiceCounter;
use App\Models\InvoiceType;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Mail\InvoiceMail;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\SendMail;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use PDF;
use DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invoice.invoice-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($id)
    {
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->get();
        $invoice = Invoice::find($id);
        $employee = Employee::find($invoice->employee_id);


        $send_mail_count = SendMail::where('invoice_id', $id)->count();
        // if($estimate->file==null){


        $file_path = public_path() . '/pdf/' . $invoice->file;
        if (file_exists($file_path) && !is_null($invoice->file)) {
            unlink($file_path);
        }

        $upload_dir = public_path();
        $newFileName = 'invoice_' . time() . '_' . $invoice->number . '.pdf';
        $filename = $upload_dir . '/pdf/' . $newFileName . '';
        $invoice->file = $newFileName;
        $invoice->update();



        if ($invoice->invoice_type_id == 1) {
            $temp = 'invoice.invoice-pdf';
        } elseif ($invoice->invoice_type_id == 2) {
            $temp = 'invoice.domain-hosting-invoice-pdf';
        } else {
            $temp = 'invoice.invoice-pdf';
        }




        $mpdf = PDF::loadView($temp, compact('invoice_details', 'invoice', 'employee'), [], [
            'title'             => 'Invoice',
            'format'            => 'A4',
            'orientation'       => 'P',
            'default_font_size' => '12',
            'margin_top'        => 54,
            'margin_right'      => 12,
            'margin_bottom'     => 25,
            'margin_left'       => 16,
            'margin_header'     => 0,
            'margin_footer'     => 0,
            'show_watermark'           => false,
            'display_mode'               => 'fullpage',
            'show_watermark_image'     => true,
            'watermark_image_alpha'    => 1,
            'watermark_image_path'       => asset('bg/pad.jpg'),
        ])->save($filename);

        return view('invoice.invoice-show', compact('invoice', 'send_mail_count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function send_email($id)
    {

        $invoice = Invoice::find($id);
        $customer = Customer::find($invoice->customer_id);
        $invoiceType = InvoiceType::find($invoice->invoice_type_id);
        $organization = Organization::find(1);

        $data = [
            'title' => $invoice->subject,
            'body' => $invoice->description,
            'invoice' => $invoice,
            'file_name' => $invoice->file
        ];



        Mail::to($customer->email)->cc([$organization->email])->send(new InvoiceMail($data));

        $sent_mail = new SendMail;
        $sent_mail->invoice_id = $invoice->id;
        $sent_mail->title = $data['title'];
        $sent_mail->body = $data['body'];
        $sent_mail->save();

        Alert::success('Success', 'Invoice has been mailed successfully.');
        return redirect()->back();
    }
}
