 @php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
    $discount = Session::get('discount') ? Session::get('discount') : 0;
    $cart = Cart::instance('shopping')->content();

   if(Auth::guard('customer')->user()){
        $session_address = Session::get('session_address', '');

        $customer_id = Auth::guard('customer')->user()->id;
        $selected = App\Models\CustomerAddress::where(['customer_id' => $customer_id, 'active' => 1])->count();
        if($selected > 0){
          $session_address = 1;
        }
   }
@endphp
      <div class="col-12 col-sm-4 col-sm-4 order-sm-2 order-lg-2 order-md-2 " >
        <div class="card-bodys" >
            <div class="cartlists">
                 <div class="vcart-content">
                  <div class="view__cart__products">
                    <p class="header_view">Product(s)</p>
                    @foreach (Cart::instance('shopping')->content() as $item)
                        <div class="mini-cart-item view__cart_item">
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
                                <button class="mini-cart-change cart_remove view_delete" data-id="{{ $item->rowId }}" type="button">
                                       <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
            
                        </div>
                    @endforeach
                     
                  </div>
                 </div>
            </div>
        </div>
    
     <div class="table__section">
           <table class="table checkout__table mt-3">
                <tr>
                    <td>Subtotal</th>
                    <td>৳ {{ $subtotal }}</td>
                </tr>
                <tr>
                    <td>Delivery Charge</th>
                    <td>৳ {{ $shipping }}</td>
                </tr>
                <tr>
                    <td>Discount</th>
                    <td>৳ {{ $discount + $coupon }}</td>
                </tr>
                <tr>
                    <td><b>Total</b></th>
                    <td><b>৳ {{ $subtotal + $shipping - ($discount + $coupon) }}</b></td>
                </tr>
          
        </table>
      </div>
  </div>
   <div class="col-12 col-lg-8 col-md-8 col-sm-8 order-sm-1 order-lg-1 order-md-1 ">
        <div class="order_address" id="customerAddresses">
            @include('frontEnd.layouts.ajax.customeraddresses')
        </div>
         <div class="order-save-button mt-3">
            <button type="submit" class="btn btn-primary w-100">Order Save</button>
        </div>
        <div class="home__shopping back__cart_checkout mt-2 mb-3 text-center"><a href="{{route('viewCart')}}"><i class="fa-solid fa-arrow-left-long"></i> Back To Cart</a></div>
   </div>