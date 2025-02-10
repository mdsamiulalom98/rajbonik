@extends('frontEnd.layouts.master')
@section('title', 'Customer Checkout')
@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
@endpush 
@section('content')
 @php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
    $discount = Session::get('discount') ? Session::get('discount') : 0;
    $cart = Cart::instance('shopping')->content();
@endphp
<style> 
.select__date__time {
    margin-bottom: 15px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.form-select {
    border-radius: 6px;
    padding: 10px;
    font-size: 14px;
    background-color: #fff;
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
}
</style>
<section class="address__section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="order_address">
                    <div class="address_details table-responsive-sm">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fa-solid fa-location-dot"></i> Select a Delivery Address</h6>
                            </div>
                            <div class="card-body">
                               <div class="add_address">
                                   <button><i class="fa-solid fa-plus"></i> New Address</button>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order_address mt-3">
                    <div class="address_details">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fa-solid fa-clock"></i> Preferred Delivery Time</h6>
                            </div>
                            <div class="card-body"> 
                              <div class="select__date__time">
                                <label for="order_date" class="form-label">Select Date</label>
                                <select name="order_date" id="order_date" class="form-select">
                                    <option value="">Today, 6 Feb</option>
                                    <option value="">Tomorrow, 7 Feb</option>
                                    <option value="">Monday, 8 Feb</option>
                                    <option value="">Tuesday, 9 Feb</option>
                                    <option value="">Wednesday, 10 Feb</option>
                                </select>
                            </div>

                            <div class="select__date__time mt-3">
                                <label for="order_time" class="form-label">Select Time</label>
                                <select name="order_time" id="order_time" class="form-select">
                                    <option value="">4:00PM - 5:00PM</option>
                                    <option value="">6:00PM - 7:00PM</option>
                                    <option value="">8:00PM - 9:00PM</option>
                                    <option value="">10:00PM - 11:00PM</option>
                                    <option value="">10:00AM - 11:00AM</option>
                                </select>
                            </div>
                            </div>
                         </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

