<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\Employee;
use App\Models\Configuration;
use App\Models\Organization;
use App\Models\EstimateCounter;
use App\Models\EstimateDetail;
use App\Models\QuotationType;
use App\Models\InvoiceCounter;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Unit;
use App\Mail\EstimateMail;
use App\Models\InvoiceType;
use App\Models\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use Darryldecode\Cart\Cart;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use PDF;
use DB;

use function PHPUnit\Framework\fileExists;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Cart::clear();
        $estimates = Estimate::orderBy('id', 'desc')->get();
        $invoiceTypes = InvoiceType::all();
        return view('estimate.estimate-index', compact('estimates', 'invoiceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $counter = EstimateCounter::find(1);
        $customers = ['' => '-Please select customer-'] + Customer::where('status', 1)->pluck('name', 'id')->toArray();
        $products = ['' => '-Please select product-'] + Product::where('is_visible', 1)->pluck('name', 'id')->toArray();
        $units = ['' => '-Please select unit-'] + Unit::all()->pluck('name', 'id')->toArray();
        $quotationTypes = ['' => '-Please select-'] + QuotationType::all()->pluck('quotation_type_name', 'id')->toArray();
        return view('estimate.estimate-create', compact('customers', 'products', 'units', 'counter', 'quotationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Cart::isEmpty()) {
            Alert::error('Opps..', 'Please add at least 1 product');
            return redirect()->back();
        }
        $quotation_type_id = $request->quotation_type_id;
        $customer_id = $request->customer_id;
        $estimate_number = $request->estimate_number;
        $estimate_date = $request->estimate_date;
        $expiry_date = $request->expiry_date;
        $reference_number = $request->reference_number;
        $note = $request->note;
        $work_order = null;
        $work_completion = null;

        if ($request->hasFile('work_order')) {
            $file = $request->file('work_order');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'quotation/' . 'quotation_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_order = $fileName;
        }

        if ($request->hasFile('work_completion')) {
            $file = $request->file('work_completion');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'quotation/' . 'quotation_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_completion = $fileName;
        }

        $discount = $request->discount;
        $tax = $request->tax;
        $vat = $request->vat;

        $rules = array(
            'quotation_type_id' => 'required',
            'customer_id' => 'required',
            'estimate_number' => 'required|numeric',
            'estimate_date' => 'required|date',
            'work_order' => 'nullable|file',
            'work_completion' => 'nullable|file',
            'expiry_date' => 'required|date',
        );

        $custom_rules = [
            'quotation_type_id.required' => 'The quotation type field is required.',
            'customer_id.required' => 'The customer field is required.'
        ];

        $this->validate($request, $rules, $custom_rules);

        if (!is_numeric($request->discount)) {
            $discount = 0;
        }
        if (!is_numeric($request->tax)) {
            $tax = 0;
        }
        if (!is_numeric($request->vat)) {
            $vat = 0;
        }


        $totalPrice = \Cart::getTotal();

        if ($request->discount_percent) {
            $discount = ($totalPrice * $discount) / 100;
        }
        if ($request->tax_percent) {
            $tax = ($totalPrice * $tax) / 100;
        }

        if ($request->vat_percent) {
            $vat = ($totalPrice * $vat) / 100;
        }

        $counter = EstimateCounter::find(1);
        $quotationType = QuotationType::find($quotation_type_id);


        $estimate = new Estimate;
        $estimate->user_id = Auth::user()->id;
        $estimate->number = $counter->number;
        $estimate->quotation_type_id = $quotationType->id;
        $estimate->ref_number = $reference_number;
        $estimate->customer_id = $customer_id;
        $estimate->employee_id = $quotationType->employee_id;
        $estimate->estimate_date = $estimate_date;
        $estimate->expiry_date = $expiry_date;
        $estimate->note = $note;
        $estimate->sub_total = $totalPrice;
        $estimate->discount = $discount;
        $estimate->tax = $tax;
        $estimate->vat = $vat;
        $estimate->total = ($totalPrice + $tax + $vat) - $discount;
        $estimate->status = 'draft';
        $estimate->work_order = $work_order;
        $estimate->work_completion = $work_completion;
        $estimate->save();

        foreach (\Cart::getContent() as $item) {
            $estimateDetail = new EstimateDetail;
            $estimateDetail->estimate_id = $estimate->id;
            $estimateDetail->product_id = $item->id;
            $estimateDetail->quantity = $item->quantity;
            $estimateDetail->price = $item->price;
            $estimateDetail->total = $item->price * $item->quantity;
            $estimateDetail->save();
        }

        $counter->number = $counter->number + 1;
        $counter->update();
        \Cart::clear();

        Alert::success('Success', 'Estimate has been added successfully.');
        return redirect()->route('estimate.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function show(Estimate $estimate)
    {
        $estimate_details = EstimateDetail::where('estimate_id', $estimate->id)->get();
        $is_converted = Invoice::where('estimate_id', $estimate->id)->count();
        $send_mail_count = SendMail::where('estimate_id', $estimate->id)->count();
        $employee = Employee::find($estimate->employee_id);


        $file_path = public_path() . '/pdf/' . $estimate->file;
        if (file_exists($file_path) && !is_null($estimate->file)) {
            unlink($file_path);
        }

        $upload_dir = public_path();
        $newFileName = 'quotation_' . time() . '_' . $estimate->number . '.pdf';
        $filename = $upload_dir . '/pdf/' . $newFileName . '';
        $estimate->file = $newFileName;
        $estimate->update();


        $mpdf = PDF::loadView('estimate.estimate-pdf', compact('estimate_details', 'estimate', 'employee'), [], [
            'title'             => 'estimate',
            'format'            => 'A4',
            'orientation'       => 'P',
            'default_font_size' => '12',
            'margin_top'        => 55,
            'margin_right'      => 12,
            'margin_bottom'     => 25,
            'margin_left'       => 16,
            'margin_header'     => 0,
            'margin_footer'     => 0,
            'show_watermark'           => false,
            'display_mode'             => 'fullpage',
            'show_watermark_image'     => true,
            'watermark_image_alpha'    => 1,
            'watermark_image_path'       => asset('bg/pad.jpg'),
        ])->save($filename);

        return view('estimate.estimate-show', compact('estimate', 'send_mail_count', 'is_converted'));
    }

    public function work_order_show($id)
    {
        $estimate = Estimate::findOrFail($id);
        $work_order_pdf = $estimate->work_order;
        return view('estimate.work_order-show', compact('work_order_pdf'));
    }

    public function work_completion_show($id)
    {
        $estimate = Estimate::findOrFail($id);
        $work_completion_pdf = $estimate->work_completion;
        return view('estimate.work_completion-show', compact('work_completion_pdf'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function edit(Estimate $estimate)
    {
        $counter = EstimateCounter::find(1);
        $estimateDetails = EstimateDetail::where('estimate_id', $estimate->id)->get();
        \Cart::clear();
        foreach ($estimateDetails as $estimateDetail) {
            \Cart::add([
                'id' => $estimateDetail->product_id,
                'name' => $estimateDetail->product->name,
                'quantity' => $estimateDetail->quantity,
                'price' => $estimateDetail->price,
                'attributes' => ['unit' => $estimateDetail->product->unit_id, 'unit_name' => $estimateDetail->product->unit->name, 'total' => $estimateDetail->sum('total')],

            ]);
        }

        $customers = ['' => '-Please select customer-'] + Customer::where('status', 1)->pluck('name', 'id')->toArray();
        $products = ['' => '-Please select product-'] + Product::where('is_visible', 1)->pluck('name', 'id')->toArray();
        $units = ['' => '-Please select unit-'] + Unit::all()->pluck('name', 'id')->toArray();
        $quotationTypes = ['' => '-Please select-'] + QuotationType::all()->pluck('quotation_type_name', 'id')->toArray();
        return view('estimate.estimate-edit', compact('counter', 'estimate', 'estimateDetails', 'customers', 'products', 'units', 'quotationTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estimate $estimate)
    {
        if (\Cart::isEmpty()) {
            Alert::error('Opps..', 'Please add at least 1 product');
            return redirect()->back();
        }
        if ($estimate->work_order !== null && isset($request->work_order)) {
            $file_path = public_path() . '/storage/' . $estimate->work_order;
            unlink($file_path);
        }
        if ($estimate->work_completion !== null && isset($request->work_completion)) {
            $file_path = public_path() . '/storage/' . $estimate->work_completion;
            unlink($file_path);
        }

        $quotation_type_id = $request->quotation_type_id;
        $customer_id = $request->customer_id;
        $estimate_number = $request->estimate_number;
        $estimate_date = $request->estimate_date;
        $expiry_date = $request->expiry_date;
        $reference_number = $request->reference_number;
        $note = $request->note;
        $work_order = $estimate['work_order'];
        $work_completion = $estimate['work_completion'];

        if (isset($request->work_order)) {
            $file = $request->work_order;
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'quotation/' . 'quotation_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_order = $fileName;
        }
        if (isset($request->work_completion)) {
            $file = $request->file('work_completion');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'quotation/' . 'quotation_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_completion = $fileName;
        }

        $discount = $request->discount;
        $tax = $request->tax;
        $vat = $request->vat;

        $rules = array(
            'quotation_type_id' => 'required',
            'customer_id' => 'required',
            'estimate_number' => 'required|numeric',
            'estimate_date' => 'required|date',
            'work_order' => 'nullable|file',
            'work_completion' => 'nullable|file',
            'expiry_date' => 'required|date',
        );

        $custom_rules = [
            'quotation_type_id.required' => 'The quotation type field is required.',
            'customer_id.required' => 'The customer field is required.'
        ];

        $this->validate($request, $rules, $custom_rules);

        if (!is_numeric($request->discount)) {
            $discount = 0;
        }
        if (!is_numeric($request->tax)) {
            $tax = 0;
        }
        if (!is_numeric($request->vat)) {
            $vat = 0;
        }


        $totalPrice = \Cart::getTotal();

        if ($request->discount_percent) {
            $discount = ($totalPrice * $discount) / 100;
        }
        if ($request->tax_percent) {
            $tax = ($totalPrice * $tax) / 100;
        }
        if ($request->vat_percent) {
            $vat = ($totalPrice * $vat) / 100;
        }


        $quotationType = QuotationType::find($quotation_type_id);


        // Open a try/catch block
        try {

            // Begin a transaction
            DB::beginTransaction();
            $estimate = Estimate::find($estimate->id);
            // dd($estimate);
            $estimate->user_id = Auth::user()->id;
            $estimate->quotation_type_id = $quotationType->id;
            $estimate->ref_number = $reference_number;
            $estimate->customer_id = $customer_id;
            $estimate->employee_id = $quotationType->employee_id;
            $estimate->estimate_date = $estimate_date;
            $estimate->expiry_date = $expiry_date;
            $estimate->note = $note;
            $estimate->sub_total = $totalPrice;
            $estimate->discount = $discount;
            $estimate->tax = $tax;
            $estimate->vat = $vat;
            $estimate->total = ($totalPrice + $tax + $vat) - $discount;
            $estimate->status = 'draft';
            $estimate->work_order = $work_order;
            $estimate->work_completion = $work_completion;


            $estimate->update();

            $estimateDetailsCount = EstimateDetail::where('estimate_id', $estimate->id)->get();
            if (count($estimateDetailsCount) > 0) {
                EstimateDetail::where('estimate_id', $estimate->id)->delete();
            }

            foreach (\Cart::getContent() as $item) {
                $estimateDetail = new EstimateDetail;
                $estimateDetail->estimate_id = $estimate->id;
                $estimateDetail->product_id = $item->id;
                $estimateDetail->quantity = $item->quantity;
                $estimateDetail->price = $item->price;
                $estimateDetail->total = $item->price * $item->quantity;
                $estimateDetail->save();
            }

            // Commit the transaction
            DB::commit();
            \Cart::clear();
            Alert::success('Success', 'Estimate has been updated successfully.');
            return redirect()->route('estimate.index');
        } catch (\Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();
            Alert::error('Opps!', $e->getMessage());
            return redirect()->back();
            // and throw the error again.
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */


    public function destroy(Estimate $estimate)
    {
        if ($estimate->work_order !== null) {
            $file_path = public_path() . '/storage/' . $estimate->work_order;
            unlink($file_path);
        }
        if ($estimate->work_completion !== null) {
            $file_path = public_path() . '/storage/' . $estimate->work_completion;
            unlink($file_path);
        }

        if ($estimate->status == 'draft') {
            EstimateDetail::where('estimate_id', $estimate->id)->delete();
            $file_path = public_path() . '/pdf/' . $estimate->file;

            if (file_exists($file_path) && !is_null($estimate->file)) {
                unlink($file_path);
            }
            Estimate::find($estimate->id)->delete();
            Alert::success('Success', 'Estimate has been deleted successfully.');
            return redirect()->route('estimate.index');
        } else {
            Alert::error('Ops.', 'This quotation has been submitted.');
            return redirect()->route('estimate.index');
        }
    }


    public function add_product(Request $request)
    {

        $product = $request->product;
        $quantity = $request->quantity;
        $price = $request->price;
        $vatTax = $request->vatTax;

        $rules = array(
            'product' => 'required',
            'quantity' => 'required|min:1|numeric',
            'price' => 'required|numeric|min:0'
        );



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(array(
                'status' => 0,
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 200); // 400 being the HTTP code for an invalid request.
        }

        $cartPro = Product::find($product);




        if ($vatTax == 'true') {
            $vat = 0;
            $tax = 0;

            $config = Configuration::pluck('config_value', 'config_name')->toArray();

            if (isset($config['vat'])) {
                $vat = $config['vat'];
            }

            if (isset($config['tax'])) {
                $tax = $config['tax'];
            }


            $price = ($price * 100) / (100 - ($vat + $tax));
            $price = ceil($price);
        }

        if (\Cart::getContent()->has($product)) {
            return Response::json(array(
                'status' => 2,
                'title' => 'This product is already exist!',

            ), 200); // 400 being the HTTP code for an invalid request.
        }

        \Cart::add([
            'id' => $cartPro->id,
            'name' => $cartPro->name,
            'quantity' => $quantity,
            'price' => $price,
            'attributes' => ['unit' => $cartPro->unit_id, 'unit_name' => $cartPro->unit->name, 'total' => $price * $quantity],
        ]);

        $data = \Cart::getContent();
        $html = view('estimate.product', compact('data'))->render();
        $cartTotal = \Cart::getTotal();

        return Response::json(array(
            'status' => 1,
            'success' => true,
            'html' => $html,
            'cartTotal' => $cartTotal

        ), 200); // 400 being the HTTP code for an invalid request.

    }

    public function price_change($id)
    {

        $product = Product::find($id);
        return Response::json(array(
            'status' => 1,
            'success' => true,
            'price' => $product->price

        ), 200); // 400 being the HTTP code for an invalid request.

    }
    public function cart_delete($id)
    {

        \Cart::remove($id);
        $totalPrice = \Cart::getTotal();
        return Response::json(array(
            'status' => 1,
            'success' => true,
            'total' => $totalPrice,
            'delid' => $id

        ), 200); // 400 being the HTTP code for an invalid request.

    }

    public function global_change(Request $request)
    {
        $cartTotal = \Cart::getTotal();
        if (\Cart::isEmpty()) {
            $cartTotal = 0;
        }

        $discount = $request->discount;
        $tax = $request->tax;
        $vat = $request->vat;

        //$dis_check=(boolean)$request->discount_check;
        //$tax_check=(boolean)$request->tax_check;

        if ($request->discount_check == "true") {
            $discount = ($discount * $cartTotal) / 100;
        }

        if ($request->vat_check == "true") {
            $vat = ($vat * $cartTotal) / 100;
        }

        if ($request->tax_check == "true") {
            $tax = ($tax * $cartTotal) / 100;
        }



        return Response::json(array(
            'status' => 1,
            'success' => true,
            'grandTotal' => ($cartTotal + $tax + $vat) - $discount,
            'tax' => $tax,
            'discount' => $discount,
            'vat' => $vat,
            'total' => $cartTotal
        ), 200); // 400 being the HTTP code for an invalid request.


    }


    public function send_email($id)
    {
        DB::beginTransaction();

        try {
            $estimate = Estimate::find($id);
            $organization = Organization::find(1);
            $estimateDetails = EstimateDetail::where('estimate_id', $id)->get();
            $customer = Customer::find($estimate->customer_id);
            $quotationType = QuotationType::find($estimate->quotation_type_id);



            $data = [
                'title' => $quotationType->subject . ' ' . $customer->company_name,
                'body' => $quotationType->description,
                'estimate' => $estimate,
                'estimateDetails' => $estimateDetails,
                'file_name' => $estimate->file
            ];




            Mail::to($customer->email)->cc([$organization->email])->send(new EstimateMail($data));


            $sent_mail = new SendMail;
            $sent_mail->estimate_id = $estimate->id;
            $sent_mail->title = $data['title'];
            $sent_mail->body = $data['body'];
            $sent_mail->save();

            $estimate->status = 'sent';
            $estimate->update();

            DB::commit();
            Alert::success('Success', 'Estimate has been mailed successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Opps!', 'There was an error sending the mail');
            return redirect()->back();
        }
    }


    // go to livewire component
    public function estimate_convert($estimate_id)
    {
        return view('estimate.estimate-convert', compact('estimate_id'));
    }






    public function estimate_accepted($estimate_id)
    {
        $estimate = Estimate::find($estimate_id);
        $estimate->status = 'accepted';
        $estimate->update();
        Alert::success('Success', 'Estimate has been accepted successfully.');
        return redirect()->back();
    }
    public function estimate_rejected($estimate_id)
    {
        $estimate = Estimate::find($estimate_id);
        $estimate->status = 'rejected';
        $estimate->update();
        Alert::success('Success', 'Estimate has been rejected successfully.');
        return redirect()->back();
    }
}
