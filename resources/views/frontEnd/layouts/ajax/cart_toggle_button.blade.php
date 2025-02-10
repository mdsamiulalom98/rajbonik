@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
@endphp

<span class="cart-button-top">
    {{ Cart::instance('shopping')->content()->count() }}
    {{ Cart::instance('shopping')->content()->count() > 1 ? 'items' : 'item' }}
</span>
<span class="cart-button-bottom">
    ৳{{ $subtotal }}
</span>
