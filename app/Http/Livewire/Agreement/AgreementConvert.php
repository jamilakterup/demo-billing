<?php

namespace App\Http\Livewire\Agreement;

use App\Models\Agreement;
use App\Models\AgreementDetail;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceCounter;
use App\Models\InvoiceDetail;
use App\Models\InvoiceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class AgreementConvert extends Component
{
    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmedItem',
        'productChange' => 'product_change',
        'grandTotalChange' => 'grandTotal_Change',
        'cartDelete',
    ];

    public $agreement_id;
    public $customer;
    public $agreement;

    public $state = [];
    public $type = 0;
    public $invoiceTypes = [];


    public function mount()
    {
        $this->state['type'] = 1;
        $this->state['agreement'] = Agreement::findOrFail($this->agreement_id);
        $this->state['number'] = Agreement::findOrFail($this->agreement_id)['number'];
        $this->invoiceTypes = InvoiceType::all()->toArray();
        $this->customer = Customer::where('id', $this->state['agreement']->customer_id)->first();
        $this->state['customer'] = Customer::where('id', $this->state['agreement']->customer_id)->first();
        // $this->state['number'] = agreementCounter::find(1)->number;
    }

    public function invoiceType($invoice_type_id)
    {
        if ($invoice_type_id != "") {
            $invoiceType = InvoiceType::findOrFail($invoice_type_id);
            $this->state['subject'] = $invoiceType->subject;
            $this->state['description'] = $invoiceType->description;
            if ($invoice_type_id == 1) {
                $this->state['type'] = 1;
            }
            if ($invoice_type_id == 2) {
                $this->state['type'] = 1;
            }
        }
    }

    public function agreementConvert()
    {
        $validatedData = Validator::make($this->state, [
            'invoice_type_id' => 'required',
            'date' => 'required',
            'expected_payment_date' => 'required',
            'vat_text_visibility' => 'required',
            'date_visibility' => 'required',
            'auto_seal_signature' => 'required',

        ], [])->validate();


        $agreement = Agreement::find($this->state['agreement']['id']);
        // $agreementCounter = agreementCounter::find(1);
        // $invoiceCounter = InvoiceCounter::find(1);
        $agreement_details = AgreementDetail::where('agreement_id', $this->state['agreement']['id'])->get();


        $invoice = new Invoice;

        $invoice->estimate_id = $this->state['agreement']['id'];
        $invoice->number = $this->state['number'];
        $invoice->customer_id = $this->state['agreement']['id'];
        $invoice->invoice_type_id = $this->state['invoice_type_id'];
        $invoice->ref_number = $this->state['agreement']['ref_number'];
        $invoice->customer_id = $this->state['agreement']['customer_id'];
        $invoice->employee_id = $this->state['agreement']['employee_id'];
        $invoice->user_id = $this->state['agreement']['user_id'];
        $invoice->date = $this->state['date'];
        $invoice->expected_payment_date = $this->state['expected_payment_date'];
        $invoice->subject = $this->state['subject'];
        $invoice->description = $this->state['description'];
        $invoice->date_visibility = $this->state['date_visibility'];
        $invoice->vat_text_visibility = $this->state['vat_text_visibility'];
        $invoice->auto_seal_signature = $this->state['auto_seal_signature'];
        $invoice->note = $this->state['agreement']['note'];
        $invoice->sub_total = $this->state['agreement']['sub_total'];
        $invoice->discount = $this->state['agreement']['discount'];
        $invoice->vat = $this->state['agreement']['vat'];
        $invoice->tax = $this->state['agreement']['tax'];
        $invoice->total = $this->state['agreement']['total'];
        $invoice->is_recurring = $this->state['type'];
        $invoice->recurring_interval = $this->state['recurring_interval'];
        $invoice->recurring_start_date = $this->state['recurring_start_date'];
        $invoice->file = $this->state['agreement']['file'];

        $invoice->save();

        foreach ($agreement_details as $agreement_detail) {
            $newInvoiceDetail = new InvoiceDetail;
            $newInvoiceDetail->invoice_id = $invoice->id;
            $newInvoiceDetail->product_id = $agreement_detail->product_id;
            $newInvoiceDetail->quantity = $agreement_detail->quantity;
            $newInvoiceDetail->price = $agreement_detail->price;
            $newInvoiceDetail->save();
        }

        // $agreementCounter->number = $agreementCounter->number + 1;
        // $agreementCounter->update();

        // $invoiceCounter->number = $invoiceCounter->number + 1;
        // $invoiceCounter->update();

        $agreement->status = 'converted';
        $agreement->update();

        DB::commit();

        Alert::success('Success', 'agreement has been converted successfully.');
        return redirect()->route('agreement.index');

        DB::rollback();
        Alert::error('Opps!', 'There was an error for converting agreement.');
        return redirect()->route('agreement.convert');
    }

    public function render()
    {
        return view('livewire.agreement.agreement-convert');
    }
}
