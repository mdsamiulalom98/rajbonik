<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingDiscount;
use Toastr;
class ShippingDiscountController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:shipping-list|shipping-create|shipping-edit|shipping-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:shipping-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:shipping-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:shipping-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $show_data = ShippingDiscount::orderBy('id', 'ASC')->get();
        return view('backEnd.shippingdiscount.index', compact('show_data'));
    }
    public function create()
    {
        return view('backEnd.shippingdiscount.create');
    }
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'min_amount' => 'required',
            'max_amount' => 'required',
            'discount' => 'required',
            'status' => 'required',
        ]);        

        $input = $request->all();
        $input['status'] = $request->status?1:0;
        // dd($input);
        ShippingDiscount::create($input);
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('shippingdiscount.index');
    }

    public function edit($id)
    {
        $edit_data = ShippingDiscount::find($id);
        return view('backEnd.shippingdiscount.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'min_amount' => 'required',
            'max_amount' => 'required',
            'discount' => 'required',
            'status' => 'required',
        ]);
        $update_data = ShippingDiscount::find($request->id);

        $input = $request->all();       
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('shippingdiscount.index');
    }

    public function inactive(Request $request)
    {
        $inactive = ShippingDiscount::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }

    public function active(Request $request)
    {
        $active = ShippingDiscount::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $delete_data = ShippingDiscount::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}
