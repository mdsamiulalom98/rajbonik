@php
    $districts = \App\Models\District::distinct()->select('district')->orderBy('district', 'asc')->get();
@endphp

<div class="address-back-button">
    <button id="addressModalClose">
        <i class="fa fa-arrow-left"></i>
    </button>
    <span>Edit Address for Delivery</span>
</div>
<div class="address-modal-box">
    <div class="row">
        <input type="hidden" id="hidden_id" name="hidden_id" value="{{$edit_data->id}}">
        <div class="col-6 col-sm-6">
            <div class="form-group mb-3">
                <label for="district"> District *</label>
                <select type="district" id="district" class="form-control form-select district @error('district') is-invalid @enderror"
                    name="district" required>
                    <option value="">Select District</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->district }}" @if($edit_data->district == $district->district) selected @endif >{{ $district->district }}</option>
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
                    name="area_id" required  @foreach ($districts as $district)  @if($edit_data->area_id == $district->area_id) selected @endif @endforeach >
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
                    name="house_no" value="{{$edit_data->house_no}}" placeholder="" required />
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
                    name="floor_no" value="{{$edit_data->floor_no}}" placeholder="" required />
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
                    name="block" value="{{$edit_data->block}}" placeholder="" required />
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
                    name="flat_no" value="{{$edit_data->flat_no}}" placeholder="" required />
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
                    name="road_no" value="{{$edit_data->road_no}}" placeholder="" required />
                @error('road_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-sm-12">
            <div class="form-group mb-3">
                <label for="name"> Full Name *</label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{$edit_data->name}}" placeholder="" required />
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-sm-12">
            <div class="form-group mb-3">
                <label for="phone"> Phone Number *</label>
                <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                    name="phone" value="{{$edit_data->phone}}" placeholder="" required />
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-12">
            <div class="address-submit-button">
                <button type="button" id="updateAddressButton">Update Address Manually</button>
            </div>
        </div>
    </div>
</div>
