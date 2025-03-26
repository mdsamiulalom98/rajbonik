@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
    $discount = Session::get('discount') ? Session::get('discount') : 0;
@endphp
<table class="table checkout__table mt-2">
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