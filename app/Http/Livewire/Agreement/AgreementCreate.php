<?php

namespace App\Http\Livewire\Agreement;

use App\Models\Agreement;
use App\Models\AgreementCounter;
use App\Models\AgreementDetail;
use App\Models\AgreementType;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Customer;
use App\Models\InvoiceCounter;
use App\Models\InvoiceType;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Estimate;
use App\Models\EstimateCounter;
use App\Models\EstimateDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\QuotationType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Cart;
use PDF;
use Auth;
use RealRashid\SweetAlert\Facades\Alert;


class AgreementCreate extends Component
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
    public $agreementTypes = [];
    public $vat_tax = false;
    public $vatTaxVal = 0;
    public $vatTax_parcentage = false;


    public $discount = 0;
    public $vat = 0;
    public $tax = 0;
    public $due = 0;
    public $percent = 0;
    public $discount_percent;
    public $vat_percent;
    public $tax_percent;
    public $grandTotal;

    public $product_id;
    public $unit;
    public $price;
    public $quantity;
    public $work_order_file;
    public $work_completion_file;

    public $cartProduct;

    public function mount()
    {
        Cart::clear();
        $this->productTypes = ProductType::all_product_type();
        $this->agreementTypes = AgreementType::all();
        $this->isEdit = false;
        $this->customers = Customer::all_customer();
        $this->products = Product::all_product();
        $this->units = Unit::all_unit();
        $this->state = [];
        $this->state['number'] = AgreementCounter::find(1)->number;
    }

    public function render()
    {
        return view('livewire.agreement.agreement-create');
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
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
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

        if (isset($this->newpro['image'])) {
            $file = $this->newpro['image'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'product/' . 'product_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['image'] = $fileName;
        }

        $item = new Product;
        $item->name = $validatedData['name'];
        $item->image = $validatedData['image'] ?? null;
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
            'image' => $item->image,
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

    public function agreementType($agreement_type_id)
    {

        if ($agreement_type_id != "") {
            $agreementType = AgreementType::findOrFail($agreement_type_id);
            $this->state['subject'] = $agreementType->subject;
            $this->state['description'] = $agreementType->description;
            if ($agreement_type_id == 2) {
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
            'quantity' => 'required|numeric|min:1',

        ])->validate();

        $item = Product::findOrFail($this->addpro['product_id']);
        // if (Cart::isEmpty()) {
        //     $uid = 1;
        // } else {
        //     $lastItem = Cart::getContent()->last();
        //     $uid = $lastItem->id + 1;
        // }

        $price = $this->addpro['price'];

        if ($this->addpro['vat_tax'] == 'true') {
            $vatTax = floatval($this->addpro['vatTaxVal']);
            $price = ($price * 100) / (100 - $vatTax);
            $price = floatval(number_format($price, 2, ".", ""));
        }
        Cart::add([
            'id' => $item->id,
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
            'agreement_type_id' => 'required',
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
            'agreement_type_id.required' => 'The invoice type field is required.'
        ])->validate();

        $upload_dir = public_path();
        $newFileName = 'invoice_' . time() . '_' . $this->state['number'] . '.pdf';
        $filename = $upload_dir . '/pdf/' . $newFileName . '';

        $file_path = public_path() . '/pdf/' . $newFileName;
        if (file_exists($file_path)) {
            unlink($file_path);
        }


        if ($validatedData['agreement_type_id'] == 1) {
            $temp = 'invoice.preview-invoice-pdf';
        } elseif ($validatedData['agreement_type_id'] == 2) {
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

        $invoiceType = QuotationType::find($validatedData['agreement_type_id']);
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



    // Store section ::
    public function agreementStore()
    {
        if (\Cart::isEmpty()) {
            Alert::error('Opps..', 'Please add at least 1 product');
            return redirect()->back();
        }


        $validatedData = Validator::make($this->state, [
            'customer_id' => 'required',
            'agreement_type_id' => 'required',
            'agreement_date' => 'required',
            'expiry_date' => 'required',

        ])->validate();

        if (!is_numeric($this->discount)) {
            $discount = 0;
        }
        if (!is_numeric($this->tax)) {
            $tax = 0;
        }
        if (!is_numeric($this->vat)) {
            $vat = 0;
        }


        $number = $this->state['number'];
        $agreement_type_id = $this->state['agreement_type_id'];
        $ref_number = $this->state['ref_number'] ?? null;
        $customer_id = $this->state['customer_id'];
        $agreement_date = $this->state['agreement_date'] ?? null;
        $expiry_date = $this->state['expiry_date'] ?? null;
        $note = $this->state['note'] ?? null;
        $work_order = null;
        $work_completion = null;

        if (isset($this->state['work_order'])) {
            $file = $this->state['work_order'];
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'agreement/' . 'agreement_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_order = $fileName;
        }
        if (isset($this->state['work_completion'])) {
            $file = $this->state['work_completion'];
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'agreement/' . 'agreement_' . uniqid() . '.' . $fileExtension; //set to db
            $file->storeAs('public', $fileName);
            $work_completion = $fileName;
        }



        $discount = $this->discount;
        $tax = $this->tax;
        $vat = $this->vat;


        $totalPrice = \Cart::getTotal();

        if ($this->discount_percent) {
            $discount = ($totalPrice * $discount) / 100;
        }
        if ($this->tax_percent) {
            $tax = ($totalPrice * $tax) / 100;
        }

        if ($this->vat_percent) {
            $vat = ($totalPrice * $vat) / 100;
        }

        $agreementCounter = AgreementCounter::find(1);
        $invoiceCounter = InvoiceCounter::find(1);
        $estimateCounter = EstimateCounter::find(1);
        $agreementType = AgreementType::find($agreement_type_id);


        $agreement = new Agreement;
        $agreement->user_id = Auth::user()->id;
        $agreement->number = $number;
        $agreement->agreement_type_id = $agreementType->id;
        $agreement->ref_number = $ref_number;
        $agreement->customer_id = $customer_id;
        $agreement->employee_id = $agreementType->employee_id;
        $agreement->agreement_date = $agreement_date;
        $agreement->expiry_date = $expiry_date;
        $agreement->note = $note;
        $agreement->sub_total = $totalPrice;
        $agreement->discount = $discount;
        $agreement->tax = $tax;
        $agreement->vat = $vat;
        $agreement->total = ($totalPrice + $tax + $vat) - $discount;
        $agreement->status = 'draft';
        $agreement->work_order = $work_order;
        $agreement->work_completion = $work_completion;
        $agreement->save();

        foreach (\Cart::getContent() as $item) {
            $agreementDetail = new AgreementDetail;
            $agreementDetail->agreement_id = $agreement->id;
            $agreementDetail->product_id = $item->id;
            $agreementDetail->quantity = $item->quantity;
            $agreementDetail->price = $item->price;
            $agreementDetail->total = $item->price * $item->quantity;
            $agreementDetail->save();
        }

        $agreementCounter->number = $agreementCounter->number + 1;
        $agreementCounter->update();

        $invoiceCounter->number = $invoiceCounter->number + 1;
        $invoiceCounter->update();

        $estimateCounter->number = $estimateCounter->number + 1;
        $estimateCounter->update();

        \Cart::clear();

        Alert::success('Success', 'Agreement has been added successfully.');
        return redirect()->route('agreement.index');
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


        // $total_payable = $cartTotal + $cartTax + $cartVat;
        $total_payable = ($cartTotal * 100) / (100 - ($cartTax + $cartVat));
        $this->due = $total_payable - $cartDiscount;
    }
}
