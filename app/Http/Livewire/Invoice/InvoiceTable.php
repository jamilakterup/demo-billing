<?php

namespace App\Http\Livewire\Invoice;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Unit;
use App\Models\InvoiceCounter;
use App\Models\InvoiceType;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;
use Auth;
use PDF;

class InvoiceTable extends Component
{
    use WithPagination;
    protected $listeners = [
        'refreshComponent' => '$refresh',
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
    public $searchItem;


    public function mount()
    {
        \Cart::clear();
        $this->invoiceTypes = InvoiceType::all();
        $this->searchItem = '';
    }

    public function delete($delete_item_id)
    {
        if (!Auth::user()->can('invoice.delete')) {
            abort(403, 'Sorry! You are unathorized to delete invoice.');
        }
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {
        if (!Auth::user()->can('invoice.delete')) {
            abort(403, 'Sorry! You are unathorized to delete invoice.');
        }
        $delete_item = Invoice::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        InvoiceDetail::where('invoice_id', $this->beingDeleteItem)->delete();
        Payment::where('invoice_id', $this->beingDeleteItem)->delete();
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'msg' => 'Invoice has been deleted succesfully.']);
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
        if (array_key_exists('type', $this->state)) {
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



        //Invoice::create($validatedData);
        $invoiceCounter = InvoiceCounter::find(1);
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


        $newInvoice = new Invoice;
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
        $newInvoice->vat = floatval($cartVat);
        $newInvoice->tax = floatval($cartTax);
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

        $invoiceCounter->number = $invoiceCounter->number + 1;
        $invoiceCounter->update();

        \Cart::clear();

        $this->dispatchBrowserEvent('invoice-store', [
            'type' => 'success',
            'title' => 'Invoice has been created succesfully',
        ]);
    }

    public function print($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        $invoice_details = InvoiceDetail::where('invoice_id', $invoice_id)->get();

        $employee = Employee::find($invoice->employee_id);
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


        $this->dispatchBrowserEvent('print', [
            'file' => $invoice->file,
        ]);
    }

    public function exportCSV()
    {
        $invoices = Invoice::where(function ($q) {
            if ($this->searchField) {
                $q->where('number', 'like', '%' . $this->searchField . '%')
                    ->orWhereHas('customer', function ($query) {
                        if ($this->searchField) {
                            $query->where('display_name', 'like', '%' . $this->searchField . '%');
                        }
                    });
            }
        })
            ->with('customer')
            ->get();
        // $invoices = Invoice::all();
        $data[] = ['ID', 'Invoice Number', 'Date', 'Recurring', 'Interval', 'Customer', 'Sub Total', 'Due'];

        foreach ($invoices as $val) {
            $customers = Customer::where('id', $val->customer_id)->get();
            $payments = Payment::where('id', $val->id)->get();
            $paymentAmount = 0;
            foreach ($payments as $payment) {
                $paymentAmount += $payment->amount ?? 0;
            }
            foreach ($customers as $kcustomer) {
                $customerDisplayName = $kcustomer->display_name;
            }

            $data[] = [
                $val->id,
                $val->number,
                $val->date,
                $val->is_recurring ? 'Yes' : 'No',
                $val->recurring_interval,
                $customerDisplayName, // Use the customer's display name obtained in the loop
                $val->sub_total,
                $val->sub_total ? intval($val->sub_total) - $paymentAmount : '',
            ];
        }
        $filename = 'invoice_' . time() . '.csv';
        $filePath = public_path() . '/temp/' . $filename;

        // Open file handler with error handling
        $file = fopen($filePath, 'w');
        if (!$file) {
            return "Failed to open file: $filePath";
        }

        // Write data to CSV file
        foreach ($data as $row) {
            fputcsv($file, (array) $row);
        }
        fclose($file);

        // Check if file exists before downloading
        if (file_exists($filePath)) {
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return response()->download($filePath, $filename, $headers);
        } else {
            return "File not found: $filePath";
        }
    }


    public function updatedSearchField()
    {
        if ($this->searchField == 'paid') {
            $this->searchItem = 1;
        } elseif ($this->searchField == 'unpaid') {
            $this->searchItem = 0;
        } else {
            $this->searchItem = '';
        }
    }


    public function render()
    {
        $searchItem = '';
        if ($this->searchField == 'paid') {
            $searchItem = 1;
        } elseif ($this->searchField == 'unpaid') {
            $searchItem = 0;
        }

        $carts = \Cart::getContent();
        $cartTotal = \Cart::getTotal();
        $discount = $this->discount;
        if ($this->percent) {
            $discount = ($cartTotal * $discount) / 100;
        }
        $payment = $this->payment;
        $withdue = (int)$cartTotal - ((int)$payment + (int)$discount);


        $invoices = Invoice::where('cancellation', 0)
            ->where(function ($query) {
                if ($this->searchField) {
                    $query->where('number', 'like', '%' . $this->searchField . '%')
                        ->orWhere('date', 'like', '%' . $this->searchField . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->searchField . '%');
                        });
                }
                if ($this->searchItem) {
                    $query->orWhere('status', $this->searchItem);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('livewire.invoice.invoice-table', compact('invoices', 'carts', 'cartTotal', 'withdue'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
