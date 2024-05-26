<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public $user;
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    public function index()
    {
        if (!$this->user->can('customer.view')) {
            abort(403, 'Sorry! You are unathorized to view any customer.');
        }
        $customers = Customer::all();
        return view('customer.customer-index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->user->can('customer.create')) {
            abort(403, 'Sorry! You are unathorized to create any customer.');
        }
        return view('customer.customer-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->user->can('customer.create')) {
            abort(403, 'Sorry! You are unathorized to create any customer.');
        }

        request()->validate([
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|max:255',
            'company_name' => 'required|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|numeric',
            'company_address' => 'nullable|max:255',
            // 'company_website' => 'required|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->display_name = $request->display_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;

        $customer->company_name = $request->company_name;
        $customer->company_email = $request->company_email;
        $customer->company_website = $request->company_website;
        $customer->company_phone = $request->company_phone;
        $customer->company_address = $request->company_address;


        $customer->save();



        if ($request->hasFile('company_logo')) {
            $image = $request->file('company_logo');
            $ext = $image->getClientOriginalExtension();
            $file_name = 'company_logo_' . $customer->id . '.' . $ext;

            $des = public_path() . '/logo';
            $image->move($des, $file_name);
            $customer->company_logo = $file_name;
            $customer->save();
        }

        Alert::success('Success', 'Customer has been added successfully.');
        return redirect()->route('customer.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        if (!$this->user->can('customer.show')) {
            abort(403, 'Sorry! You are unathorized to show any customer.');
        }

        $customer = Customer::find($customer->id);
        return view('customer.customer-show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        if (!$this->user->can('customer.edit')) {
            abort(403, 'Sorry! You are unathorized to edit any customer.');
        }
        return view('customer.customer-edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        if (!$this->user->can('customer.edit')) {
            abort(403, 'Sorry! You are unathorized to edit any customer.');
        }
        request()->validate([
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|max:255',
            'company_name' => 'required|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|numeric',
            'company_address' => 'required|max:255',
            // 'company_website' => 'required|max:255',
            'company_logo' => 'image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $customer = Customer::find($customer->id);
        $customer->name = $request->name;
        $customer->display_name = $request->display_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;

        $customer->company_name = $request->company_name;
        $customer->company_email = $request->company_email;
        $customer->company_website = $request->company_website;
        $customer->company_phone = $request->company_phone;
        $customer->company_address = $request->company_address;


        $customer->update();



        if ($request->hasFile('company_logo')) {
            $image = $request->file('company_logo');
            $ext = $image->getClientOriginalExtension();
            $file_name = 'company_logo_' . $customer->id . '.' . $ext;

            $des = public_path() . '/logo';
            $image->move($des, $file_name);
            $customer->company_logo = $file_name;
            $customer->update();
        }

        Alert::success('Success', 'Customer has been updated successfully.');
        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if (!$this->user->can('customer.delete')) {
            abort(403, 'Sorry! You are unathorized to delete any customer.');
        }

        $invoice_count = Invoice::where('customer_id', $customer->id)->count();
        if ($invoice_count > 0) {
            Alert::error('Opps', 'You can not delete this customer because ' . $invoice_count . ' invoices have been created for this customer');
            return redirect()->route('customer.index');
        }
        Customer::find($customer->id)->delete();
        Alert::success('Success', 'Customer has been deleted successfully.');
        return redirect()->route('customer.index');
    }
}
