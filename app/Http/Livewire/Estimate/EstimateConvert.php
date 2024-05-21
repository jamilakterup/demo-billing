<?php

namespace App\Http\Livewire\Estimate;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimateCounter;
use App\Models\EstimateDetail;
use App\Models\Invoice;
use App\Models\InvoiceCounter;
use App\Models\InvoiceDetail;
use App\Models\InvoiceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPUnit\Framework\isNull;

class EstimateConvert extends Component
{
    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmedItem',
        'productChange' => 'product_change',
        'grandTotalChange' => 'grandTotal_Change',
        'cartDelete',
    ];

    public $estimate_id;
    public $customer;
    public $estimate;

    public $state = [];
    public $type = 0;
    public $invoiceTypes = [];


    public function mount()
    {
        $this->state['estimate'] = Estimate::findOrFail($this->estimate_id);
        $this->state['number'] = Estimate::findOrFail($this->estimate_id)['number'];
        $this->invoiceTypes = InvoiceType::all()->toArray();
        $this->customer = Customer::where('id', $this->state['estimate']->customer_id)->first();
        $this->state['customer'] = Customer::where('id', $this->state['estimate']->customer_id)->first();
        // $this->state['number'] = EstimateCounter::find(1)->number;
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

    public function estimateConvert()
    {
        // dd($this->state);
        $validatedData = Validator::make($this->state, [
            'invoice_type_id' => 'required',
            'date' => 'required',
            'type' => 'required',
            'expected_payment_date' => 'required',
            'vat_text_visibility' => 'required',
            'date_visibility' => 'required',
            'auto_seal_signature' => 'required',

        ], [])->validate();


        $estimate = Estimate::find($this->state['estimate']['id']);
        // $estimateCounter = EstimateCounter::find(1);
        // $invoiceCounter = InvoiceCounter::find(1);
        $estimate_details = EstimateDetail::where('estimate_id', $this->state['estimate']['id'])->get();

        if ($this->state['type'] == 0) {
            $recurring_interval = 0;
            $recurring_start_date = null;
        }

        $invoice = new Invoice;

        $invoice->estimate_id = $this->state['estimate']['id'];
        $invoice->number = $this->state['number'];
        $invoice->customer_id = $this->state['estimate']['id'];
        $invoice->invoice_type_id = $this->state['invoice_type_id'];
        $invoice->ref_number = $this->state['estimate']['ref_number'];
        $invoice->customer_id = $this->state['estimate']['customer_id'];
        $invoice->employee_id = $this->state['estimate']['employee_id'];
        $invoice->user_id = $this->state['estimate']['user_id'];
        $invoice->date = $this->state['date'];
        $invoice->expected_payment_date = $this->state['expected_payment_date'];
        $invoice->subject = $this->state['subject'];
        $invoice->description = $this->state['description'];
        $invoice->date_visibility = $this->state['date_visibility'];
        $invoice->vat_text_visibility = $this->state['vat_text_visibility'];
        $invoice->auto_seal_signature = $this->state['auto_seal_signature'];
        $invoice->note = $this->state['estimate']['note'];
        $invoice->sub_total = $this->state['estimate']['sub_total'];
        $invoice->discount = $this->state['estimate']['discount'];
        $invoice->vat = $this->state['estimate']['vat'];
        $invoice->tax = $this->state['estimate']['tax'];
        $invoice->total = $this->state['estimate']['total'];
        $invoice->is_recurring = $this->state['type'];
        $invoice->recurring_interval = $this->state['recurring_interval'] ?? $recurring_interval;
        $invoice->recurring_start_date = $this->state['recurring_start_date'] ?? $recurring_start_date;
        $invoice->file = $this->state['estimate']['file'];

        $invoice->save();

        foreach ($estimate_details as $estimate_detail) {
            $newInvoiceDetail = new InvoiceDetail;
            $newInvoiceDetail->invoice_id = $invoice->id;
            $newInvoiceDetail->product_id = $estimate_detail->product_id;
            $newInvoiceDetail->quantity = $estimate_detail->quantity;
            $newInvoiceDetail->price = $estimate_detail->price;
            $newInvoiceDetail->save();
        }

        // $estimateCounter->number = $estimateCounter->number + 1;
        // $estimateCounter->update();

        // $invoiceCounter->number = $invoiceCounter->number + 1;
        // $invoiceCounter->update();

        $estimate->status = 'converted';
        $estimate->update();

        DB::commit();

        Alert::success('Success', 'Estimate has been converted successfully.');
        return redirect()->route('estimate.index');

        DB::rollback();
        Alert::error('Opps!', 'There was an error for converting estimate.');
        return redirect()->route('estimate.convert');
    }


    public function render()
    {
        return view('livewire.estimate.estimate-convert');
    }
}
