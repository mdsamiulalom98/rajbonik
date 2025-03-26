<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Timeslot;

class TimeslotController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:timeslot-list|timeslot-create|timeslot-edit|timeslot-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:timeslot-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:timeslot-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:timeslot-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $data = Timeslot::orderBy('id', 'DESC')->get();
        return view('backEnd.timeslot.index', compact('data'));
    }

    public function create()
    {
        return view('backEnd.timeslot.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required',
        ]);
        $input = $request->all();
        Timeslot::create($input);
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('timeslots.index');
    }

    public function edit($id)
    {
        $edit_data = Timeslot::find($id);
        return view('backEnd.timeslot.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        $update_data = Timeslot::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status ? 1 : 0;
        $update_data->update($input);

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('timeslots.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Timeslot::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }

    public function active(Request $request)
    {
        $active = Timeslot::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $delete_data = Timeslot::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}
