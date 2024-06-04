<?php

namespace App\Http\Livewire\Estimate;

use App\Models\AgreementCounter;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Estimate;
use App\Models\EstimateCounter;
use App\Models\EstimateDetail;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Unit;
use App\Models\InvoiceCounter;
use App\Models\QuotationType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Cart;
use PDF;
use Illuminate\Support\ServiceProvider;
use Auth;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;

class EstimateCreate extends Component
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
    public $estimateCounter;
    public $quotationTypes = [];
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

    public $product_id;
    public $unit;
    public $price;
    public $quantity;

    public $cartProduct;

    public function mount()
    {
        Cart::clear();
        $this->productTypes = ProductType::all_product_type();
        $this->quotationTypes = QuotationType::all();
        $this->isEdit = false;
        $this->customers = Customer::all_customer();
        $this->products = Product::all_product();
        $this->units = Unit::all_unit();
        $this->state = [];
        $this->state['number'] = EstimateCounter::find(1)->number;
    }

    public function render()
    {
        return view('livewire.estimate.estimate-create');
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
        Estimate::findOrFail($this->beingDeleteItem)->delete();
        EstimateDetail::where('estimate_id', $this->beingDeleteItem)->delete();

        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Invoice has been deleted succesfully.']);
    }


    // public function grandTotal_Change()
    // {

    //     $discountValue = (int)$this->discount;
    //     //dd($discountValue);
    //     $total = \Cart::getTotal();
    //     // dd($total);

    //     $this->grandTotal = $discountValue + $total;
    // }

    public function itemAssign($product_id)
    {
        if ($product_id != "") {
            $item = Product::findOrFail($product_id);
            $this->addpro['unit'] = $item->unit_id;
            $this->addpro['price'] = $item->price;
        }
    }

    public function quotationType($quotation_type_id)
    {
        if ($quotation_type_id != "") {
            $quotationType = QuotationType::findOrFail($quotation_type_id);
            $this->state['subject'] = $quotationType->subject;
            $this->state['description'] = $quotationType->description;
            if ($quotation_type_id == 2) {
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



    // Store section ::
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

        $this->due  = $cartTotal + $cartVat + $cartTax;

        $subtotal = number_format($cartTotal, 2, ".", "");
        $discount = number_format($cartDiscount, 2, ".", "");


        $quotation_type_id = $this->state['quotation_type_id'];
        $customer_id = $this->state['customer_id'];
        $estimate_date = $this->state['estimate_date'] ?? null;
        $expiry_date = $this->state['expiry_date'] ?? null;
        $ref_number = $this->state['ref_number'] ?? null;
        $note = $this->state['note'] ?? null;
        $work_order = null;
        $work_completion = null;

        $agreementCounter = AgreementCounter::find(1);
        $estimateCounter = EstimateCounter::find(1);
        $invoiceCounter = InvoiceCounter::find(1);
        $cartContents = \Cart::getContent();
        $quotationType = QuotationType::find($quotation_type_id);


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
        $estimate->sub_total = $subtotal;
        $estimate->discount = $discount;
        $estimate->tax = $this->tax;
        $estimate->vat = $this->vat;
        $estimate->total = $this->due;
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

        $this->due = $cartTotal + $cartVat + $cartTax;
    }
}
