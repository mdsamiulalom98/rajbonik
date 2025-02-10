@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
    $discount = Session::get('discount') ? Session::get('discount') : 0;

    $shipping_discount = App\Models\ShippingDiscount::where('status',1)->first();
@endphp


<div class="mini-cart-header">
    <p class="mini-close-button">
        <i class="fa-solid fa-bag-shopping "></i>
         {{ Cart::instance('shopping')->content()->count() }} ITEM
    </p>
    <button class="mini-close-button mini-close-cart">
        <span>Close</span>
    </button>
 
</div>
@php
    // Progress bar width calculation
    $progress_width = min(100, ($subtotal / $shipping_discount->max_amount) * 100);
@endphp

<div class="shipping__discount_area">
    <div class="shipping_dis_data">
        <p>Shop: Add ৳{{$shipping_discount->min_amount}} for {{$shipping_discount->discount}}% off, 
           ৳{{$shipping_discount->max_amount}} for 100% off!</p>
     
        <p>৳
            @if($subtotal >= $shipping_discount->min_amount && $subtotal < $shipping_discount->max_amount) 
                {{ $shipping - ($shipping * ($shipping_discount->discount / 100)) }}
            @elseif($subtotal >= $shipping_discount->max_amount) 
                0
            @else
                {{ $shipping }}
            @endif
             <i class="fa-solid fa-circle-info"></i>
        </p>

    </div>
    <div class="apply_ratio" style="width: {{ $progress_width }}%;"></div>
</div>

<div class="express_delivary">
    <div class="express_data">
        <p><i class="fa-solid fa-truck-fast"></i> Express Delivery</p>
    </div>
</div>
@if (Cart::instance('shopping')->count() > 0)
    <div class="mini-cart-body">
        @foreach (Cart::instance('shopping')->content() as $item)
            <div class="mini-cart-item {{ $loop->last ? 'border-none' : '' }}">
                <div class="cart-quantity-content">
                    <button class="mini-cart-change cart_increment" data-id="{{ $item->rowId }}" type="button">
                       <i class="fa-solid fa-angle-up"></i>
                    </button>

                    <span>{{ $item->qty }}</span>

                    <button class="mini-cart-change cart_decrement " @if($item->qty == 1) disabled @endif data-id="{{ $item->rowId }}" type="button">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                    
                </div>

                <div class="cart-item-image">
                    <img src="{{ asset($item->options->image ?? '') }}" alt="">
                </div>

                <div class="cart-item-content">
                    <div class="cart-product">
                        <a href="">{{ $item->name }}</a>
                    </div>
                    <div class="cart-item-subtotal">
                        <strong>{{ $item->price * $item->qty }} TK</strong>
                        @if ($item->options->size)
                            <small>Size: {{ $item->options->size }}</small>
                        @endif
                    </div>
                </div>
                <div class="delete__items">
                    <button class="mini-cart-change cart_remove" data-id="{{ $item->rowId }}" type="button">
                           <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
               
            </div>
        @endforeach
    </div>
@else
    <button class="mini-close-button floating-close-button"><i class="fa-solid fa-angle-right"></i></button>

    <div class="empty-cart">
        <div class="empty-img">
            <img src="{{ asset('public/frontEnd/images/empty-cart.webp') }}" alt="">
        </div>
    </div>
@endif
<div class="coupon__section">
    <button><i class="fa-solid fa-angle-down"></i> Have a special code?</button>
</div>
 <div class="main__coupon_apply">
        <form action="@if (Session::get('coupon_used')) {{ route('customer.coupon_remove') }} @else {{ route('customer.coupon') }} @endif"
            class="checkout-coupon-forms" method="POST">
            @csrf
            <div class="coupon">
                <input type="text" name="coupon_code" placeholder=" @if (Session::get('coupon_used')) {{ Session::get('coupon_used') }} @else Apply Coupon @endif" class="border-0 shadow-none form-control" />
            </div>
            <div class="apply__btn">
                 <input type="submit" value="@if (Session::get('coupon_used')) remove @else go @endif " class="border-0 shadow-none btn btn-theme"/>
            </div>
        </form>
    </div>

    <div class="mini-cart-checkout">
        <div class="place__order_subtotal">
            <div style="width:75%">
                @if(Auth::guard('customer')->user())
                <a href="{{route('customer.address')}}"> <div class="place__order"><p>Order Place</p></div></a>
                @else
                <a href="{{route('customer.login')}}"> <div class="place__order"><p>Login First</p></div></a>
                @endif
            </div>
            <div class="place__order_total" style="width:25%"><p>৳ {{$subtotal}}</p></div>
        </div>
    </div>
    <button class="mini-close-button floating-close-button"><i class="fa-solid fa-angle-right"></i></button>
