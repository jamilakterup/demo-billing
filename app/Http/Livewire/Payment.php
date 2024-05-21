<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Unit;
use App\Models\InvoiceCounter;
use App\Models\Payment as AppPayment;
use App\Models\ProductType;
use App\Models\PaymentType;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use PDF;

use function PHPUnit\Framework\fileExists;

class Payment extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmedItem',
        'invoiceSelect' => 'invoice_select',
    ];
    public $beingDeleteItem = NULL;

    public $state = [
        'net_amount' => '',
        'vat_tax' => '',
        'commission' => '',
        'cost' => '',
    ];
    public $showInvoice = "";
    public $showPayments = [];
    public $showhistory = [];
    public $isEdit = false;
    public $searchField;
    public $currentPage = 1;
    public $payment_type_array = [];
    public $unit_array = [];
    public $payment;

    public function editPayment($payment)
    {
        $this->isEdit = true;
        $this->state = $payment;
        $this->dispatchBrowserEvent('show-modal');
    }

    public function showHistory($payment)
    {
        $this->showhistory = AppPayment::where('invoice_id', $payment['invoice_id'])->with('payment_type', 'invoice', 'invoice.customer')->get();

        $this->dispatchBrowserEvent('show-payment-hiistory');
    }

    public function showPayment($payment)
    {
        $employee = Employee::findOrFail($payment['invoice']['employee_id']);
        $mpdf = PDF::loadView('livewire/payment/payment-pdf', compact('payment', 'employee'), [], [
            'title'             => 'payment',
            'format'            => 'A4',
            'orientation'       => 'P',
            'default_font_size' => '12',
            'margin_top'        => 8,
            'margin_right'      => 12,
            'margin_bottom'     => 25,
            'margin_left'       => 10,
            'margin_header'     => 0,
            'margin_footer'     => 0,
            'show_watermark'           => false,
            'display_mode'             => 'fullpage',
            'show_watermark_image'     => false,
            'watermark_image_alpha'    => 0,
        ])->save('document.pdf');

        return \response()->download('document.pdf')->deleteFileAfterSend(true);
    }


    public function experienceCertificate($payment)
    {
        $this->state = $payment;
        $this->dispatchBrowserEvent('show-certificate');
    }
    public function showVat($payment)
    {
        $this->state = $payment;
        $this->dispatchBrowserEvent('show-vat');
    }
    public function showTax($payment)
    {
        $this->state = $payment;
        $this->dispatchBrowserEvent('show-tax');
    }


    public function invoice_select($invoice_id)
    {
        $this->state['invoice_id'] = $invoice_id;

        $this->showInvoice = Invoice::find($invoice_id);


        $this->showPayments = Apppayment::where('invoice_id', $invoice_id)->get();
    }

    public function delete($delete_item_id)
    {
        if (!Auth::user()->can('payment.delete')) {
            abort(403, 'Sorry! You are unathorized to delete payment.');
        }
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {
        if (!Auth::user()->can('payment.delete')) {
            abort(403, 'Sorry! You are unathorized to delete payment.');
        }
        $delete_item = AppPayment::findOrFail($this->beingDeleteItem);


        $file_path = public_path() . '/storage/' . $this->state['certificate_file'];
        if (file_exists($file_path) && !is_null($this->state['certificate_file'])) {
            unlink($file_path);
        }
        $file_path = public_path() . '/storage/' . $this->state['vat_file'];
        if (file_exists($file_path) && !is_null($this->state['vat_file'])) {
            unlink($file_path);
        }
        $file_path = public_path() . '/storage/' . $this->state['tax_file'];
        if (file_exists($file_path) && !is_null($this->state['tax_file'])) {
            unlink($file_path);
        }

        if (Invoice::get_due($delete_item->invoice_id) != 0) {
            $invoice = Invoice::find($delete_item->invoice_id);
            $invoice->status = 0;
            $invoice->update();
        }
        $delete_item->delete();

        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Invoice has been deleted succesfully.']);
    }

    public function addNewPayment()
    {
        $this->dispatchBrowserEvent('show-modal');
    }

    public function updated()
    {
        $net_amount = $this->state['net_amount'] !== '' ? intval($this->state['net_amount']) : 0;
        $vat_tax = $this->state['vat_tax'] !== '' ? intval($this->state['vat_tax']) : 0;
        $commission = $this->state['commission'] !== '' ? intval($this->state['commission']) : 0;
        $cost = $this->state['cost'] !== '' ? intval($this->state['cost']) : 0;

        // Calculate total cost whenever any of the input fields change
        $this->state['amount'] = $net_amount + $vat_tax + $commission + $cost;
    }

    public function paymentStore()
    {
        // dd(array_key_exists('invoice_id', $this->state));
        if (array_key_exists('invoice_id', $this->state) == false || $this->state['invoice_id'] == '') {
            $this->dispatchBrowserEvent('payment-store', [
                'type' => 'error',
                'title' => 'Please select invoice number',
            ]);
            return false;
        }


        $validatedData = Validator::make($this->state, [
            'invoice_id' => 'required',
            'payment_type_id' => 'required',
            'net_amount' => '',
            'vat_tax' => '',
            'commission' => '',
            'cost' => '',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'comment' => '',
            'certificate_file' => 'nullable|file',
            'vat_file' => 'nullable|file',
            'tax_file' => 'nullable|file',
        ])->validate();




        if (isset($this->state['certificate_file'])) {
            $file = $this->state['certificate_file'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'payment/' . 'payment_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['certificate_file'] = $file->storeAs($fileName);
        }
        if (isset($this->state['vat_file'])) {
            $file = $this->state['vat_file'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'payment/' . 'payment_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['vat_file'] = $file->storeAs($fileName);
        }
        if (isset($this->state['tax_file'])) {
            $file = $this->state['tax_file'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'payment/' . 'payment_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['tax_file'] = $file->storeAs($fileName);
        }

        $invoice = Invoice::findOrFail($this->state['invoice_id']);
        $previous_payment = AppPayment::where('invoice_id', $this->state['invoice_id'])->sum('amount');

        if ($invoice->total < ($previous_payment + $this->state['amount'])) {
            $this->dispatchBrowserEvent('payment-store', [
                'type' => 'error',
                'title' => 'The amount must not be greater than due amount',
            ]);
            return false;
        }
        AppPayment::create($validatedData);

        if (($invoice->total - ($previous_payment + $this->state['amount']) < 1)) {
            $invoice->status = 1;
            $invoice->update();
        }


        $this->state = [];

        $this->dispatchBrowserEvent('payment-store', [
            'type' => 'success',
            'title' => 'Payment has been saved successfully',
        ]);
    }

    public function exportCSV()
    {
        $payments = AppPayment::where(function ($q) {
            if ($this->searchField) {
                $q->where('about', 'like', '%' . $this->searchField . '%')
                    ->orWhereHas('invoice', function ($query) {
                        $query->where('number', 'like', '%' . $this->searchField . '%');
                    })
                    ->orWhereHas('customer', function ($query) {
                        $query->where('display_name', 'like', '%' . $this->searchField . '%');
                    });
            }
        })
            ->with('invoice')
            ->get();

        $data[] = ['ID', 'Amount'];

        foreach ($payments as $val) {
            $data[] = [
                $val->id,
                $val->amount
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


    public function paymentUpdate()
    {
        // dd(array_key_exists('invoice_id', $this->state));
        if (array_key_exists('invoice_id', $this->state) == false || $this->state['invoice_id'] == '') {
            $this->dispatchBrowserEvent('payment-store', [
                'type' => 'error',
                'title' => 'Please select invoice number',
            ]);
            return false;
        }


        $validatedData = Validator::make($this->state, [
            'invoice_id' => 'required',
            'payment_type_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'comment' => '',
            'certificate_file' => 'nullable|file',
            'vat_file' => 'nullable|file',
            'tax_file' => 'nullable|file',
        ])->validate();

        if (isset($this->state['certificate_file'])) {
            $file = $this->state['certificate_file'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'payment/' . 'payment_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['certificate_file'] = $file->storeAs($fileName);
        }
        if (isset($this->state['vat_file'])) {
            $file = $this->state['vat_file'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'payment/' . 'payment_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['vat_file'] = $file->storeAs($fileName);
        }
        if (isset($this->state['tax_file'])) {
            $file = $this->state['tax_file'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'payment/' . 'payment_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['tax_file'] = $file->storeAs($fileName);
        }

        $invoice = Invoice::findOrFail($this->state['invoice_id']);
        $previous_payment = AppPayment::where('invoice_id', $this->state['invoice_id'])->sum('amount');

        if ($invoice->total < ($previous_payment + $this->state['amount'])) {
            $this->dispatchBrowserEvent('payment-store', [
                'type' => 'error',
                'title' => 'The amount must not be greater than due amount',
            ]);
            return false;
        }
        AppPayment::updated($validatedData);

        if (($invoice->total - ($previous_payment + $this->state['amount']) < 1)) {
            $invoice->status = 1;
            $invoice->update();
        }


        $this->state = [];

        $this->dispatchBrowserEvent('payment-store', [
            'type' => 'success',
            'title' => 'Payment has been saved successfully',
        ]);
    }

    public function render()
    {
        $invoices = Invoice::with('customer')->where('status', 0)->get();
        $payment_types = PaymentType::all();
        $payments = AppPayment::with('invoice')
            ->where(function ($query) {
                $query->where('invoice_id', 'like', '%' . $this->searchField . '%');
            })
            ->orWhereHas('invoice', function ($query) {
                $query->where('number', 'like', '%' . $this->searchField . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.payment.payment', compact('payments', 'invoices', 'payment_types'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
