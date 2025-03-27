@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    view()->share('subtotal', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $discount = Session::get('discount') ? Session::get('discount') : 0;
@endphp
<div class="col-sm-9">
    <div class="vcart-inner">
        <div class="cart-title">
            <h4>Shopping Cart</h4>
        </div>
        <div class="vcart-content">
            <div class="view__cart__products">
                <p class="header_view">Product</p>
                @foreach (Cart::instance('shopping')->content() as $item)
                    <div class="mini-cart-item view__cart_item">
                        <div class="cart-quantity-content">
                            <button class="mini-cart-change cart_increment" data-id="{{ $item->rowId }}" type="button">
                                <i class="fa-solid fa-angle-up"></i>
                            </button>

                            <span>{{ $item->qty }}</span>

                            <button class="mini-cart-change cart_decrement "
                                @if ($item->qty == 1) disabled @endif data-id="{{ $item->rowId }}"
                                type="button">
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
                            <button class="mini-cart-change cart_remove view_delete" data-id="{{ $item->rowId }}"
                                type="button">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="button__list">
        <div class="home__shopping"><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left-long"></i> Contnue
                Shopping</a></div>
        <div class="home__shopping"><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Back to home</a>
        </div>
    </div>
    <div class="coupon-form">
        <label class="form-label" for="coupon_label">Using A Promo Code?</label>
        <form action="">
            <input type="text" placeholder="apply coupon" id="coupon_label" />
            <button>Apply</button>
        </form>
    </div>
</div>
<div class="col-sm-3">
    <div class="cart-summarys">
        <h5>Cart totals</h5>
        <table class="table">
            <tbody>
                <tr>
                    <td>Sub Total</td>
                    <td>৳{{ $subtotal }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td>৳{{ $discount }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>৳{{ $subtotal + $shipping - $discount }}</td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('customer.checkout') }}" class="go_cart">Process To Checkout</a>
    </div>
</div>
