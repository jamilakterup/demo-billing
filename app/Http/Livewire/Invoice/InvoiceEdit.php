<?php

namespace App\Http\Livewire\Invoice;

use Livewire\Component;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Unit;
use App\Models\InvoiceCounter;
use App\Models\InvoiceType;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Cart;
use PDF;
use Illuminate\Support\ServiceProvider;
use Auth;

use function PHPUnit\Framework\isNull;

class InvoiceEdit extends Component
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
    public $invoiceCounter;
    public $invoiceTypes = [];
    public $invoice;

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


    public function mount($id)
    {
        Cart::clear();
        $this->invoice = Invoice::find($id);

        if ($this->invoice->status) {
            abort(403, 'Sorry! You are unathorized to edit invoice.');
        }
        $this->productTypes = ProductType::all_product_type();
        $this->invoiceTypes = InvoiceType::all();
        $this->isEdit = false;
        $this->customers = Customer::all_customer();
        $this->products = Product::all_product();
        $this->units = Unit::all_unit();

        $this->state['number'] = $this->invoice->number;
        $this->state['invoice_type_id'] = $this->invoice->invoice_type_id;
        $this->state['customer_id'] = $this->invoice->customer_id;
        $this->state['subject'] = $this->invoice->subject;
        $this->state['description'] = $this->invoice->description;
        $this->state['date'] = $this->invoice->date;
        $this->state['type'] = $this->invoice->is_recurring;
        $this->state['vat_text_visibility'] = $this->invoice->vat_text_visibility;
        $this->state['date_visibility'] = $this->invoice->date_visibility;
        $this->state['auto_seal_signature'] = $this->invoice->auto_seal_signature;

        if ($this->invoice->is_recurring == 1) {
            $this->type = 1;
            $this->state['recurring_interval'] = $this->invoice->recurring_interval;
            $this->state['recurring_start_date'] = $this->invoice->recurring_start_date;
        }
        $this->state['expected_payment_date'] = $this->invoice->expected_payment_date;

        foreach ($this->invoice->invoice_details as $detail) {
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
        return view('livewire.invoice.invoice-edit');
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
        Payment::where('invoice_id', $this->beingDeleteItem)->delete();
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

    public function invoiceStore()
    {
        if (array_key_exists('type', $this->state)) {
            // return $this->type;
            $this->type = $this->state['type'];
        }



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

        try {
            DB::beginTransaction();


            $cartTotal = \Cart::getTotal();
            $cartContents = \Cart::getContent();

            if ($cartTotal < 1) {
                $this->dispatchBrowserEvent('invoice-store', [
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
            if ($this->payment == '') {
                $this->payment = 0;
            }



            if (!is_numeric($this->discount)) {
                $this->dispatchBrowserEvent('invoice-store', [
                    'type' => 'error',
                    'title' => 'Please input valid discount amount',
                ]);
                return false;
            }
            if (!is_numeric($this->vat)) {
                $this->dispatchBrowserEvent('invoice-store', [
                    'type' => 'error',
                    'title' => 'Please input valid vat amount',
                ]);
                return false;
            }
            if (!is_numeric($this->tax)) {
                $this->dispatchBrowserEvent('invoice-store', [
                    'type' => 'error',
                    'title' => 'Please input valid tax amount',
                ]);
                return false;
            }
            if (!is_numeric($this->payment)) {
                $this->dispatchBrowserEvent('invoice-store', [
                    'type' => 'error',
                    'title' => 'Please input valid payment amount',
                ]);
                return false;
            }





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





            if (($cartTotal + $cartTax + $cartVat) < ($cartDiscount + $this->payment)) {
                $this->dispatchBrowserEvent('invoice-store', [
                    'type' => 'error',
                    'title' => 'Invalid due amount',
                ]);
                return false;
            }

            $invoiceType = InvoiceType::find($this->state['invoice_type_id']);


            $newInvoice = $this->invoice;
            $newInvoice->customer_id = $this->state['customer_id'];
            $newInvoice->user_id = Auth::user()->id;
            $newInvoice->invoice_type_id = $this->state['invoice_type_id'];
            $newInvoice->employee_id = $invoiceType->employee_id;
            $newInvoice->date = $this->state['date'];
            $newInvoice->expected_payment_date = $this->state['expected_payment_date'];
            $newInvoice->subject = $this->state['subject'];
            $newInvoice->description = $this->state['description'];
            $newInvoice->vat_text_visibility = $this->state['vat_text_visibility'];
            $newInvoice->date_visibility = $this->state['date_visibility'];
            $newInvoice->auto_seal_signature = $this->state['auto_seal_signature'];


            if ($this->type == "1") {
                $newInvoice->is_recurring = $this->state['type'];
                $newInvoice->recurring_interval = $this->state['recurring_interval'];
                $newInvoice->recurring_start_date = $this->state['recurring_start_date'];
            }

            $subtotal = number_format($cartTotal, 2, ".", "");
            $discount = number_format($cartDiscount, 2, ".", "");
            $vat = number_format($cartVat, 2, ".", "");
            $tax = number_format($cartTax, 2, ".", "");
            $total = ($subtotal + $vat + $tax) - $discount;

            $newInvoice->sub_total = $subtotal;
            $newInvoice->discount = $discount;
            $newInvoice->vat = $vat;
            $newInvoice->tax = $tax;


            $newInvoice->total = floatval($total);

            if (($subtotal + $vat + $tax) == ($discount + $this->payment)) {
                $newInvoice->status = 1;
            }
            $newInvoice->update();
            InvoiceDetail::where('invoice_id', $this->invoice->id)->delete();
            foreach ($cartContents as $cartContent) {

                $newInvoiceDetail = new InvoiceDetail;
                $newInvoiceDetail->invoice_id = $newInvoice->id;
                $newInvoiceDetail->product_id = $cartContent->attributes['product_id'];
                $newInvoiceDetail->quantity = $cartContent->quantity;
                $newInvoiceDetail->price = $cartContent->price;
                $newInvoiceDetail->save();
            }



            Payment::where('invoice_id', $this->invoice->id)->delete();
            if ($this->payment > 0) {
                $payment = new Payment;
                $payment->invoice_id = $newInvoice->id;
                $payment->payment_type_id = 1;
                $payment->amount = $this->payment;
                $payment->date = $this->state['date'];
                $payment->save();
            }


            \Cart::clear();


            $this->emitTo("invoice.invoice-table", "refreshComponent");
            $this->dispatchBrowserEvent('invoice-store', [
                'type' => 'success',
                'title' => 'Invoice has been created succesfully',
            ]);

            // Commit the transaction
            DB::commit();
        } catch (QueryException $e) {

            // Roll back the transaction in case of an error
            DB::rollBack();
            $errorMessage = $e->getMessage();
            throw new \Exception($errorMessage);
            // Handle the exception or error appropriately
        }
    }

    public function resetAll()
    {
        $totalPayment = Payment::where('invoice_id', $this->invoice->id)->first();

        $this->discount = $this->invoice->discount;
        $this->vat = $this->invoice->vat;
        $this->tax = $this->invoice->tax;
        $this->due = $this->invoice->due;

        $cartTotal = Cart::getTotal();
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



        if (!is_numeric($this->discount)) {
            $this->discount = 0;
        }
        if (!is_numeric($this->vat)) {
            $this->vat = 0;
        }
        if (!is_numeric($this->tax)) {
            $this->tax = 0;
        }
        if (!is_numeric($this->payment)) {
            $this->payment = 0;
        }



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

        if (!is_null($totalPayment) && isset($totalPayment['amount'])) {
            $this->payment = $totalPayment['amount'];
        }

        $total_payable = $cartTotal + $cartTax + $cartVat;
        $Totalpayment = $cartDiscount + $this->payment;

        $this->due = $total_payable - $Totalpayment;
    }
}
