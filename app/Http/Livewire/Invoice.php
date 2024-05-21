<?php

namespace App\Http\Livewire;

use App\Models\AgreementCounter;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\EstimateCounter;
use App\Models\Product;
use App\Models\Unit;
use App\Models\InvoiceCounter;
use App\Models\InvoiceType;
use App\Models\Payment;
use App\Models\Invoice as InvoiceNew;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;
use Auth;

class Invoice extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmedItem',
        'productChange' => 'product_change',
        'grandTotalChange' => 'grandTotal_Change'
    ];
    public $beingDeleteItem = NULL;
    public $customers = [];
    public $products = [];
    public $units = [];
    public $state = [];
    public $type = 0;
    public $addpro = [];
    // public $carts=[];
    public $isEdit = false;
    public $searchField;
    public $currentPage = 1;
    public $invoiceCounter;
    public $invoiceTypes = [];


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


    public function mount()
    {
        \Cart::clear();
        $this->invoiceTypes = InvoiceType::all();
    }

    public function delete($delete_item_id)
    {
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {
        $delete_item = InvoiceNew::findOrFail($this->beingDeleteItem);
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

    public function product_change()
    {
        if ($this->addpro['product_id']) {

            $item = Product::findOrFail($this->addpro['product_id']);
            $this->addpro['unit'] = $item->unit_id;
            $this->addpro['price'] = $item->price;
        } else {
            $this->addpro = [];
        }
    }



    public function dehydrate()
    {
        $this->dispatchBrowserEvent('select-reload');
        $this->resetErrorBag();
    }

    public function addProduct()
    {

        $validatedData = Validator::make($this->addpro, [
            'product_id' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|min:1',

        ])->validate();



        $item = Product::findOrFail($this->addpro['product_id']);
        \Cart::add([
            'id' => $this->addpro['product_id'],
            'name' => $item->name,
            'quantity' => $this->addpro['quantity'],
            'price' => $this->addpro['price'],
            'attributes' => ['unit' => $this->addpro['unit'], 'unit_name' => $item->unit->name],
        ]);


        $cartTotal = \Cart::getTotal();

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



        $total_payable = $cartTotal + $cartTax + $cartVat;
        $Totalpayment = $cartDiscount + $this->payment;


        $this->due = $total_payable - $Totalpayment;


        $this->addpro = [];
        // $carts=\Cart::getContent();
        // $totalPrice=\Cart::getTotal();
        // $totalItem=\Cart::getContent()->count();

        // if($this->product_id=='' || $this->unit=='' || $this->price=='' || $this->quantity==''){
        //     $this->dispatchBrowserEvent('add-product',[
        //         'type'=>'error',
        //         'title'=>'Invoice has been created succesfully',
        //     ]);
        // }
        //$this->dispatchBrowserEvent('show-modal');
    }


    public function cartDelete($id)
    {

        \Cart::remove($id);
        $cartTotal = \Cart::getTotal();

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



        $total_payable = $cartTotal + $cartTax + $cartVat;
        $Totalpayment = $cartDiscount + $this->payment;


        $this->due = $total_payable - $Totalpayment;
    }



    public function addNewInvoice()
    {
        $this->isEdit = false;
        $this->customers = Customer::all_customer();
        $this->products = Product::all_product();
        $this->units = Unit::all_unit();
        $this->state = [];
        $this->state['number'] = InvoiceCounter::find(1)->number;
        $this->dispatchBrowserEvent('show-modal');
    }

    public function invoiceStore()
    {
        // dd('jasl');
        if (array_key_exists('type', $this->state)) {
            // return $this->type;
            $this->type = $this->state['type'];
        }



        $validatedData = Validator::make($this->state, [
            'customer_id' => 'required',
            'invoice_type_id' => 'required',
            'number' => 'required',
            'date' => 'required',
            'due_date' => 'required',
            'type' => 'required',
            'recurring_interval' => 'exclude_if:type,0|required',
            'recurring_start_date' => 'exclude_if:type,0|required',

        ], [
            'invoice_type_id.required' => 'The invoice type field is required.'
        ])->validate();


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

        $agreementCounter = AgreementCounter::find(1);
        $estimateCounter = EstimateCounter::find(1);
        $invoiceCounter = InvoiceCounter::find(1);
        $invoiceType = InvoiceType::find($this->state['invoice_type_id']);


        $newInvoice = new InvoiceNew;
        $newInvoice->customer_id = $this->state['customer_id'];
        $newInvoice->user_id = Auth::user()->id;
        $newInvoice->number = $invoiceCounter->number;
        $newInvoice->invoice_type_id = $this->state['invoice_type_id'];
        $newInvoice->employee_id = $invoiceType->employee_id;
        $newInvoice->date = $this->state['date'];
        $newInvoice->due_date = $this->state['due_date'];

        if ($this->type == "1") {
            $newInvoice->is_recurring = $this->state['type'];
            $newInvoice->recurring_interval = $this->state['recurring_interval'];
            $newInvoice->recurring_start_date = $this->state['recurring_start_date'];
        }

        $newInvoice->sub_total = $cartTotal;
        $newInvoice->discount = $cartDiscount;
        $newInvoice->vat = ceil($cartVat);
        $newInvoice->tax = ceil($cartTax);
        $newInvoice->total = ($cartTotal + $cartVat + $cartTax) - $cartDiscount;
        if (($cartTotal + $cartVat + $cartTax) == ($cartDiscount + $this->payment)) {
            $newInvoice->status = 1;
        }
        $newInvoice->save();

        foreach ($cartContents as $cartContent) {
            $newInvoiceDetail = new InvoiceDetail;
            $newInvoiceDetail->invoice_id = $newInvoice->id;
            $newInvoiceDetail->product_id = $cartContent->id;
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



        $this->dispatchBrowserEvent('invoice-store', [
            'type' => 'success',
            'title' => 'Invoice has been created succesfully',
        ]);
    }

    public function render()
    {
        $carts = \Cart::getContent();
        $cartTotal = \Cart::getTotal();
        $discount = $this->discount;
        if ($this->percent) {
            $discount = ($cartTotal * $discount) / 100;
        }
        $payment = $this->payment;
        $withdue = (int)$cartTotal - ((int)$payment + (int)$discount);



        $invoices = InvoiceNew::with('customer')
            ->where(function ($query) {
                $query->where('number', 'like', '%' . $this->searchField . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.invoice.invoice', compact('invoices', 'carts', 'cartTotal', 'withdue'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
