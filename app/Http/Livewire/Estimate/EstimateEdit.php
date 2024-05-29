<?php

namespace App\Http\Livewire\Estimate;

use App\Models\AgreementCounter;
use App\Models\Customer;
use App\Models\InvoiceCounter;
use App\Models\InvoiceType;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Estimate;
use App\Models\EstimateCounter;
use App\Models\EstimateDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\QuotationType;
use Illuminate\Database\QueryException;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Cart;
use PDF;
use Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EstimateEdit extends Component
{
    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmedItem',
        'productChange' => 'product_change',
        'grandTotalChange' => 'grandTotal_Change',
        'cartDelete',
    ];

    public $beingDeleteItem = NULL;
    public $customers = [];
    public $productTypes = [];
    public $products = [];
    public $units = [];
    public $state = [];
    public $type = 0;
    public $addpro = [];
    public $newpro = [];
    public $carts = [];
    public $cartTotal;
    public $isEdit = false;
    public $isNewProduct = false;
    public $estimateCounter;
    public $estimateTypes = [];
    public $estimate;

    public $discount = 0;
    public $vat = 0;
    public $tax = 0;
    public $payment = 0;
    public $due = 0;
    public $percent = 0;
    public $discount_percent;
    public $vat_percent;
    public $tax_percent;

    public $product_id;
    public $unit;
    public $price;
    public $quantity;

    public $cartProduct;


    public function mount($estimate_id)
    {
        Cart::clear();
        $this->estimate = Estimate::find($estimate_id);

        $this->productTypes = ProductType::all_product_type();
        $this->estimateTypes = QuotationType::all();
        $this->isEdit = false;
        $this->customers = Customer::all_customer();
        $this->products = Product::all_product();
        $this->units = Unit::all_unit();

        $this->state['number'] = $this->estimate->number;
        $this->state['quotation_type_id'] = $this->estimate->quotation_type_id;
        $this->state['customer_id'] = $this->estimate->customer_id;
        $this->state['subject'] = $this->estimate->subject;
        $this->state['description'] = $this->estimate->description;
        $this->state['estimate_date'] = $this->estimate->estimate_date;
        $this->state['expiry_date'] = $this->estimate->expiry_date;
        $this->state['vat_text_visibility'] = $this->estimate->vat_text_visibility;
        $this->state['date_visibility'] = $this->estimate->date_visibility;
        $this->state['auto_seal_signature'] = $this->estimate->auto_seal_signature;

        if ($this->estimate->is_recurring == 1) {
            $this->type = 1;
            $this->state['recurring_interval'] = $this->estimate->recurring_interval;
            $this->state['recurring_start_date'] = $this->estimate->recurring_start_date;
        }

        foreach ($this->estimate->estimate_details as $detail) {
            if (Cart::isEmpty()) {
                $uid = 1;
            } else {
                $lastItem = Cart::getContent()->last();
                $uid = $lastItem->id + 1;
            }

            Cart::add([
                'id' => $uid,
                'name' => $detail->product->name,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'attributes' => [
                    'unit' => $detail->product->unit->id,
                    'product_id' => $detail->product_id,
                    'vat_tax' => 'false',
                    'unit_name' => $detail->product->unit->name
                ]
            ]);
        }
        $this->resetAll();
    }

    public function render()
    {
        return view('livewire.estimate.estimate-edit');
    }

    public function newProductToggle()
    {

        if ($this->isNewProduct) {
            $this->isNewProduct = false;
            $this->products = Product::all_product();
        } else {
            $this->isNewProduct = true;
        }
    }

    public function addNewProduct()
    {
        $validatedData = Validator::make($this->newpro, [
            'name' => 'required|string|max:255|unique:product_types',
            'product_type_id' => 'required',
            'unit_id' => 'required',
            'product_price' => 'required|numeric',
            'product_quantity' => 'required|numeric',
        ], [
            'name.required' => 'The product field is required.',
            'product_quantity.required' => 'The quantity field is required.',
            'product_type_id.required' => 'The type field is required.',
            'unit_id.required' => 'The unit field is required.',
        ])->validate();

        $item = new Product;
        $item->name = $validatedData['name'];
        $item->unit_id = $validatedData['unit_id'];
        $item->product_type_id = $validatedData['product_type_id'];
        $item->price = $validatedData['product_price'];
        $item->save();

        if (Cart::isEmpty()) {
            $uid = 1;
        } else {
            $lastItem = Cart::getContent()->last();
            $uid = $lastItem->id + 1;
        }

        Cart::add([
            'id' => $uid,
            'name' => $item->name,
            'quantity' => $validatedData['product_quantity'],
            'price' => $validatedData['product_price'],
            'attributes' => [
                'unit' => $validatedData['unit_id'],
                'product_id' => $item->id,
                'vat_tax' => 'false',
                'unit_name' => $item->unit->name
            ]
        ]);
        $this->resetAll();
        $this->resetErrorBag();
        $this->reset(['newpro']);
        $this->dispatchBrowserEvent('notification', [
            'type' => 'success',
            'title' => 'Success!',
            'msg' => 'Product added successfully.',
        ]);
    }


    public function productEdit($cart_id)
    {
        $this->isEdit = true;
        $this->isNewProduct = false;
        $this->cartProduct = Cart::get($cart_id);
        $this->addpro['product_id'] = $this->cartProduct->attributes['product_id'];
        $this->addpro['unit'] = $this->cartProduct->attributes['unit'];
        $this->addpro['price'] = $this->cartProduct->price;
        $this->addpro['vat_tax'] = $this->cartProduct->attributes['vat_tax'];
        $this->addpro['quantity'] = $this->cartProduct->quantity;
    }

    public function updateProduct()
    {
        $validatedData = Validator::make($this->addpro, [
            'product_id' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric',
            'vat_tax' => 'required',
            'quantity' => 'required|numeric|min:1',

        ])->validate();

        $item = Product::findOrFail($this->addpro['product_id']);

        $price = $validatedData['price'];
        if ($this->addpro['vat_tax'] == 'true') {
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
            $price = floatval($price);
        }

        Cart::update(
            $this->cartProduct['id'],
            array(
                'name' => $item->name,
                'price' => $price,
                'quantity' => array(
                    'relative' => false,
                    'value' => $validatedData['quantity']
                ),
                'attributes' => array(
                    'unit' => $validatedData['unit'],
                    'product_id' => $item->id,
                    'vat_tax' => $validatedData['vat_tax'],
                    'unit_name' => $item->unit->name
                )
            )
        );

        $this->resetErrorBag();
        $this->reset(['addpro', 'isEdit']);
        $this->dispatchBrowserEvent('notification', [
            'type' => 'success',
            'title' => 'Success!',
            'msg' => 'Product updated successfully.',
        ]);
        $this->resetAll();
    }

    public function delete($delete_item_id)
    {
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {
        $delete_item = Invoice::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        InvoiceDetail::where('invoice_id', $this->beingDeleteItem)->delete();
        // Payment::where('invoice_id', $this->beingDeleteItem)->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Invoice has been deleted succesfully.']);
    }


    public function grandTotal_Change()
    {

        $discountValue = (int)$this->discount;
        //dd($discountValue);
        $total = \Cart::getTotal();

        $this->grandTotal = $discountValue + $total;
    }

    public function itemAssign($product_id)
    {
        if ($product_id != "") {
            $item = Product::findOrFail($product_id);
            $this->addpro['unit'] = $item->unit_id;
            $this->addpro['price'] = $item->price;
        }
    }

    public function invoiceType($invoice_type_id)
    {

        if ($invoice_type_id != "") {
            $invoiceType = InvoiceType::findOrFail($invoice_type_id);
            $this->state['subject'] = $invoiceType->subject;
            $this->state['description'] = $invoiceType->description;
            if ($invoice_type_id == 2) {
                $this->state['type'] = 1;
            }
        }
    }





    public function addProduct()
    {

        $validatedData = Validator::make($this->addpro, [
            'product_id' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric',
            'vat_tax' => 'required',
            'quantity' => 'required|numeric|min:0',

        ])->validate();


        $item = Product::findOrFail($this->addpro['product_id']);
        if (Cart::isEmpty()) {
            $uid = 1;
        } else {
            $lastItem = Cart::getContent()->last();
            $uid = $lastItem->id + 1;
        }
        $price = $validatedData['price'];
        if ($validatedData['vat_tax'] == 'true') {

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
            $price = floatval($price);
        }

        Cart::add([
            'id' => $uid,
            'name' => $item->name,
            'quantity' => $this->addpro['quantity'],
            'price' => $price,
            'attributes' => [
                'unit' => $this->addpro['unit'],
                'product_id' => $item->id,
                'vat_tax' => $this->addpro['vat_tax'],
                'unit_name' => $item->unit->name
            ]
        ]);

        $this->resetAll();

        $this->resetErrorBag();
        $this->reset(['addpro']);
        $this->dispatchBrowserEvent('add-product', [
            'type' => 'success',
            'title' => 'Success!',
            'msg' => 'Product added successfully.',
        ]);
    }


    public function cartDelete($id)
    {

        \Cart::remove($id);
        $cartTotal = \Cart::getTotal();

        $this->resetAll();
        $this->dispatchBrowserEvent('notification', [
            'type' => 'error',
            'title' => 'Please input valid discount amount',
            'msg' => 'Product deleted successfully.',
        ]);
    }

    public function invoicePreview()
    {

        $validatedData = Validator::make($this->state, [
            'customer_id' => 'required',
            'invoice_type_id' => 'required',
            'number' => 'required',
            'date' => 'required|date',
            'expected_payment_date' => 'required|date',
            'type' => 'required',
            'subject' => 'required|string|max:500',
            'description' => 'required|string|max:500',
            'vat_text_visibility' => 'required',
            'date_visibility' => 'required',
            'auto_seal_signature' => 'required',
            'recurring_interval' => 'exclude_if:type,0|required',
            'recurring_start_date' => 'exclude_if:type,0|required',

        ], [
            'invoice_type_id.required' => 'The invoice type field is required.'
        ])->validate();

        $upload_dir = public_path();
        $newFileName = 'invoice_' . time() . '_' . $this->state['number'] . '.pdf';
        $filename = $upload_dir . '/pdf/' . $newFileName . '';

        $file_path = public_path() . '/pdf/' . $newFileName;
        if (file_exists($file_path)) {
            unlink($file_path);
        }


        if ($validatedData['invoice_type_id'] == 1) {
            $temp = 'invoice.preview-invoice-pdf';
        } elseif ($validatedData['invoice_type_id'] == 2) {
            $temp = 'invoice.preview-domain-hosting-invoice-pdf';
        } else {
            $temp = 'invoice.preview-invoice-pdf';
        }


        if ($this->discount == '') {
            $this->discount = 0;
        }
        if ($this->vat == '') {
            $this->vat = 0;
        }
        if ($this->tax == '') {
            $this->tax = 0;
        }
        if ($this->payment == '') {
            $this->payment = 0;
        }

        $invoiceType = InvoiceType::find($validatedData['invoice_type_id']);
        $employee = Employee::find($invoiceType->employee_id);
        $customer = Customer::find($validatedData['customer_id']);
        $invoice = $validatedData;
        $invoice_details = \Cart::getContent();
        $cartTotal = \Cart::getTotal();

        if ($this->discount_percent) {
            $cartDiscount = ($cartTotal * $this->discount) / 100;
        } else {
            $cartDiscount = $this->discount;
        }

        if ($this->vat_percent) {
            $cartVat = ($cartTotal * $this->vat) / 100;
        } else {
            $cartVat = $this->vat;
        }

        if ($this->tax_percent) {
            $cartTax = ($cartTotal * $this->tax) / 100;
        } else {
            $cartTax = $this->tax;
        }


        $mpdf = PDF::loadView($temp, compact('invoice_details', 'invoice', 'invoiceType', 'employee', 'customer', 'cartDiscount', 'cartVat', 'cartTax'), [], [
            'title'             => 'Invoice',
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
            'display_mode'               => 'fullpage',
            'show_watermark_image'     => true,
            'watermark_image_alpha'    => 1,
            'watermark_image_path'       => asset('bg/pad.jpg'),
        ])->save($filename);

        //dd($filename);

        $this->dispatchBrowserEvent('invoice-preview', [
            'invoiceName' => $newFileName,
        ]);
    }

    public function estimateStore()
    {
        $validatedData = Validator::make($this->state, [
            'customer_id' => 'required',
            'quotation_type_id' => 'required',
            'estimate_date' => 'required|date',
            'expiry_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'description' => 'required|string|max:500',
            'vat_text_visibility' => 'required',
            'date_visibility' => 'required',
            'auto_seal_signature' => 'required',

        ], [
            'invoice_type_id.required' => 'The invoice type field is required.'
        ])->validate();

        $agreementCounter = AgreementCounter::find(1);
        $invoiceCounter = InvoiceCounter::find(1);
        $estimateCounter = EstimateCounter::find(1);
        $cartTotal = \Cart::getTotal();

        if ($cartTotal < 1) {
            $this->dispatchBrowserEvent('estimate-store', [
                'type' => 'error',
                'title' => 'Please add at least one product.',
            ]);
            return false;
        }

        if ($this->discount == '') {
            $this->discount = 0;
        }
        if ($this->vat == '') {
            $this->vat = 0;
        }
        if ($this->tax == '') {
            $this->tax = 0;
        }

        $quotation_type_id = $this->state['quotation_type_id'];
        $customer_id = $this->state['customer_id'];
        $estimate_date = $this->state['estimate_date'] ?? null;
        $expiry_date = $this->state['expiry_date'] ?? null;
        $ref_number = $this->state['ref_number'] ?? null;
        $note = $this->state['note'] ?? null;
        $work_order = null;
        $work_completion = null;

        if (isset($this->state['work_order'])) {
            $file = $this->state['work_order'];
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'quotation/' . 'quotation_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_order = $fileName;
        }
        if (isset($this->state['work_completion'])) {
            $file = $this->state['work_completion'];
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'quotation/' . 'quotation_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_completion = $fileName;
        }


        if ($this->discount_percent == true) {
            $this->discount = ($cartTotal * $this->discount) / 100;
        }

        $discount = floatval($this->discount);
        $floatVat = floatval($this->vat);
        $floatTax = floatval($this->tax);
        $total = floor((($cartTotal * 100) / (100 - ($floatVat + $floatTax))) - $discount);

        if ($this->vat_percent == true || $this->tax_percent == true) {
            $this->vat = ($cartTotal * $this->vat) / 100;
            $this->tax = ($cartTotal * $this->tax) / 100;
        }

        $agreementCounter = AgreementCounter::find(1);
        $estimateCounter = EstimateCounter::find(1);
        $invoiceCounter = InvoiceCounter::find(1);
        $cartContents = \Cart::getContent();
        $quotationType = QuotationType::find($quotation_type_id);


        $estimate = new Estimate;
        $estimate->customer_id = $customer_id;
        $estimate->user_id = Auth::user()->id;
        $estimate->number = $estimateCounter->number;
        $estimate->quotation_type_id = $this->state['quotation_type_id'];
        $estimate->ref_number = $ref_number;
        $estimate->employee_id = $quotationType->employee_id;
        $estimate->estimate_date = $estimate_date;
        $estimate->expiry_date = $expiry_date;
        $estimate->note = $note;
        $estimate->sub_total = $cartTotal;
        $estimate->discount = $discount;
        $estimate->tax = $this->tax;
        $estimate->vat = $this->vat;
        $estimate->total = $total;
        $estimate->status = 'draft';
        $estimate->work_order = $work_order;
        $estimate->work_completion = $work_completion;
        $estimate->subject = $this->state['subject'];
        $estimate->description = $this->state['description'];
        $estimate->vat_text_visibility = $this->state['vat_text_visibility'];
        $estimate->date_visibility = $this->state['date_visibility'];
        $estimate->auto_seal_signature = $this->state['auto_seal_signature'];
        $estimate->save();


        foreach ($cartContents as $cartContent) {
            $estimateDetail = new EstimateDetail;
            $estimateDetail->estimate_id = $estimate->id;
            $estimateDetail->product_id = $cartContent->attributes['product_id'];
            $estimateDetail->quantity = $cartContent->quantity;
            $estimateDetail->price = $cartContent->price;
            $estimateDetail->total = $cartContent->price * $cartContent->quantity;
            $estimateDetail->save();
        }

        $agreementCounter->number = $agreementCounter->number + 1;
        $agreementCounter->update();

        $invoiceCounter->number = $invoiceCounter->number + 1;
        $invoiceCounter->update();

        $estimateCounter->number = $estimateCounter->number + 1;
        $estimateCounter->update();

        \Cart::clear();

        Alert::success('Success', 'Estimate has been added successfully.');
        return redirect()->route('estimate.index');
    }


    public function resetAll()
    {
        $cartTotal = Cart::getTotal();
        // dd($cartTotal);
        if ($this->discount == '') {
            $this->discount = 0;
        }
        if ($this->vat == '') {
            $this->vat = 0;
        }
        if ($this->tax == '') {
            $this->tax = 0;
        }



        if (!is_numeric($this->discount)) {
            $this->discount = 0;
        }
        if (!is_numeric($this->vat)) {
            $this->vat = 0;
        }
        if (!is_numeric($this->tax)) {
            $this->tax = 0;
        }





        if ($this->discount_percent) {
            $cartDiscount = ($cartTotal * $this->discount) / 100;
        } else {
            $cartDiscount = $this->discount;
        }



        $total_payable = ($cartTotal * 100) / (100 - (floatval($this->vat) + floatval($this->tax))) - $cartDiscount;
        $this->due = $total_payable - $cartDiscount;
    }
}
