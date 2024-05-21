<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuotationType;
use App\Models\Employee;
use RealRashid\SweetAlert\Facades\Alert;
class QuotationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotationTypes=QuotationType::all();
        return view('quotation-type.quotation-type-index',compact('quotationTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees=Employee::all();
        return view('quotation-type.quotation-type-create',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $rules=array(
            'quotation_type_name'=>'required|string|max:255|unique:quotation_types',
            'quotation_type_short_name'=>'required|string|max:255',
            'employee_id'=>'required',
            'subject'=>'required|string|max:500',
            'description'=>'required|string|max:500',
        );

        $this->validate($request,$rules);

        $invoicType=new QuotationType;
        $invoicType->quotation_type_name=$request->quotation_type_name;
        $invoicType->quotation_type_short_name=$request->quotation_type_short_name;
        $invoicType->subject=$request->subject;
        $invoicType->description=$request->description;
        $invoicType->employee_id=$request->employee_id;
        $invoicType->save();
        Alert::success('Success', 'Data has been saved successfully.');
        return redirect()->route('quotationType.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employees=Employee::all();
        $invoicType=QuotationType::find($id);
        return view('quotation-type.quotation-type-edit',compact('invoicType','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=array(
            'quotation_type_name'=>'required|string|max:255|unique:quotation_types,quotation_type_name,'.$id.'',
            'quotation_type_short_name'=>'required|string|max:255',
            'employee_id'=>'required',
            'subject'=>'required|string|max:500',
            'description'=>'required|string|max:500',
        );
        $this->validate($request,$rules);

        $invoicType=QuotationType::find($id);
        $invoicType->quotation_type_name=$request->quotation_type_name;
        $invoicType->quotation_type_short_name=$request->quotation_type_short_name;
        $invoicType->description=$request->description;
        $invoicType->subject=$request->subject;
        $invoicType->employee_id=$request->employee_id;
        $invoicType->update();
        Alert::success('Success', 'Data has been updated successfully.');
        return redirect()->route('quotationType.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        QuotationType::destroy($id);
        Alert::success('Success', 'Data has been deleted successfully.');
        return redirect()->route('quotationType.index');
    }
}
