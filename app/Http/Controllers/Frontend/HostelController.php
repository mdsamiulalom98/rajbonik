<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\ShippingCharge;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Review;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;
use App\Models\GeneralSetting;
use App\Models\CouponCode;
use App\Models\Hostel;
use App\Models\Product;
use App\Models\ProductVariable;
use Mail;
class HostelController extends Controller
{
    public function orders_updates_page(Request $request)
    {
       // return $request->all();
        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }
        $order__id = Session::get('order_id');

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $customer_id = Auth::guard('customer')->user()->id;
        // order data save
        $order = Order::where('id', $order__id)->first();
        // return  $order;
        $order->amount = $subtotal;
        $order->discount = 0;
        $order->shipping_charge = 0;
        $order->customer_id = $customer_id;
        $order->order_status = 1;
        $order->note = 'hostel note';
        $order->save();


        // payment data save
        $payment = Payment::where('order_id', $order__id)->first();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        // return $payment;
        $payment->save();

        // order details data save
        foreach ($order->orderdetails as $orderdetail) {
            $item = Cart::instance('shopping')->content()->where('id', $orderdetail->product_id)->first();
            if (!$item) {
                $orderdetail->delete();
            }
        }
        // return Cart::instance('shopping')->content();
        foreach (Cart::instance('shopping')->content() as $cart) {
            // return $cart->options->details_id;
            $exits = OrderDetails::where('id', $cart->options->details_id)->first();
            if ($exits) {
                $order_details = OrderDetails::find($exits->id);
                $order_details->product_discount = $cart->options->product_discount;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            } else {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->product_discount = $cart->options->product_discount;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->product_type = $cart->options->type;
                $order_details->save();
            }
        }
        // return "stop";

        Cart::instance('shopping')->destroy();
        Session::forget('order_id');
        Toastr::success('Thanks, Your order update successfully', 'Success!');
        return redirect()->back();
    }

    public function orders_updates(){
        $cartinfo = Cart::instance('shopping')->content();

        if (!$cartinfo) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        return view('frontEnd.layouts.hostel.ordersEdit', compact('cartinfo'));
    }

    public function order_clear(){
           $cartinfo = Cart::instance('shopping')->destroy();
           return response()->json(compact('cartinfo'));
        }
    public function orders_edit_hostel(Request $request){
        Session::put('order_id', $request->id);

        $data = Cart::instance('shopping')->destroy();
        $data = OrderDetails::where('order_id',$request->id)->with('image')->get();
        foreach($data as $key=>$value){
            $hostel_cart = Cart::instance('shopping')->add([
            'id' => $value->product_id,
            'name' => $value->product_name,
            'qty' => $value->qty,
            'price' => $value->sale_price,
            'weight' => 1,
            'options' => [
                'slug' => $value->product_name,
                'image' => $value->image->image,
                'old_price' => $value->sale_price,
                'purchase_price' => $value->purchase_price,
                'product_size' => $value->product_size,
                'product_color' => $value->product_color,
                'type' => $value->product_type,
                'comment' => $value->comment,
                'details_id' => $value->id
                ],
            ]);
        }
        $products['products'] = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
            ->with('image', 'variables')->get();
        $products = view('frontEnd.layouts.hostel.edit', $products)->render();
        if ($products != '') {
            echo $products;
        }

        // return response()->json(compact('data'));
    }

    public function order_save(Request $request)
    {
        // return $request->all();
         $this->validate($request, [
            'deliveryTime' => 'required',
        ]);

        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $amount = $subtotal;

        $customer_id = Auth::guard('customer')->user()->id;

        // order data save
        $order                   = new Order();
        $order->invoice_id       = rand(11111, 99999);
        $order->amount           = $amount;
        $order->discount         = 0;
        $order->shipping_charge  = 0;
        $order->customer_id      = $customer_id;
        $order->customer_type    = 'hostel';
        $order->customer_ip      = $request->ip();
        $order->order_type       = Session::get('free_shipping') ? 'digital' : 'goods';
        $order->order_status     = 1;
        $order->note             = 'Hostel order';
        $order->save();

        // shipping data save
        $shipping              =   new Shipping();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   Auth::guard('customer')->user()->name;
        $shipping->phone       =   Auth::guard('customer')->user()->phone;
        $shipping->address     =   Auth::guard('customer')->user()->address??'';
        $shipping->area        =   'Free Shipping';
        $shipping->save();

        // payment data save
        $payment                 = new Payment();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = 'Hostel Order';
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('shopping')->content() as $cart) {
            // return $cart;
            $order_details                  =   new OrderDetails();
            $order_details->order_id        =   $order->id;
            $order_details->product_id      =   $cart->id;
            $order_details->product_name    =   $cart->name;
            $order_details->sale_price      =   $cart->price;
            $order_details->purchase_price  =   $cart->options->purchase_price;
            $order_details->product_color   =   $cart->options->product_color;
            $order_details->product_size    =   $cart->options->product_size;
            $order_details->product_size    =   $cart->options->product_size;
            $order_details->product_type    =   $cart->options->type;
            $order_details->comment         =   $cart->options->comment;
            $order_details->qty             =   $cart->qty;
            $order_details->save();
        }

        Cart::instance('shopping')->destroy();
        Session::forget('free_shipping');

        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect()->back();
        
    }

     public function cart_content()
    {
        $cartinfo = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.hostel.cart_list', compact('cartinfo'));
    }
     public function cart_edit(Request $request)
    {
        $cartContent = Cart::instance('shopping')->content();
        $cartinfo = $cartContent->firstWhere('id', $request->id);
        if (!$cartinfo) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
            ->with('image', 'variables')
            ->get();
        $response = view('frontEnd.layouts.hostel.cart_edit',compact('cartinfo','products'))->render();
        return response()->json($response);
    }

    public function cart_content_edit(Request $request)
    {
        $cartContent = Cart::instance('shopping')->content();
        $cartinfo = $cartContent->firstWhere('id', $request->id);
        if (!$cartinfo) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
            ->with('image', 'variables')
            ->get();

        return view('frontEnd.layouts.hostel.cart_edit', compact('cartinfo', 'products'));
    }


     public function cart_add(Request $request)
    {
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock','wholesale')->where(['id' => $request->id])->first();

        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->wholesale_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->wholesale;
            $stock = $product->stock;
        }

        $qty = $request->qty;

        $cartItem = Cart::instance('shopping')->content()->first(function ($item) use ($request, $product) {
            return $item->id == $request->id || $item->id == $product->id;
        });

        if ($cartItem) {
            $cart_qty = $cartItem->qty + $qty;
        } else {
            $cart_qty = $qty;
        }
        if ($stock < $cart_qty) {
            Toastr::error('Product stock limit over', 'Failed!');
            return response()->json(['status' => 'limitover', 'message' => 'Your stock limit is over']);
        }
        $hostel_cart = Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $new_price,
            'weight' => 1,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->size,
                'product_color' => $request->color,
                'type' => $product->type,
                'comment' => $request->comment,
            ],
        ]);

        // return Cart::instance('shopping')->content();
        return response()->json(compact('hostel_cart'));
    }

    public function cart_update(Request $request)
    {
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $request->id])->first();

        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->wholesale_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }

        $qty = $request->qty;
          $cartItem = Cart::instance('shopping')->content()->first(function ($item) use ($request, $product) {
            return $item->id == $request->cart_id || $item->id == $product->id;
        });

        if ($cartItem) {
            $cart_qty = $cartItem->qty + $qty;
        } else {
            $cart_qty = $qty;
        }

        if ($stock < $cart_qty) {
            Toastr::error('Product stock limit over', 'Failed!');
            return response()->json(['status' => 'limitover', 'message' => 'Your stock limit is over']);
        }
        $hostel_cart_update = Cart::instance('shopping')->update($request->cart_id,[
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $new_price,
            'weight' => 1,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->size,
                'product_color' => $request->color,
                'type' => $product->type,
                'comment' => $request->comment,
            ],
        ]);

        // return Cart::instance('shopping')->content();
        return response()->json(compact('hostel_cart_update'));
    }
     public function cart_creatForm(Request $request)
    {
        
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
            ->with('image', 'variables')
            ->get();
        return view('frontEnd.layouts.hostel.createForm',compact('products'));
    }

   public function cart_remove(Request $request)
    {
        $cartContent = Cart::instance('shopping')->content();
        // return $cartContent;
        $cartItem = $cartContent->firstWhere('rowId', $request->id);
        if (!$cartItem) {
            return response()->json(['error' => 'Item not found in the cart'], 404);
        }

        Cart::instance('shopping')->remove($cartItem->rowId);
        return response()->json(['success' => 'Item removed successfully']);
    }
   public function destroy(Request $request)
    {
        $order = Order::where('id', $request->id)->delete();
        $order_details = OrderDetails::where('order_id', $request->id)->delete();
        $shipping = Shipping::where('order_id', $request->id)->delete();
        $payment = Payment::where('order_id', $request->id)->delete();
        Toastr::success('Success', 'Order delete success successfully');
        return redirect()->back();
    }

     public function hostelProduct(Request $request)
    {
        $products = Product::where(['status' => 1, 'wholesale' => 1])
        ->orderBy('id', 'DESC')
        ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
        ->withCount('variable');

        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.hostel.product', compact('products'));
    }

    public function hostelOrder(Request $request)
    {
        $show_data = Order::where('customer_id',Auth::guard('customer')->user()->id)->latest()->with('shipping', 'status');
            if ($request->keyword) {
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhereHas('shipping', function ($subQuery) use ($request) {
                            $subQuery->where('phone', $request->keyword);
                        });
                });
            }
        $show_data = $show_data->paginate(50);

        return view('frontEnd.layouts.hostel.orders',compact('show_data'));
    }
    
     public function invoiceData(Request $request)
    {
        $data['data']= Order::where(['id' => $request->id])->with('orderdetails', 'payment', 'shipping', 'customer')->get();
        // return  $data;
        $data = view('frontEnd.layouts.hostel.invoice', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }

 public function orderCreate(Request $request){
      $products['products'] = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
            ->with('image', 'variables')->get();
        // return  $data;
    Cart::instance('shopping')->destroy();
        $products = view('frontEnd.layouts.hostel.create', $products)->render();
        if ($products != '') {
            echo $products;
        }
    }
   public function orderUpdates(Request $request)
    {
        $products['products'] = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
            ->with('image', 'variables')->get();
        // return  $data;
        $products = view('frontEnd.layouts.hostel.create', $products)->render();
        if ($products != '') {
            echo $products;
        }
    }


     private function setting()
    {
        return GeneralSetting::select('name')->first();
    }
    private function districts()
    {
        return District::distinct()->select('district')->orderBy('district', 'asc')->get();
    }

    public function register()
    {
        return view('frontEnd.layouts.hostel.register');
    }

    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:hostels',
            'email' => 'required|unique:hostels',
            'password' => 'required',
            'agree' => 'required',
            'confirmed' => 'required_with::password|same:password',
        ]);
        $verify                 = rand(1111, 9999);
        $store_data             = new Hostel();
        $store_data->hostel_id   = $this->generateHostelId();
        $store_data->name       = $request->name;
        $store_data->email      = $request->email;
        $store_data->phone      = $request->phone;
        $store_data->agree      = $request->agree;
        $store_data->status     = 0;
        $store_data->verify     = $verify;
        $store_data->password = bcrypt($request->password);
        $store_data->save();

        Session::put('verify_phone', $store_data->phone);
        // verify by sms
        $apiKey = 'mPHNEo5pvdzYOfj7cyLJczoNyrSMZB4g0DGuAzBExOo=';
        $clientId = '37574055-f638-4736-87af-c995ad7200ff';
        $senderId = '8809617611899';
        $message = "Dear $store_data->name, Your account verify OTP is $verify. Thanks for using " . $this->setting()->name;
        $mobileNumbers = "88$store_data->phone";
        $isUnicode = '0';
        $isFlash = '0';
        $message = urlencode($message);
        $mobileNumbers = urlencode($mobileNumbers);
        $url = "http://sms.insafhost.com/api/v2/SendSMS?ApiKey=$apiKey&ClientId=$clientId&SenderId=$senderId&Message=$message&MobileNumbers=$mobileNumbers&Is_Unicode=$isUnicode&Is_Flash=$isFlash";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        Toastr::success('Please check your phone for account verify token');
        return redirect()->route('hostel.verify');
    }
    public function verify()
    {
        return view('frontEnd.layouts.hostel.verify');
    }
    public function account_verify(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ]);
        $auth_check = Hostel::select('id', 'phone', 'verify')->where('phone', Session::get('verify_phone'))->first();
        if ($auth_check->verify == $request->otp) {
            $auth_check->verify = 1;
            $auth_check->status = 1;
            $auth_check->save();

            Auth::guard('customer')->loginUsingId($auth_check->id);
            Toastr::success('Your account verified successfully', 'Congratulations!');
            Session::forget('verify_phone');
            return redirect()->route('hostel.dashboard');
        } else {
            Toastr::error('Your token does not match', 'Failed!');
            return redirect()->back();
        }
    }

    public function login()
    {
        return view('frontEnd.layouts.hostel.login');
    }
    // login form
    public function signin(Request $request)
    {
        $this->validate($request, [
            'email_phone' => 'required',
            'password' => 'required',
        ]);
        $auth_check = Hostel::select('phone','email', 'name', 'password', 'verify', 'status')->where('phone', $request->email_phone)->orWhere('email', $request->email_phone)->first();
        return $auth_check;

        $email_phone = $request->email_phone;
        $credentials = [];
        if (filter_var($email_phone, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $email_phone;
        } else {
            $credentials['phone'] = $email_phone;
        }
        $credentials['password'] = $request->password;

        if ($auth_check) {
            if ($auth_check->verify != 1) {
                $verify = rand(1111, 9999);
                $auth_check->verify = $verify;
                $auth_check->save();

                Session::put('verify_phone', $auth_check->phone);
                // verify by sms
                $apiKey = 'mPHNEo5pvdzYOfj7cyLJczoNyrSMZB4g0DGuAzBExOo=';
                $clientId = '37574055-f638-4736-87af-c995ad7200ff';
                $senderId = '8809617611899';
                $message = "Dear $auth_check->name, Your account verify OTP is $verify. Thanks for using " . $this->setting()->name;
                $mobileNumbers = "88$auth_check->phone";
                $isUnicode = '0';
                $isFlash = '0';
                $message = urlencode($message);
                $mobileNumbers = urlencode($mobileNumbers);
                $url = "http://sms.insafhost.com/api/v2/SendSMS?ApiKey=$apiKey&ClientId=$clientId&SenderId=$senderId&Message=$message&MobileNumbers=$mobileNumbers&Is_Unicode=$isUnicode&Is_Flash=$isFlash";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                Toastr::error('Your account not verified, check your phone for OTP', 'Failed');
                return redirect()->route('hostel.verify');
            } elseif ($auth_check->status == 0) {
                Toastr::error('Your account not active now', 'Failed');
                return redirect()->back();
            } else {
                if (Auth::guard('customer')->attempt($credentials)) {
                    Toastr::success('You are login successfully', 'Success');
                    return redirect()->route('hostel.dashboard');
                } else {
                    Toastr::error('Your password does not match', 'Failed');
                    return redirect()->back();
                }
            }
        } else {
            Toastr::error('message', 'Sorry! You have no account');
            return redirect()->back();
        }
    }
    public function dashboard(Request $request){        
        if ($request->start_date && $request->end_date) {
            $total_order = Order::where('customer_id', Auth::guard('customer')->user()->id)->whereBetween('created_at', [$request->start_date,$request->end_date])->count();
            $total_delivery = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','7')->whereBetween('created_at', [$request->start_date,$request->end_date])->count();
            $total_process = Order::where('customer_id', Auth::guard('customer')->user()->id)->whereNotIn('order_status',['1','8','9','10'])->whereBetween('created_at', [$request->start_date,$request->end_date])->count();
            $total_return = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','9')->whereBetween('created_at', [$request->start_date,$request->end_date])->count();
            $total_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->whereBetween('created_at', [$request->start_date,$request->end_date])->sum('cod');
            $delivery_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','7')->whereBetween('created_at', [$request->start_date,$request->end_date])->sum('cod');  
            $process_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->whereNotIn('order_status',['1','7','8','9','10'])->whereBetween('created_at', [$request->start_date,$request->end_date])->sum('cod');
            $return_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','9')->whereBetween('created_at', [$request->start_date,$request->end_date])->sum('cod');
        }else{
            $total_order = Order::where('customer_id', Auth::guard('customer')->user()->id)->count();
            $total_delivery = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','7')->count();
            $total_process = Order::where('customer_id', Auth::guard('customer')->user()->id)->whereNotIn('order_status',['1','7','8','9','10'])->count();
            $total_return = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','9')->count();
            $total_complete = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','7')->count();
            $total_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->sum('cod');
            $delivery_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','7')->sum('cod');  
            $process_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->whereNotIn('order_status',['1','7','8','9','10'])->sum('cod');
            $return_amount = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_status','9')->sum('cod');
        }
        return view('frontEnd.layouts.hostel.dashboard',compact('total_order','total_delivery','total_process','total_return','total_complete','total_amount','delivery_amount','process_amount','return_amount'));
    }
    
    public function hprofile()
    {
        $profile = Customer::find(Auth::guard('customer')->user()->id);
        // return $profile;
        $districts = $this->districts();
        return view('frontEnd.layouts.hostel.hprofile', compact('profile','districts'));
    }
    public function settings(){
        $profile = Customer::find(Auth::guard('customer')->user()->id);
        $districts = $this->districts();
        return view('frontEnd.layouts.hostel.settings', compact('profile','districts'));
    }
    public function basic_update(Request $request)
    {
        $update_data = Customer::find(Auth::guard('customer')->user()->id);
        $update_image = $request->file('image');
        if ($update_image) {
            $file = $request->file('image');
            $name = time() . '-' . $file->getClientOriginalName();
            $uploadPath = 'public/uploads/hostel/';
            $file->move($uploadPath, $name);
            $fileUrl = $uploadPath . $name;
        } else {
            $fileUrl = $update_data->image;
        }
        $update_data->address        = $request->address??$update_data->address;
        $update_data->image          = $fileUrl;
        $update_data->save();
        Toastr::success('Basic info update successfully', 'Success');
        return redirect()->route('hostel.settings');
    }
   
    public function change_pass()
    {
        return view('frontEnd.layouts.hostel.change_password');
    }

    public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);
        $auth_user = Customer::find(Auth::guard('customer')->user()->id);
        $hashPass = $auth_user->password;
        if (Hash::check($request->old_password, $hashPass)) {
            $auth_user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            Toastr::success('Password changed successfully!', 'Success');
            return redirect()->route('hostel.dashboard');
        } else {
            Toastr::error('Old password not match!', 'Failed');
            return redirect()->back();
        }
    }

    public function messages()
    {
        $messages = MerchantMessage::with('district', 'thana')->where('rider_id', Auth::guard('rider')->user()->id)->get();
        return view('frontEnd.layouts.hostel.message', compact('messages'));
    }
    public function forgot_password()
    {
        return view('frontEnd.layouts.hostel.forgot_password');
    }

    public function forgot_verify(Request $request) {
        $auth_info = Customer::where('phone', $request->phone)->first();
        if (!$auth_info) {
            Toastr::error('Your phone number not found', 'Failed');
            return back();
        }
        $auth_info->forgot = rand(1111, 9999);
        $auth_info->save();

        $apiKey = 'mPHNEo5pvdzYOfj7cyLJczoNyrSMZB4g0DGuAzBExOo=';
        $clientId = '37574055-f638-4736-87af-c995ad7200ff';
        $senderId = '8809617611899';
        $message = "Dear $auth_info->name, Your account forgot OTP is $auth_info->forgot. Thanks for using " . $this->setting()->name;
        $mobileNumbers = "88$auth_info->phone";
        $isUnicode = '0';
        $isFlash = '0';
        $message = urlencode($message);
        $mobileNumbers = urlencode($mobileNumbers);
        $url = "http://sms.insafhost.com/api/v2/SendSMS?ApiKey=$apiKey&ClientId=$clientId&SenderId=$senderId&Message=$message&MobileNumbers=$mobileNumbers&Is_Unicode=$isUnicode&Is_Flash=$isFlash";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        session::put('verify_phone', $request->phone);
        Toastr::success('Verify OTP send your phone number', 'Success');
        return redirect()->route('hostel.forgot.reset');
    }

    public function forgot_reset(){
        if (!Session::get('verify_phone')) {
            Toastr::error('Something wrong please try again', 'Failed');
            return redirect()->route('hostel.forgot.password');
        }
        return view('frontEnd.layouts.hostel.forgot_reset');
    }
    public function forgot_store(Request $request){

        $auth_info = Hostel::where('phone', session::get('verify_phone'))->first();
        if ($auth_info->forgot != $request->otp) {
            Toastr::error('Failed', 'Your OTP not match');
            return redirect()->back();
        }
        $auth_info->forgot = 1;
        $auth_info->password = bcrypt($request->password);
        $auth_info->save();
        if (Auth::guard('customer')->attempt(['phone' => $auth_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
            return redirect()->route('hostel.dashboard');
        }
    }

    public function logout()
    {
        Session::flush();
        Toastr::success('You are logout successfully', 'Logout!');
        return redirect('/');
    }
    public function generateHostelId(){
        $lastMember = Hostel::orderBy('id', 'desc')->first();
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
