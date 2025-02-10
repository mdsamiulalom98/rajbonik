<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\CustomerProfit;
use App\Models\Customer;
use App\Models\IpBlock;
use App\Models\Hostel;
use Toastr;
use Image;
use File;
use Auth;
use Hash;

class HostelManageController extends Controller
{
    public function create(){
        return view('backEnd.hostel.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:hostels',
            'email' => 'required|unique:hostels',
            'password' => 'required'
        ]);
        // $verify                     = rand(1111, 9999);
        // $store_data                 = new Hostel();
        // $store_data->hostel_id      = $this->generateHostelId();
        // $store_data->name           = $request->name;
        // $store_data->email          = $request->email;
        // $store_data->phone          = $request->phone;
        // $store_data->address        = $request->address;
        // $store_data->verify         = 1;
        // $store_data->agree          = 1;
        // $store_data->status         = 1;
        // $store_data->password       = bcrypt($request->password);
        // $store_data->save();

        $last_id            = Customer::orderBy('id', 'desc')->first();
        $last_id            = $last_id ? $last_id->id + 1 : 1;
        $store              = new Customer();
        $store->name        = $request->name;
        $store->slug        = strtolower(Str::slug($request->name . '-' . $last_id));
        $store->email       = $request->email ?? '';
        $store->phone       = $request->phone ?? '';
        $store->address     = $request->address ?? '';
        $store->customer_type= $request->customer_type ?? '';
        $store->verify      = 1;
        $store->status      = 'active';
        $store->password    = bcrypt($request->password);
        // return $store;
        $store->save();


        Toastr::success('Success','Rider added successfully');
        return redirect()->route('hostel.index');
    }
    public function index(Request $request){
        if($request->keyword){
            $show_data = Customer::orWhere('customer_type', 'hostel')->orWhere('phone',$request->keyword)->orWhere('name',$request->keyword)->latest()->paginate(50);
        }else{
            $show_data = Customer::where('customer_type', 'hostel')->latest()->paginate(50);
        }
        return view('backEnd.hostel.index',compact('show_data'));
    }
    public function payment($slug,Request $request){
        $payments = Payment::where(['status'=>$slug,'user_type'=>'rider']);
        if($request->start_date && $request->end_date){
            $payments = $payments->whereBetween('created_at', [$request->start_date,$request->end_date]);
        }
        if($request->merchant_id){
            $payments = $payments->where('rider_id',$request->rider_id);
        }
        $payments = $payments->with('rider')->paginate(100);
        return view('backEnd.hostel.payment',compact('payments'));
    }
    
    public function invoice($id){
        $payment = Payment::where(['id'=>$id,'user_type'=>'rider'])->first();
        return view('backEnd.hostel.invoice',compact('payment'));
    }
   
    public function edit($id){
        $edit_data = Customer::find($id);
        return view('backEnd.hostel.edit',compact('edit_data'));
    }
    
    public function update(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $input = $request->except('hidden_id');
        $update_data = Customer::find($request->hidden_id);
       
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
        $image = $request->file('image');
        if($image){
            // image with intervention 
            $name =  time().'-'.$image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath.$name; 
            $img=Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 100;
            $height = 100;
            $img->height() > $img->width() ? $width=null : $height=null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
            $input['image'] = $imageUrl;
            File::delete($update_data->image);
        }else{
            $input['image'] = $update_data->image;
        }
        $input['status'] = $request->status? 'active': 'inactive';
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('hostel.index');
    }
 
    public function inactive(Request $request){
        $inactive = Customer::find($request->hidden_id);
        $inactive->status = 'inactive';
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request){
        $active = Customer::find($request->hidden_id);
        $active->status = 'active';
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function profile(Request $request){
        $profile = Customer::find($request->id);
        return view('backEnd.hostel.profile',compact('profile'));
    }
   
    public function adminlog(Request $request){
        $customer = Customer::find($request->hidden_id);
        Auth::guard('customer')->loginUsingId($customer->id);
        return redirect()->route('hostel.dashboard');
    }
    public function generateHostelId(){
        $lastMember = Customer::orderBy('id', 'desc')->first();
        if ($lastMember) {
            $lastId = (int) substr($lastMember->id, -5);
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }
        return '10000' . str_pad($newId, 1, '0', STR_PAD_LEFT);
    }
    function invoiceIdGenerate(){
        do {
            $uniqueId = 'INV-'.date('dmy').Str::upper(Str::random(6));
            $exists = Payment::where('invoice_id', $uniqueId)->exists();
        }while ($exists);

        return $uniqueId;
    }
}
