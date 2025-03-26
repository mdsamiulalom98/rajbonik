@php
    $session_address = Session::get('session_address', '');
@endphp
@php
    $districts = \App\Models\District::distinct()->select('district')->orderBy('district', 'asc')->get();
@endphp
<style>
    .address-back-button {
        width:100% !important;
    }
    .address-modal-box {
        padding: 25px;
        max-width: 100% !important;
        height: auto !important;
        background-color: #fff;
    }
   
</style>
@if ($session_address)
    <div class="address_details table-responsive-sm">
        <div class="card">
            <div class="card-header">
                <h6><i class="fa-solid fa-location-dot"></i> Delivery Address</h6>
            </div>
            <div class="card-body">
                <div class="delivery-item">
                    <div class="left">
                        <p>Flat {{ $selected_address->flat_no }}, Floor {{ $selected_address->floor_no }}, House
                            {{ $selected_address->house_no }}, {{ $selected_address->road_no }},
                            {{ $selected_address->area_id }}, {{ $selected_address->district }} </p>
                        <strong>{{ $selected_address->label }}</strong>
                    </div>
                    <div class="right">
                        <button type="button" id="changeAddress">Change</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
@if(Auth::guard('customer')->user())
    <div class="address_details table-responsive-sm">
        <div class="card">
            <div class="card-header">
                <h6><i class="fa-solid fa-location-dot"></i> Select a Delivery Address</h6>
            </div>
            <div class="card-body">
                <div class="add_address">
                    <button type="button" id="createNewAddress"><i class="fa-solid fa-plus"></i> New Address</button>
                </div>
                @foreach ($customer_addresses as $key => $address)
                    <div class="customer-address-items {{ $address->active == 1 ? 'active' : '' }}" >
                        @if ($address->active == 1)
                        <div class="icon customer-address-item" data-id="{{ $address->id }}">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        @endif
                        <div class="left customer-address-item" data-id="{{ $address->id }}">
                            <p>Flat {{ $address->flat_no }}, Floor {{ $address->floor_no }}, House {{ $address->house_no }}, {{ $address->road_no }}, {{ $address->area_id }}, {{ $address->district }}</p>
                            <p>{{ $address->label }}</p>
                            <p>{{ $address->phone }}</p>
                        </div>
                        <div class="right">
                            <button data-id="{{$address->id}}" id="address___edit">Edit</button>
                            <button data-id="{{$address->id}}" id="address___delete">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
<div class="address-back-button">
    <span>Shipping information</span>
</div>
<div class="address-modal-box">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-group mb-3">
                <label for="name"> Full Name *</label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{ old('name') }}" placeholder="" required />
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="form-group mb-3">
                <label for="phone"> Phone Number *</label>
                <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                    name="phone" value="{{ old('phone') }}" placeholder="" required />
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="district"> District *</label>
                <select type="district" id="district" class="form-control form-select district @error('district') is-invalid @enderror"
                    name="district" required>
                    <option value="">Select District</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->district }}">{{ $district->district }}</option>
                    @endforeach
                </select>
                @error('district')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="area_id"> Delivery Area *</label>
                <select type="area_id" id="area_id" class="form-control form-select area @error('area_id') is-invalid @enderror"
                    name="area_id" required>
                    <option value="">Select Delivery Area</option>

                </select>
                @error('area_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="house_no"> House No *</label>
                <input type="text" id="house_no" class="form-control @error('house_no') is-invalid @enderror"
                    name="house_no" value="{{ old('house_no') }}" placeholder="" required />
                @error('house_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="floor_no"> Floor No *</label>
                <input type="text" id="floor_no" class="form-control @error('floor_no') is-invalid @enderror"
                    name="floor_no" value="{{ old('floor_no') }}" placeholder="" required />
                @error('floor_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="block"> Block/Sector *</label>
                <input type="text" id="block" class="form-control @error('block') is-invalid @enderror"
                    name="block" value="{{ old('block') }}" placeholder="" required />
                @error('block')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="flat_no"> Flat No *</label>
                <input type="text" id="flat_no" class="form-control @error('flat_no') is-invalid @enderror"
                    name="flat_no" value="{{ old('flat_no') }}" placeholder="" required />
                @error('flat_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-12 col-sm-12">
            <div class="form-group mb-3">
                <label for="road_no"> Road/Street *</label>
                <input type="text" id="road_no" class="form-control @error('road_no') is-invalid @enderror"
                    name="road_no" value="{{ old('road_no') }}" placeholder="" required />
                @error('road_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        
       <div class="col-sm-6">
            <div class="select__date__time">
            <label for="order_date" class="form-label">Select Date</label>
            <select name="order_date" id="order_date" class="form-select">
                @for ($i = 0; $i < 5; $i++)
                    <option value="{{ now()->addDays($i)->format('Y-m-d') }}">
                        {{ $i == 0 ? 'Today' : ($i == 1 ? 'Tomorrow' : now()->addDays($i)->format('l')) }}, 
                        {{ now()->addDays($i)->format('j M') }}
                    </option>
                @endfor
            </select>
        </div>
       </div>

        <div class="col-sm-6">
            <div class="select__date__time">
            <label for="order_time" class="form-label">Select Time</label>
            <select name="order_time" id="order_time" class="form-select">
                <!-- Available time slots will be loaded here -->
            </select>
        </div>
        </div>
        <div class="col-sm-12">
               <div class="col-sm-12 mt-2">
                                <div class="radio_payment">
                                    <label id="payment_method">Payment Method</label>
                                </div>
                                <div class="payment-methods">
                                   
                                    <div class="form-check p_cash payment_method" data-id="cod">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="inlineRadio1" value="Cash On Delivery" checked required />
                                        <label class="form-check-label" for="inlineRadio1">
                                            Cash On Delivery
                                        </label>
                                    </div>
                                    @if ($bkash_gateway)
                                        <div class="form-check p_bkash payment_method" data-id="bkash">
                                            <input class="form-check-input" type="radio"
                                                name="payment_method" id="inlineRadio2" value="bkash"
                                                required />
                                            <label class="form-check-label" for="inlineRadio2">
                                                Bkash
                                            </label>
                                        </div>
                                    @endif
                                    @if ($shurjopay_gateway)
                                        <div class="form-check p_shurjo payment_method" data-id="nagad">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="inlineRadio3" value="shurjopay" required />
                                            <label class="form-check-label" for="inlineRadio3">
                                                Nagad
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
        </div>

    </div>
</div>
    @endif

@endif
