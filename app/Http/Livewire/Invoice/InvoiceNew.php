<?php

namespace App\Http\Livewire\Invoice;

use App\Models\AgreementCounter;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EstimateCounter;
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
use Livewire\WithFileUploads;

class InvoiceNew extends Component
{
    use WithFileUploads;

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
    public $vat_tax = false;
    public $vatTaxVal = 0;
    public $vatTax_parcentage = false;


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

    public function mount()
    {
        Cart::clear();
        $this->productTypes = ProductType::all_product_type();
        $this->invoiceTypes = InvoiceType::all();
        $this->isEdit = false;
        $this->customers = Customer::all_customer();
        $this->products = Product::all_product();
        $this->units = Unit::all_unit();
        $this->state = [];
        $this->state['number'] = InvoiceCounter::find(1)->number;
    }

    public function render()
    {
        return view('livewire.invoice.invoice-new');
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
            'name' => 'required|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
            'description' => 'nullable',
            'unit_id' => 'required|integer',
            'product_type_id' => 'required|integer',
            'price' => 'required|integer',
            'product_quantity' => 'required',
        ])->validate();

        if (isset($this->newpro['image']) && !is_null($this->newpro['image'])) {
            $file = $this->newpro['image'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'product/' . 'product_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $this->newpro['image'] = $fileName;
        }

        $item = new Product;
        $item->name = $this->newpro['name'];
        if (isset($this->newpro['image'])) {
            $item->image = $this->newpro['image'];
        }
        $item->unit_id = $this->newpro['unit_id'];
        $item->product_type_id = $this->newpro['product_type_id'];
        $item->price = $this->newpro['price'];
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
            'image' => $item->image,
            'quantity' => $validatedData['product_quantity'],
            'price' => $validatedData['price'],
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
        $this->addpro['quantity'] = $this->cartProduct->quantity;

        if ($this->cartProduct->vat_tax == 'true') {
            $this->vat_tax = true;
            $this->addpro['vatTaxVal'] = $this->cartProduct->vatTaxVal;
            $this->addpro['vatTax_parcentage'] = $this->cartProduct->vatTax_parcentage;
        }
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
            $vatTax = floatval($this->addpro['vatTaxVal']);
            $price = ($price * 100) / (100 - $vatTax);
            $price = floatval(number_format($price, 2, ".", ""));
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

        $this->resetAll();

        $this->resetErrorBag();
        $this->reset(['addpro', 'isEdit']);
        $this->dispatchBrowserEvent('notification', [
            'type' => 'success',
            'title' => 'Success!',
            'msg' => 'Product updated successfully.',
        ]);
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
        // dd($total);

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

        $price = $this->addpro['price'];

        if ($this->addpro['vat_tax'] == 'true') {
            $vatTax = floatval($this->addpro['vatTaxVal']);
            $price = ($price * 100) / (100 - $vatTax);
            $price = floatval(number_format($price, 2, ".", ""));
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

        $total_payable = $cartTotal + $cartVat + $cartTax;
        $Totalpayment = $cartDiscount + $this->payment;

        $mpdf = PDF::loadView($temp, compact('invoice_details', 'invoice', 'invoiceType', 'employee', 'customer', 'cartDiscount', 'cartVat', 'cartTax', 'cartTotal', 'total_payable', 'Totalpayment'), [], [
            'title'             => 'Invoice',
            'format'            => 'A4',
            'orientation'       => 'P',
            'default_font_size' => '12',
            'margin_top'        => 54,
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


            $agreementCounter = AgreementCounter::find(1);
            $invoiceCounter = InvoiceCounter::find(1);
            $estimateCounter = EstimateCounter::find(1);
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

            $total_payable = $cartTotal + $cartVat + $cartTax;
            $Totalpayment = $cartDiscount + $this->payment;
            $this->due = $total_payable - $Totalpayment;

            if (($cartDiscount + $this->payment) > $total_payable) {
                $this->dispatchBrowserEvent('invoice-store', [
                    'type' => 'error',
                    'title' => 'Invalid due amount',
                ]);
                return false;
            }

            $invoiceType = InvoiceType::find($this->state['invoice_type_id']);


            $newInvoice = new Invoice;
            $newInvoice->customer_id = $this->state['customer_id'];
            $newInvoice->user_id = Auth::user()->id;
            $newInvoice->number = $invoiceCounter->number;
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

            $newInvoice->sub_total = $subtotal;
            $newInvoice->discount = $discount;
            $newInvoice->vat = $vat;
            $newInvoice->tax = $tax;


            $newInvoice->total = $this->due;

            if (($cartDiscount + $this->payment) == $total_payable) {
                $newInvoice->status = 1;
            }
            $newInvoice->save();

            foreach ($cartContents as $cartContent) {
                $newInvoiceDetail = new InvoiceDetail;
                $newInvoiceDetail->invoice_id = $newInvoice->id;
                $newInvoiceDetail->product_id = $cartContent->attributes['product_id'];
                $newInvoiceDetail->quantity = $cartContent->quantity;
                $newInvoiceDetail->price = $cartContent->price;
                $newInvoiceDetail->save();
            }



            if ($this->payment > 0) {
                $payment = new Payment;
                $payment->invoice_id = $newInvoice->id;
                $payment->payment_type_id = 1;
                $payment->amount = $this->payment;
                $payment->date = $this->state['date'];
                $payment->save();
            }

            $agreementCounter->number = $agreementCounter->number + 1;
            $agreementCounter->update();

            $invoiceCounter->number = $invoiceCounter->number + 1;
            $invoiceCounter->update();

            $estimateCounter->number = $estimateCounter->number + 1;
            $estimateCounter->update();

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

        $total_payable = $cartTotal + $cartVat + $cartTax;
        $Totalpayment = $cartDiscount + $this->payment;
        $this->due = $total_payable - $Totalpayment;
    }
}
