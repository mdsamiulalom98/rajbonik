@extends('frontEnd.layouts.hostel.master')
@section('title', 'Change Password')
@section('content')
<div class="page-header">
    <h5>Change Password</h5>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-sm-6">
            <div class="content-inner">
                <form action="{{ route('hostel.password_update') }}" method="POST"
                    class="row justify-content-center" data-parsley-validate="">
                    @csrf
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="old_password">Old Password *</label>
                            <input type="password" id="old_password"
                                class="form-control @error('old_password') is-invalid @enderror"
                                name="old_password" value="{{ old('old_password') }}" required>
                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="new_password">New Password *</label>
                            <input type="password" id="new_password"
                                class="form-control @error('new_password') is-invalid @enderror"
                                name="new_password" value="{{ old('new_password') }}" required>
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="confirm_password">Confirmed Password *</label>
                            <input type="password" id="confirm_password"
                                class="form-control @error('confirm_password') is-invalid @enderror"
                                name="confirm_password" value="{{ old('confirm_password') }}" required>
                            @error('confirm_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <button type="submit" class="btn-submit">Update</button>
                        </div>
                    </div>
                    <!-- col-end -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
@endpush
