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
    <div class="">
        <p class="mini-close-button mini-close-cart">
            <a href=""><i class="fa-solid fa-arrow-left"></i></a>
        </p>
    </div>
    <div class="cart__top__count"><p>Cart( {{Cart::instance('shopping')->count()}} )</p></div>

</div>
@php
    // Progress bar width calculation
    $progress_width = min(100, ($subtotal / $shipping_discount->max_amount) * 100);
@endphp

@if (Cart::instance('shopping')->count() > 0)
    <div class="mini-cart-body">
        @foreach (Cart::instance('shopping')->content() as $item)
            <div class="mini-cart-item">
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
<div class="cart-summary">
     <h5>Cart Summary</h5>
     <table class="table">
      <tbody>
       <tr>
        <td>Items</td>
        <td>{{Cart::instance('shopping')->count()}} (qty)</td>
       </tr>
       <tr>
        <td>Sub Total</td>
        <td>৳{{$subtotal}}</td>
       </tr>
      </tbody>
     </table>
    </div>
    <div class="mini-cart-checkouts">
        <div class="view___cart">
            <a href="{{route('viewCart')}}"> <div class="place__orders"><p>View Cart</p></div></a>
        </div>
        <div class="view___cart_checkout">
            <a href="{{route('customer.checkout')}}"> <div class="place__orders active__button"><p>Checkout</p></div></a>
        </div>
    </div>
   
