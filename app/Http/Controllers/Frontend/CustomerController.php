<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

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
use App\Models\CustomerAddress;
use App\Models\PaymentGateway;
use App\Models\ShippingCharge;
use App\Models\OrderDetails;
use App\Models\SmsGateway;
use App\Models\Shipping;
use App\Models\Customer;
use App\Models\District;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Order;
use App\Models\GeneralSetting;
use App\Models\CouponCode;
use Mail;

class CustomerController extends Controller
{

    function __construct()
    {
        $this->middleware('customer', ['except' => ['register', 'customer_coupon', 'coupon_remove', 'store', 'verify', 'resendotp', 'account_verify', 'login', 'signin', 'logout', 'checkout', 'forgot_password', 'forgot_verify', 'forgot_reset', 'forgot_store', 'forgot_resend', 'order_save', 'order_success', 'order_track', 'order_track_result', 'hostellogin']]);
    }
    public function customer_coupon(Request $request)
    {
        $findcoupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
        if ($findcoupon == NULL) {
            Toastr::error('Opps! your entered promo code is not valid');
            return back();
        } else {
            $currentdata = date('Y-m-d');
            $expiry_date = $findcoupon->expiry_date;
            if ($currentdata <= $expiry_date) {
                $totalcart = Cart::instance('shopping')->subtotal();
                $totalcart = str_replace('.00', '', $totalcart);
                $totalcart = str_replace(',', '', $totalcart);
                if ($totalcart >= $findcoupon->buy_amount) {
                    if ($totalcart >= $findcoupon->buy_amount) {
                        if ($findcoupon->offer_type == 1) {
                            $discountammount = (($totalcart * $findcoupon->amount) / 100);
                            Session::forget('coupon_amount');
                            Session::put('coupon_amount', $discountammount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        } else {
                            Session::put('coupon_amount', $findcoupon->amount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        }
                        Toastr::success('Success! your promo code accepted');
                        return back();
                    }
                } else {
                    Toastr::error('You need to buy a minimum of ' . $findcoupon->buy_amount . ' Taka to get the offer');
                    return back();
                }
            } else {
                Toastr::error('Opps! Sorry your promo code date expaire');
                return back();
            }
        }
    }
    public function coupon_remove(Request $request)
    {
        Session::forget('coupon_amount');
        Session::forget('coupon_used');
        Session::forget('discount');
        Toastr::success('Success', 'Your coupon remove successfully');
        return back();
    }

    public function review(Request $request)
    {
        $this->validate($request, [
            'ratting' => 'required',
            'review' => 'required',
        ]);

        // data save
        $review = new Review();
        $review->name = Auth::guard('customer')->user()->name ? Auth::guard('customer')->user()->name : 'N / A';
        $review->email = Auth::guard('customer')->user()->email ? Auth::guard('customer')->user()->email : 'N / A';
        $review->product_id = $request->product_id;
        $review->review = $request->review;
        $review->ratting = $request->ratting;
        $review->customer_id = Auth::guard('customer')->user()->id;
        $review->status = 'pending';
        $review->save();

        Toastr::success('Thanks, Your review send successfully', 'Success!');
        return redirect()->back();
    }

    public function login()
    {
        return view('frontEnd.layouts.customer.login');
    }

    public function hostellogin()
    {
        return view('frontEnd.layouts.hostel.login');
    }

    public function signin(Request $request)
    {
        // return $request->all();
        $auth_check = Customer::where('phone', $request->phone)->first();
        // return $auth_check;
        if ($auth_check) {
            if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                if ($request->review == 1) {
                    return redirect()->back();
                }
                if (Cart::instance('shopping')->count() > 0) {
                    return redirect()->route('customer.checkout');
                }

                if ($auth_check->customer_type == 'hostel' && $request->customer_type == 'hostel') {
                    Toastr::success('You are login successfully', 'success!');
                    return redirect()->route('hostel.dashboard');
                } elseif ($auth_check->customer_type == 'normal' && $request->customer_type == 'normal') {
                    Toastr::success('You are login successfully', 'success!');
                    return redirect()->intended('customer/account');
                }
            }
            Toastr::error('message', 'Opps! your phone or password wrong');
            return redirect()->back();
        } else {
            Toastr::error('message', 'Sorry! You have no accounttyhhh');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('frontEnd.layouts.customer.register');
    }

    public function address_create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $store = new CustomerAddress();
        $store->name = $request->name;
        $store->customer_id = Auth::guard('customer')->user()->id;
        $store->phone = $request->phone ?? '';
        $store->district = $request->district ?? '';
        $store->area_id = $request->area_id ?? '';
        $store->house_no = $request->house_no ?? '';
        $store->floor_no = $request->floor_no ?? '';
        $store->block = $request->block ?? '';
        $store->flat_no = $request->flat_no ?? '';
        $store->road_no = $request->road_no ?? '';
        $store->label = 'home';
        $store->active = 1;
        $store->save();

        $message = 'Account Create Successfully';
        return response()->json(['status' => 'success', 'message' => $message]);
    }
    
   public function address_update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:customer_addresses,id',
            'name' => 'required',
            'phone' => 'required',
        ]);

        // Find the existing address by ID
        $store = CustomerAddress::find($request->id);

        // Check if the logged-in user owns this address
        if ($store->customer_id !== Auth::guard('customer')->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 403);
        }

        // Update the address fields
        $store->name = $request->name;
        $store->phone = $request->phone ?? '';
        $store->district = $request->district ?? '';
        $store->area_id = $request->area_id ?? '';
        $store->house_no = $request->house_no ?? '';
        $store->floor_no = $request->floor_no ?? '';
        $store->block = $request->block ?? '';
        $store->flat_no = $request->flat_no ?? '';
        $store->road_no = $request->road_no ?? '';
        $store->label = 'home';
        $store->active = 1;
        $store->save();

    return response()->json(['status' => 'success', 'message' => 'Address updated successfully']);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'password' => 'required|min:6'
        ]);

        $last_id = Customer::orderBy('id', 'desc')->first();
        $last_id = $last_id ? $last_id->id + 1 : 1;
        $store = new Customer();
        $store->name = $request->name;
        $store->slug = strtolower(Str::slug($request->name . '-' . $last_id));
        $store->phone = $request->phone ?? '';
        $store->email = $request->email ?? '';
        $store->password = bcrypt($request->password);
        $store->customer_type = 'normal' ?? '';
        $store->verify = 1;
        $store->status = 'active';
        $store->save();

        Toastr::success('Success', 'Account Create Successfully');
        return redirect()->route('customer.login');
    }
    public function verify()
    {
        return view('frontEnd.layouts.customer.verify');
    }
    public function resendotp(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->verify = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where('status', 1)->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour account verify OTP is $customer_info->verify \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function account_verify(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ]);
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        if ($customer_info->verify != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->verify = 1;
        $customer_info->status = 'active';
        $customer_info->save();
        Auth::guard('customer')->loginUsingId($customer_info->id);
        return redirect()->route('customer.account');
    }
    public function forgot_password()
    {
        return view('frontEnd.layouts.customer.forgot_password');
    }

    public function forgot_verify(Request $request)
    {
        $customer_info = Customer::where('phone', $request->phone)->first();
        if (!$customer_info) {
            Toastr::error('Your phone number not found');
            return back();
        }
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'forget_pass' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        session::put('verify_phone', $request->phone);
        Toastr::success('Your account register successfully');
        return redirect()->route('customer.forgot.reset');
    }

    public function forgot_resend(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function forgot_reset()
    {
        if (!Session::get('verify_phone')) {
            Toastr::error('Something wrong please try again');
            return redirect()->route('customer.forgot.password');
        }
        return view('frontEnd.layouts.customer.forgot_reset');
    }
    public function forgot_store(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();

        if ($customer_info->forgot != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        if (Auth::guard('customer')->attempt(['phone' => $customer_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
            return redirect()->intended('customer/account');
        }
    }
    public function account()
    {
        return view('frontEnd.layouts.customer.account');
    }
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Toastr::success('You are logout successfully', 'success!');
        return redirect()->route('customer.login');
    }
    // public function checkout()
    // {
    //     $shippingcharge = ShippingCharge::where(['status' => 1, 'website' => 1])->get();
    //     $select_charge = ShippingCharge::where(['status' => 1, 'website' => 1])->first();
    //     $bkash_gateway = PaymentGateway::where(['status' => 1, 'type' => 'bkash'])->first();
    //     $shurjopay_gateway = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();

    //     if (Session::get('free_shipping') == 1) {
    //         Session::put('shipping', 0);
    //     } else {
    //         Session::put('shipping', $select_charge->amount);
    //     }
    //     $districts = District::distinct()->select('district')->orderBy('district', 'asc')->get();
    //     return view('frontEnd.layouts.customer.checkout', compact('shippingcharge', 'bkash_gateway', 'shurjopay_gateway', 'districts'));
    // }
    public function checkout()
    {
        if (Auth::guard('customer')->user()) {
           $customer_id = Auth::guard('customer')->user()->id;
           $customer_addresses = CustomerAddress::where(['customer_id' => $customer_id])->get();
           $selected_address = CustomerAddress::where(['customer_id' => $customer_id, 'active' => 1])->first();
        }else{
            $customer_addresses = [];
            $selected_address = [];
        }

        $bkash_gateway = PaymentGateway::where(['status' => 1, 'type' => 'bkash'])->first();
        $shurjopay_gateway = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();
        $shippingcharge = ShippingCharge::where(['status' => 1, 'website' => 1])->get();
        return view('frontEnd.layouts.customer.address', compact('customer_addresses', 'selected_address','shippingcharge','shurjopay_gateway','bkash_gateway'));
    }

    public function select_address(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        Session::put('session_address', 1);
        CustomerAddress::where('customer_id', $customer_id)->update(['active' => 0]);
        CustomerAddress::where('id', $request->id)->update(['active' => 1]);
        $customer_addresses = CustomerAddress::where(['customer_id' => $customer_id])->get();
        $selected_address = CustomerAddress::where(['customer_id' => $customer_id, 'active' => 1])->first();
        $updatedHtml = view('frontEnd.layouts.ajax.customeraddresses', ['customer_addresses' => $customer_addresses, 'selected_address' => $selected_address])->render();

        return response()->json([
            'success' => true,
            'updatedHtml' => $updatedHtml,
        ]);
    }
    public function change_address(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        Session::forget('session_address');
        $customer_addresses = CustomerAddress::where(['customer_id' => $customer_id])->get();
        $selected_address = CustomerAddress::where(['customer_id' => $customer_id, 'active' => 1])->first();
        $updatedHtml = view('frontEnd.layouts.ajax.customeraddresses', ['customer_addresses' => $customer_addresses, 'selected_address' => $selected_address])->render();

        return response()->json([
            'success' => true,
            'updatedHtml' => $updatedHtml,
        ]);
    }

    public function order_save(Request $request)
    {
        // return $request->all();
        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }
        

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('discount') + Session::get('coupon_amount');
        $shippingfee = Session::get('free_shipping') ? 0 : Session::get('shipping');
        $amount = ($subtotal + $shippingfee) - $discount;

        $shipping_area = ShippingCharge::where('id', $request->area)->first();
        if (Auth::guard('customer')->user()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else {
            $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
            } else {
                $password = rand(111111, 999999);
                $store = new Customer();
                $store->name = $request->name;
                $store->slug = $request->name;
                $store->phone = $request->phone;
                $store->password = bcrypt($password);
                $store->verify = 1;
                $store->status = 'active';
                $store->customer_type = 'normal';
                $store->save();
                $customer_id = $store->id;

                $store = new CustomerAddress();
                $store->name = $request->name;
                $store->customer_id = $customer_id;
                $store->phone = $request->phone ?? '';
                $store->district = $request->district ?? '';
                $store->area_id = $request->area_id ?? '';
                $store->house_no = $request->house_no ?? '';
                $store->floor_no = $request->floor_no ?? '';
                $store->block = $request->block ?? '';
                $store->flat_no = $request->flat_no ?? '';
                $store->road_no = $request->road_no ?? '';
                $store->label = 'home';
                $store->active = 1;
                $store->save();
            }
        }
        $customer_address = CustomerAddress::where(['customer_id' => $customer_id, 'active' => 1])->first();
        if(!$customer_address) {
            Toastr::error('Your address is empty', 'Failed!');
            return redirect()->back();
        }
        
        // order data save
        $order = new Order();
        $order->invoice_id = rand(11111, 99999);
        $order->amount = $amount;
        $order->customer_type = 'normal';
        $order->discount = $discount ? $discount : 0;
        $order->shipping_charge = $shippingfee ?? 0;
        $order->customer_id = $customer_id;
        $order->customer_ip = $request->ip();
        $order->order_type = Session::get('free_shipping') ? 'digital' : 'goods';
        $order->order_status = 1;
        $order->delivery_date = $request->order_date;
        $order->delivery_time = $request->order_time;
        $order->note = $request->note ?? '';
        $order->save();

        $complete_address = trim(implode(', ', array_filter([
            $customer_address->flat_no ? "Flat No: {$customer_address->flat_no}" : null,
            $customer_address->floor_no ? "Floor: {$customer_address->floor_no}" : null,
            $customer_address->house_no ? "House: {$customer_address->house_no}" : null,
            $customer_address->road_no ? "Road: {$customer_address->road_no}" : null,
            $customer_address->area_id,
            $customer_address->district
        ])));
        // shipping data save
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $customer_address->name;
        $shipping->phone = $customer_address->phone;
        $shipping->address = $complete_address ?? '';
        $shipping->area = $shipping_area ? $shipping_area->name : 'Free Shipping';
        $shipping->save();

        // payment data save
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('shopping')->content() as $cart) {
            // return $cart;
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->sale_price = $cart->price;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_color = $cart->options->product_color;
            $order_details->product_size = $cart->options->product_size;
            $order_details->product_size = $cart->options->product_size;
            $order_details->product_type = $cart->options->type;
            $order_details->qty = $cart->qty;
            $order_details->save();
        }

        Cart::instance('shopping')->destroy();
        Session::forget('free_shipping');
        Session::put('purchase_event', 'true');

        Toastr::success('Thanks, Your order place successfully', 'Success!');
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'order' => '1'])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $request->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $request->name!\r\nYour order ($order->invoice_id) has been successfully placed. Track your order https://shoppingghor.com/customer/order-track and Total Bill $order->amount\r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        if ($request->payment_method == 'bkash') {
            return redirect('/bkash/checkout-url/create?order_id=' . $order->id);
        } elseif ($request->payment_method == 'shurjopay') {
            $info = array(
                'currency' => "BDT",
                'amount' => $order->amount,
                'order_id' => uniqid(),
                'discsount_amount' => 0,
                'disc_percent' => 0,
                'client_ip' => $request->ip(),
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'email' => "customer@gmail.com",
                'customer_address' => $request->address,
                'customer_city' => $request->area,
                'customer_state' => $request->area,
                'customer_postcode' => "1212",
                'customer_country' => "BD",
                'value1' => $order->id
            );
            $shurjopay_service = new ShurjopayController();
            return $shurjopay_service->checkout($info);
        } else {
            return redirect('customer/order-success/' . $order->id);
        }
    }

    public function orders()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->with('status')->latest()->get();
        return view('frontEnd.layouts.customer.orders', compact('orders'));
    }
    public function order_success($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('frontEnd.layouts.customer.order_success', compact('order'));
    }
    public function invoice(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();

        $orders = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails')->first();
        // return $orders;
        return view('frontEnd.layouts.customer.invoice', compact('order', 'orders'));
    }
    public function pdfreader(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();

        $orders = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails')->first();
        // return $orders;
        return view('frontEnd.layouts.customer.pdfreader', compact('order', 'orders'));
    }


    public function order_note(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->firstOrFail();
        return view('frontEnd.layouts.customer.order_note', compact('order'));
    }
    public function profile_edit(Request $request)
    {
        $profile_edit = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::distinct()->select('district')->get();
        $areas = District::where(['district' => $profile_edit->district])->select('area_name', 'id')->get();
        return view('frontEnd.layouts.customer.profile_edit', compact('profile_edit', 'districts', 'areas'));
    }
    public function profile_update(Request $request)
    {
        $update_data = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();

        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(Str::slug($name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
        } else {
            $imageUrl = $update_data->image;
        }

        $update_data->name = $request->name;
        $update_data->phone = $request->phone;
        $update_data->email = $request->email;
        $update_data->address = $request->address;
        $update_data->district = $request->district;
        $update_data->area = $request->area;
        $update_data->image = $imageUrl;
        $update_data->save();

        Toastr::success('Your profile update successfully', 'Success!');
        return redirect()->route('customer.account');
    }

    public function order_track()
    {
        return view('frontEnd.layouts.customer.order_track');
    }

    public function order_track_result(Request $request)
    {

        $phone = $request->phone;
        $invoice_id = $request->invoice_id;

        if ($phone != null && $invoice_id == null) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['shippings.phone' => $request->phone])
                ->get();
        } else if ($invoice_id && $phone) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['orders.invoice_id' => $request->invoice_id, 'shippings.phone' => $request->phone])
                ->get();
        }

        if ($order->count() == 0) {

            Toastr::error('message', 'Something Went Wrong !');
            return redirect()->back();
        }

        //   return $order->count();

        return view('frontEnd.layouts.customer.tracking_result', compact('order'));
    }


    public function change_pass()
    {
        return view('frontEnd.layouts.customer.change_password');
    }

    public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $hashPass = $customer->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $customer->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('customer.account');
        } else {
            Toastr::error('Failed', 'Old password not match!');
            return redirect()->back();
        }
    }
}
