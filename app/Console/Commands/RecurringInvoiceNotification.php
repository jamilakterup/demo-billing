<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecurringMail;
use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Organization;

class RecurringInvoiceNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurringinvoice:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recurring invoice everyday notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $recurring_invoices=Invoice::with('customer')->where('is_recurring',1)->get();
        $organization=Organization::find(1);
        $emails=User::where('is_recurring_mail',1)->pluck('email')->toArray();
        if(count($recurring_invoices)){
            foreach($recurring_invoices as $recurring_invoice){

                $current_date = date("Y-m-d"); // or your date as well
                $date2=date_create($current_date);
                $date1=date_create($recurring_invoice->date);
                $diff = date_diff($date1,$date2);

                $day=$diff->format("%a");

                $division=($day-0)%$recurring_invoice->recurring_interval;

                //Log::info($division);
                //dd($total_day);

                if($division==0){
                    Mail::to($emails)->send(new RecurringMail($recurring_invoice));
                }
            }
        }

    }
}
